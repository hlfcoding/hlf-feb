<?php  
/**
 * FrontEnd Builds Framework
 * For when you just need the frontend done and the backend solution is unknown
 *
 * Application
 * - written with CodeIgniter syntax conventions
 * - unless you want to change application behavior, do not modify this file
 * 
 * @version     1.0
 * @author      peng@pengxwang.com
 * @package     FEB Framework
 * @subpackage  Core
 */
/*
    Goals:
    - separation of content and presentation
    - linked builds pages
    - DRY refactoring of presentation, but remaining flexible
    - easy-to-understand design pattern
    - minimal php boilerplate, overhead, libraries, etc.
    - relatively pretty urls
    - easy to export final xhtml builds
*/

// uncomment to check php version
// phpinfo(); exit();

// declaration - for documentation

namespace Feb;  // allows integration of third-party code
$partials;      // initial storage for partials as they are set in partials.php
$c_pages;       // content for pages
$c_partials;    // content for partials
$p_partials;    // final storage for all parameterized partials
$cur_url;
$cur_page;      // current view
$cur_sub_page;  // current view
$c_page;        // page content, shortcut
$c_section;     // section content, shortcut
$output;        // final output, can be saved as html

// bootstrap

// constants
// TODO - namespace
define('FEB_APP', true);
require_once 'conf.php';
if ( ! defined('FEB_HTML')) { 
    define('FEB_HTML', false);
}

// initialize globals
$partials = array();
$p_partials = array('global' => array());

require_once 'common.php';

// prep partials

$c_partials = simplexml_load_file('partials.xml');
require 'partials.php';
foreach ($partials as $_name => $_partial) 
{
    // automagically create parameter slots
    $_matches = array();
    preg_match_all('/%\(([_a-z][-_a-z]+)\)(?:d|s)/i', $_partial, $_matches);
    $_keys = el(1, $_matches, array());
    $p_partials[$_name] = array_fill_keys($_keys, '');
}

// set global template vars

// routing - no trailing slashes
$p_partials['global']['site_url'] = 'http://' 
    . $_SERVER['SERVER_NAME'] 
    . str_replace($_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF']); 
    // t($p_partials['global']['site_url']); // debug
$p_partials['global']['site_root'] = str_replace('/index.php', '', $p_partials['global']['site_url']);      
    // t($p_partials['global']['site_root']); // debug

$p_partials['global']['site_name'] = $c_partials->head->site_title;
$p_partials['global']['author_name'] = $c_partials->head->author_name;
$p_partials['global']['author_email'] = $c_partials->head->author_email;
$p_partials['global']['current_year'] = date('Y');

foreach ($p_partials as $_name => &$_p_partial) 
{
    // automagically add values to previously created parameter slots
    // requires one-to-one naming scheme
    // (array) partials > (array) partial > (object) param
    if ( ! empty($_p_partial) AND property_exists($c_partials, $_name)) { 
        $_p_partial = array_merge($_p_partial, get_object_vars($c_partials->$_name)); 
    }
}
    // t($p_partials); // debug

// prep page

$c_pages = simplexml_load_file('pages.xml');
// postback
$cur_url = explode('/', trim(el('PATH_INFO', $_SERVER, 'home'), '/'));
$cur_page = ced(array_shift($cur_url), 'home');
$cur_sub_page = ced(array_shift($cur_url), false);
$cur_page = property_exists($c_pages, $cur_page) ? $cur_page : 'fof';
$c_page = $c_pages->$cur_page;
if ($cur_sub_page) {
    $c_section = $c_page;    
    $c_page = property_exists($c_page, 'sub_page') && $cur_sub_page
        ? ( is_object(el($cur_sub_page - 1, $c_page->sub_page))
            ? $c_page->sub_page[$cur_sub_page - 1]
            : $c_pages->fof )
        : ( el_by_attr($c_page->sub_page, $cur_sub_page) 
            ? el_by_attr($c_page->sub_page, $cur_sub_page) 
            : $c_pages->fof );
}
// prep all page vars if needed
$p_partials['head']['page_title'] = $c_page->title;
$p_partials['head']['current_page'] = $cur_page;
// output page

// save
ob_start();
// the page
require "page_$cur_page.php";
// flush
$output = ob_get_contents();
ob_end_clean();
$output = clean_html($output);
// save as html
if (FEB_HTML === true) {
    file_put_contents("html/${cur_page}${cur_sub_page}.html", $output, FILE_USE_INCLUDE_PATH | LOCK_EX);
}
echo $output;