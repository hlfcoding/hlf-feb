<?php if ( ! defined('FEB_APP')) exit('No direct script access allowed'); ?>
<?php $p =& $partials // prep ?>
<?php ob_start() // head ?>
<?php // so i still get syntax highlighting ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US" dir="ltr">
<head profile="http://gmpg.org/xfn/11">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" /> 
    <meta content="en-US" http-equiv="Content-Language"/> 
    <meta content="no" http-equiv="Imagetoolbar"/> 
    <meta content="all" name="Robots"/> 
    <link rel="index" title="%(site_title)s" href="%(site_root)s" /> 
    <link rel="canonical" href="%(site_root)s" /> 
    <link rel="shortcut icon" href="%(site_root)s/img/favicon.ico" /> 

    <title>%(page_title)s &lsaquo; %(site_title)s</title>

    <meta name="description" description="%(meta_description)s" />
    <meta name="keywords" description="%(meta_keywords)s" />
    %(meta_extra)s
    <link href="%(site_root)s/css/layout.css" rel="stylesheet" type="text/css" media="all"/>
    <!--[if lte IE 8]>
    <link href="%(site_root)s/css/layout_screen.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="%(site_root)s/css/layout_mobile.css" rel="stylesheet" type="text/css" media="mobile"/>
    <link href="%(site_root)s/css/layout_print.css" rel="stylesheet" type="text/css" media="print"/>
    <![endif]-->    
    <link href="%(site_root)s/css/color.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="%(site_root)s/css/type.css" rel="stylesheet" type="text/css" media="all"/>
    <!--[if lte IE 8]>
    <link href="%(site_root)s/css/ie/lte_ie8.css" rel="stylesheet" type="text/css" media="all"/>
    <![endif]--><!--[if lte IE 7]>
    <link href="%(site_root)s/css/ie/lte_ie7.css" rel="stylesheet" type="text/css" media="all"/>
    <![endif]-->     
    %(style_extra)s
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script> 
    <script>
    $(document).ready(function () 
        {
            // $('a').click(function (e) { e.preventDefault(); });
            $('.ft').each(function (i, val) { if ($(this).text() == '') $(this).addClass('empty'); }); 
            var current = $('.panel').find('a[href$="%(current_page)s"]'); 
            if (current.length == 0 && '%(current_page)s' == 'home') { current = $('.panel').find('a[href$="index.php"]'); }
            current.addClass('current');
            $('#search .fText[name="search_var"]').focus(function () {
                if ($(this).attr("value").toLowerCase() == 'search') 
                    { $(this).attr("value", ""); }
            }).blur(function () {
                if ($(this).attr("value").toLowerCase() == '') 
                    { $(this).attr("value", 'search'); }
            });
        }
    );
    </script>
    %(script_extra)s
</head>
<?php $p['head'] = ob() ?>

<hr class="page-top-edge core-row-line core-extended"/>
<div id="the-head" class="page-head-container core-grid">
    <div class="page-head core-doc-elastic core-doc-medium core-grid-row core-col-set-13">
        <div class="core-col core-col-1 core-center">
            <h1 class="logo core-replaced">%(logo)s</h1>
        </div>
        <div class="core-col core-col-2">
            <h2 class="tagline core-em core-serif">%(tagline)s</h2>
        </div>
    </div><!-- .page-head -->
</div><!-- .page-head-container -->
<?php $p['page_head'] = ob() ?>

<div id="the-body" class="page-body-container">
    <div class="page-body core-doc-elastic core-doc-medium core-grid">
<?php $p['page_body_start'] = ob() ?>

        <div class="main-section core-doc-section core-grid-row core-col-set-13">
<?php $p['main_section_start'] = ob() ?>

            <div class="side-content-container core-col core-col-1">
                <div class="side-content">
<?php $p['side_content_start'] = ob() ?>

<div class="navigation">
<?php foreach ($c_partials->navigation->panels->children() as $panel) : ?>
    <div class="panel core-box core-extended">
        <div class="head">
            <h3 class="h-main core-serif core-em"><?php echo $panel->name ?></h3>
        </div>
        <div class="body core-text-medium">
            <ul class="core-col-menu core-link-menu">
                <?php foreach ($panel->links->children() as $link) : ?>
                <li>
                    <?php 
                    $abbr = is_array((array) $link->name->attributes()) 
                        ? ' title="'.el('abbr', $link->name->attributes()).'"' 
                        : '' 
                    ?>
                    <a href="<?php echo $link->url ?>"<?php echo $abbr ?>>
                        <span><?php echo $link->name ?></span>
                    </a>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="foot core-empty">&nbsp;</div>
    </div>
<?php endforeach ?>
</div><!-- .navigation -->
<?php $p['navigation'] = ob() ?>

                </div><!-- .side-content -->
            </div><!-- .side-content-container -->
<?php $p['side_content_end'] = ob() ?>

            <div class="main-content-container core-col core-col-2">
                <div class="main-content">
<?php $p['main_content_start'] = ob() ?>
                
                </div><!-- .main-content -->
            </div><!-- .main-content-container -->
<?php $p['main_content_end'] = ob() ?>

        </div><!-- .main-section -->
<?php $p['main_section_end'] = ob() ?>

    </div><!-- .page-body -->
</div><!-- .page-body-container -->
<?php $p['page_body_end'] = ob() ?>

<div id="the-foot" class="page-foot-container core-grid">
    <div class="page-foot core-doc-elastic core-doc-medium core-grid-row core-col-set-13">
        <div class="core-col core-col-1">
            &nbsp;
        </div>
        <div class="core-col core-col-2">
            <div class="meta" id="copyright">%(copyright)s</div>
            <div class="meta" id="credit">%(credit)s</div>
        </div>
    </div>
</div><!-- .page-foot -->
<?php $p['page_foot'] = ob() ?>

<?php ob(TRUE) ?>