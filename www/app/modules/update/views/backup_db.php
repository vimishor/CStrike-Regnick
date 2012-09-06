    
<div id="content-wrapper" class="span12 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <p>
               &nbsp;&nbsp;This process can modify the database structure and the data contained in this database. Therefore, we recomend to create a 
               database backup before proceeding with the update.<br>
               &nbsp;&nbsp;To start a database backup, click on <code>Create backup</code> button bellow and wait for download to start. After you download
               backup archive, click on <code>Update</code>.
               <hr>
            </p>
            
            <div class="text-center">
                <a href="<?php echo site_url('update/backup/db'); ?>" class="btn btn-success">Create backup</a>
                <a href="<?php echo site_url('update/database/force'); ?>" class="btn btn-info">Update</a>
                <div class="clearfix"><br></div>
                <a href="<?php echo site_url('update/backup/clean'); ?>" class="btn btn-medium btn-block btn-warning" type="button">Delete backup files from server</a>
            </div>
            
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
