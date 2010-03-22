<?php if ( ! defined('FEB_APP')) exit('No direct script access allowed');
/**
 * FrontEnd Builds Framework
 * For when you just need the frontend done and the backend solution is unknown
 *
 * Common Functions
 * - written with CodeIgniter syntax conventions
 * - template functions are named in a shorthand fashion
 * - unless you want to change application behavior, do not modify this file
 *
 * @version     1.0
 * @author      peng@pengxwang.com
 * @package     FEB Framework
 * @subpackage  Core
 */

function e ($x) 
{ // echo
    global $p_partials;
    echo dsprintf($x, el('global', $p_partials, array()));
}
function t ($x) 
{ // trace - simple logging tool
    ?><pre><?php  
    var_dump($x);
    ?></pre><?php
    return $x;  
}
function a ($x, $n) 
{ // attributes
    e(el($x, $n->attributes()));
}

function el ($item, $array, $default = FALSE) 
{ // safely get array item
    if ( ! isset($array[$item]) OR $array[$item] === "")
    {
        return $default;
    }
    return $array[$item];
}

function p ($name, $dynamic_parameters = array(), $echo = TRUE, $dump = FALSE) 
{ // hydrate a partial
    global $partials, $p_partials;
    // t($p_partials);
    // base partial pattern
    $partial = el($name, $partials, '');
    // parameters
    if ($partial_parameters = el($name, $p_partials, array())) // default empty 
    { 
        $partial_parameters = array_merge($partial_parameters, $dynamic_parameters); 
    }
    $partial_parameters = array_merge($partial_parameters, $p_partials['global']);
    $partial = dsprintf($partial, $partial_parameters);
    if ($echo) 
    { 
        e($partial); 
    }
    else 
    { 
        return $partial; 
    }
}
function pp ($name)
{ // plainly output a partial
    global $p_partials;
    return array_keys(el($name, $p_partials, array()));
}
function el_by_attr ($xml, $value, $key = "name") 
{ // find an xml element by an attribute
    foreach ($xml as $child) 
    {
        if (el($key, $child->attributes()) == $value) 
        {
            return $child;
        }
    }
    return false;
}
function dsprintf () 
{ // partial hydration workhorse
    $data = func_get_args(); // get all the arguments
    $string = array_shift($data); // the string is the first one
    if (is_array(func_get_arg(1))) 
    { // if the second one is an array, use that
        if (empty(func_get_arg(1))) {
            return;
        }
        $data = func_get_arg(1);
    }
    $used_keys = array();
    // get the matches, and feed them to our function
    $string = preg_replace('/\%\((.*?)\)(.)/e'
        , 'dsprintf_match(\'$1\', \'$2\', \$data, \$used_keys)',$string); 
    // nested matches
    $string = preg_replace('/\%\((.*?)\)(.)/e'
        , 'dsprintf_match(\'$1\', \'$2\', \$data, \$used_keys)',$string); 
    $data = array_diff_key($data, $used_keys); // diff the data with the used_keys
    return vsprintf($string, $data); // yeah!
}
function dsprintf_match ($m1, $m2, &$data, &$used_keys) 
{ // partial hydration workhorse's callback
    if (isset($data[$m1])) 
    { // if the key is there
        $str = $data[$m1];
        $used_keys[$m1] = $m1; // dont unset it, it can be used multiple times
        return sprintf("%" . $m2, $str); // sprintf the string, so %s, or %d works like it should
    } 
    else 
    {
        return "%" . $m2; // else, return a regular %s, or %d or whatever is used
    }
}
function ob ($last = FALSE) // do output buffering
{
    $output = ob_get_contents();
    ob_end_clean();
    if ( ! $last) ob_start();
    return $output;
}
function ced ($custom, $default = NULL, $echo = FALSE) // custom else default
{
    if ( ! isset($default)) 
    {
        if (is_array($custom)) 
        {
            $default = array();
        }
        elseif (is_string($custom)) 
        {
            $default = '';
        }
        elseif (is_numeric($custom)) 
        {
            $default = 0;
        }
    }
    $var = (ok($custom)) ? $custom : $default;
    if ($echo !== FALSE)
    {
        echo $var;
    }
    return $var;
}
function _empty () 
{ 
    foreach (func_get_args() as $args) // check multiple
    {
        if ( ! is_numeric($args)) 
        { 
            if (is_array($args)) // check array
            { 
                if (count($args, 1) < 1) // md
                { 
                    return TRUE;
                } 
            }
            elseif ( ! isset($args) OR strlen(trim($args)) === 0) // check string
            {
                return TRUE; 
            } 
        } 
    } 
    return FALSE; 
}
function ok ($var)
{
    $bool = @(isset($var) AND _empty($var) === FALSE);
    return $bool;
}
/**
 * @author      Jonhoo
 * @author      modified by peng@pengxwang.com
 * @link        http://snippets.dzone.com/posts/show/1964
 */
function clean_newlines($output)
{
    $pieces = explode("\n", $output);
    $skip = FALSE;
    foreach ($pieces as $key => $str)
    {
        // #modified
        if ($skip) { continue; }
        if (preg_match('/<([a-z]+)[^>]*><\/\1>/i', $str)) {/* t($str); */ continue; }
        if (preg_match('/<(textarea|pre)>/', $str)) 
            { /*_log('ch_clean_newlines');*/ $skip = true; continue; } // start of tag
        if (preg_match('/<\/(textarea|pre)>/', $str)) 
            { /*_log('ch_clean_newlines');*/ $skip = false; continue; } // end of tag
        
        // Makes sure empty lines are ignores
        if ( ! preg_match("/^(\s)*$/", $str))
        {
            $pieces[$key] = preg_replace("/>(\s|\t)*</U", ">\n<", $str);
        }
    }
    return implode("\n", $pieces);
}
function clean_html($output)
{
    // Set wanted indentation
    $indent = str_repeat(" ", 4);
    // Uses previous function to seperate tags
    $output = clean_newlines($output);
    $output = explode("\n", $output);
    // Sets no indentation
    $indent_level = 0;
    $skip = FALSE;
    foreach ($output as $key => $value)
    {
        // #modified
        if ($skip) { continue; }
        if (preg_match('/<(textarea|pre)>/', $value)) 
            { /*_log('ch_clean_newlines');*/ $skip = true; continue; } // start of tag
        if (preg_match('/<\/(textarea|pre)>/', $value)) 
            { /*_log('ch_clean_newlines');*/ $skip = false; continue; } // end of tag
        
        // Removes all indentation
        $value = preg_replace("/\t+/", "", $value);
        $value = preg_replace("/^\s+/", "", $value);
        $indent_replace = "";
        // Sets the indentation from current indent level
        for ($o = 0; $o < $indent_level; $o++)
        {
            $indent_replace .= $indent;
        }
        // If self-closing tag, simply apply indent
        if (preg_match("/<(.+)\/>/", $value))
        { 
            $output[$key] = $indent_replace . $value;
        }
        // If doctype declaration, simply apply indent
        elseif (preg_match("/<!(.*)>/", $value))
        { 
            $output[$key] = $indent_replace . $value;
        }
        // If opening AND closing tag on same line, simply apply indent
        elseif (preg_match("/<[^\/](.*)>/", $value) AND preg_match("/<\/(.*)>/", $value))
        { 
            $output[$key] = $indent_replace . $value;
        }
        // If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level
        elseif (preg_match("/<\/(.*)>/", $value) OR preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $value))
        {
            $indent_level--;
            $indent_replace = "";
            for ($o = 0; $o < $indent_level; $o++)
            {
                $indent_replace .= $indent;
            }
            $output[$key] = $indent_replace . $value;
        }
        // If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level
        elseif (  (preg_match("/<[^\/](.*)>/", $value) AND ! preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $value)) 
                OR preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $value))
        {
            $output[$key] = $indent_replace . $value;
            $indent_level++;
            $indent_replace = "";
            for ($o = 0; $o < $indent_level; $o++)
            {
                $indent_replace .= $indent;
            }
        }
        // Else only apply indentation
        else
        {
            $output[$key] = $indent_replace . $value;
        }
    }
    // Return single string seperated by newline
    $output[] = '<!-- Thanks for reviewing the source. This code has been automatically re-indented. -->';
    $output = implode("\n", $output);
    return $output;
}