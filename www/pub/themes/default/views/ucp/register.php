
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
                    <?php echo form_label( lang('email_address'), 'email', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_email); ?></div>
                </div>
                
                <div class="control-group">
                    <?php echo form_label( lang('confirm_email_address'), 'email-conf', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_email_conf); ?></div>
                </div>
                        
                <div class="control-group">
                    <?php echo form_label( lang('password'), 'password', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_password($form_password); ?></div>
                </div>
                        
                <div class="control-group">
                    <?php echo form_label( lang('confirm_password'), 'password-conf', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_password($form_passsword_conf); ?></div>
                </div>
                
                
                <div class="control-group">
                    <?php echo form_label( lang('group'), 'group', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_dropdown('group', $groups); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( lang('server'), 'server', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_dropdown('server', $servers); ?></div>
                </div>
                
                
                <?php if (count($groups)>0): ?>
                <?php endif; ?>
                
                <hr>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => lang('register'), 'class' => 'btn',)); ?>
                    </div>
                            
                    <div class="clearfix hspace"></div>
                            
                    <p class="alt-links">
                        <?php echo anchor('ucp/login/', '<i class="icon-user"></i> '. lang('login') ); ?> &bull;
                        <?php echo anchor('ucp/recover/', '<i class="icon-lock"></i> '. lang('forgot_password') .'?'); ?>
                    </p>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
