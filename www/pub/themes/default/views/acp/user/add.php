
<?php echo $template['partials']['sidebar']; ?>

<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        
        <div class="content-data">
            
            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>
            <fieldset>
                <div class="control-group">
                    <?php echo form_label( lang('user'), 'login', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($input_login); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( lang('password'), 'passwd', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_password($input_passwd); ?></div>
                </div>
                <div class="control-group">
                    <?php echo form_label( lang('email_address'), 'email', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($input_email); ?></div>
                </div>
                
                <div class="control-group well">
                    <div class="content-header hspace">
                        <h4 class="section-title"><?php echo lang('connection_flags'); ?></h4>
                        <div class="subheader"><?php echo lang('flags_description'); ?></div>
                    </div>
                    
                    <div class="row">
                        <div class="span2">
                            <label class="radio"><input type="radio" name="user_flags_b" value="b" checked="checked">Clan tag</label>
                            <label class="radio"><input type="radio" name="user_flags_b" value="c">SteamID</label>
                            <label class="radio"><input type="radio" name="user_flags_b" value="d">IP</label>
                        </div>
                                            
                        <div class="span3">
                            <label class="radio"><input type="radio" name="user_flags_a" value="a" checked="checked"><?php echo lang('disc_on_invalid_pass'); ?></label>
                            <label class="radio"><input type="radio" name="user_flags_a" value="e"><?php echo lang('pass_no_check'); ?></label>
                        </div>
                                
                        <div class="span2">
                            <label class="radio"><input type="radio" name="user_flags_c" value="f">Owner</label>
                        </div>
                                
                        <div class="clearfix"></div>
                        <div class="span7">
                            <p class="help-block"><span class="label label-warning"><?php echo lang('pub_groups_desc'); ?></span></p>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.well -->
                
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox inline"><input type="checkbox" name="active"><?php echo lang('account_is_active'); ?> ?</label>
                    </div>
                </div>
                
                <hr>
                <div class="clearfix"></div>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => lang('save'), 'class' => 'btn',)); ?>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
            <div class="clearfix hspace"><br></div>
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->


