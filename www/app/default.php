<?php 
     if ( ! defined('BASEPATH')) exit('No direct script access allowed');
     
     add_template('overall_header'); 
?>

<body>

<header>
    <div class="title"><?php echo $page_title; ?></div>
</header>

<div id="content">
    <?php add_template('overall_menu'); ?>
    <div class="page" style="text-align: center;">
        <?php if (isset($page_message) && ($page_message !== null) ): ?>
            <p><?php echo $page_message; ?></p>
        <?php endif; ?>
    </div>
</div><!-- //end #content -->

<?php add_template('overall_footer'); ?>