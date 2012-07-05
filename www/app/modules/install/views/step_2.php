
<div id="content-wrapper" class="span8 offset2 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal', 'autocomplete' => 'off')); ?>
            <fieldset>
                <div class="control-group">
                    <?php echo form_label( 'Hostname', 'hostname', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_hostname); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'Database', 'database', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_database); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'Username', 'username', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_username); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'Password', 'password', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_password($form_password); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'Tables prefix', 'prefix', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_prefix); ?></div>
                </div>
                        
                <hr>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => 'Test connection', 'class' => 'btn',)); ?>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
            
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
