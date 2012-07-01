
<div id="content-wrapper" class="span8 offset2 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
            <fieldset>
                <div class="control-group">
                    <?php echo form_label( lang('user'), 'username', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_username); ?></div>
                </div>
                        
                <div class="control-group">
                    <?php echo form_label( lang('password'), 'password', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_password($form_password); ?></div>
                </div>
                        
                <hr>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => lang('login'), 'class' => 'btn',)); ?>
                    </div>
                            
                    <div class="clearfix hspace"></div>
                            
                    <p class="alt-links">
                        <?php echo anchor('ucp/register/', '<i class="icon-user"></i> '. lang('register') ); ?> &bull;
                        <?php echo anchor('ucp/recover/', '<i class="icon-lock"></i> '. lang('forgot_password') .'?'); ?>
                    </p>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
            
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
