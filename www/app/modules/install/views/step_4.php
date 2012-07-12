
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
                    <?php echo form_label( 'SMTP Host', 'smtp_host', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($smtp_host); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'SMTP Port', 'smtp_port', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($smtp_port); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'SMTP User', 'smtp_user', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($smtp_user); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( 'SMTP Pass', 'smtp_pass', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_password($smtp_pass); ?></div>
                </div>
                        
                <hr>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => 'Continue', 'class' => 'btn',)); ?>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
            
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
