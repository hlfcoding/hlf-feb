<?php if ( ! defined('FEB_APP')) exit('No direct script access allowed'); ?>
<?php p('head') ?>
<body id="the-page" class="fof-page feb">
    <?php p('page_start') ?>
        <?php p('page_head') ?>
        <?php p('page_body_start') ?>
            <?php p('main_section_start') ?>
                <?php p('side_content_start') ?>
                    <?php p('navigation') ?>
                <?php p('side_content_end') ?>
                <?php p('main_content_start') ?>
                    <div class="body">
                        <div class="core-text">
                            <?php e($c_page->body->prose) ?>
                        </div>
                        <div class="footnotes core-small core-em core-serif ">
                            <?php e($c_section->body->footnote)?>
                        </div>
                    </div>
                <?php p('main_content_end') ?>
            <?php p('main_section_end') ?>
        <?php p('page_body_end') ?>
        <?php p('page_foot') ?>
    <?php p('page_end') ?>
</body>