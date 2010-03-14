<?php
/**
 * FrontEnd Builds Framework
 * For when you just need to get the frontend done and 
 * the backend or official framework solution is unknown
 *
 * Deliverables
 *
 * @version 1.0
 * @author  peng@pengxwang.com
 */

$builds = scandir(realpath(dirname(__FILE__)));
$base_url = 'http://' 
    . $_SERVER['SERVER_NAME'] 
    . pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
$last_modified; $list;
foreach ($builds as $file) 
{
    if (strncmp($file, '.', 1) == 0 
        OR in_array(pathinfo($file, PATHINFO_EXTENSION), array('php'))) 
        { continue; }
    $list .= "<a href=\"$base_url/$file\">$file</a><br/>" . PHP_EOL;
    if ($f_last_modified = filemtime($file) 
        AND ( ! isset($last_modified) OR $last_modified < $f_last_modified)) 
        { $last_modified = $f_last_modified; }
}

?>

<h1>Final HTML Builds</h1>
<!-- <h3>Project Name Here</h3> -->
<h3>last modified: <?php echo date("F d Y H:i:s", $last_modified) ?></h3>
<?php echo $list ?>