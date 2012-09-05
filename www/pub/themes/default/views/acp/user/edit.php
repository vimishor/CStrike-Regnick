
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profile" data-toggle="tab"><?php echo lang('profile'); ?></a></li>
                    <li><a href="#access" data-toggle="tab"><?php echo lang('access'); ?></a></li>
                </ul>
                
                <div class="tab-content"> 
                    <div class="tab-pane active" id="profile"> <!-- #profile tab -->
                    
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
                            <div class="control-group">
                                <?php echo form_label( lang('user_notes'), 'notes', array('class' => 'control-label')); ?>
                                <div class="controls"><?php echo form_textarea($txt_notes); ?></div>
                            </div>
                            
                            <div class="control-group well">
                                <div class="content-header hspace">
                                    <h4 class="section-title"><?php echo lang('connection_flags'); ?></h4>
                                    <div class="subheader"><?php echo lang('flags_description'); ?></div>
                                </div>
                                
                                <div class="row">
                                    <div class="span2">
                                        <label class="radio"><?php echo form_radio($radio_none1); ?>User</label>
                                        <label class="radio"><?php echo form_radio($radio_b); ?>Clan tag</label>
                                        <label class="radio"><?php echo form_radio($radio_c); ?>SteamID</label>
                                        <label class="radio"><?php echo form_radio($radio_d); ?>IP</label>
                                    </div>
                                    
                                    <div class="span3">
                                        <label class="radio"><?php echo form_radio($radio_a); ?><?php echo lang('disc_on_invalid_pass'); ?></label>
                                        <label class="radio"><?php echo form_radio($radio_e); ?><?php echo lang('pass_no_check'); ?></label>
                                    </div>
                                    
                                    <div class="span2">
                                        <label class="radio"><?php echo form_radio($radio_f); ?>Owner</label>
                                        <label class="radio"><?php echo form_radio($radio_none); ?>None</label>
                                    </div>
                                        
                                </div>
                            </div> <!-- /flags well -->
                                
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo form_label( lang('account_is_active').' ?', 'active', array('class' => 'checkbox inline')); ?>
                                    <?php echo form_checkbox($ckbox_public); ?>
                                </div>
                            </div>
                                
                            <hr>
                        
                            <div class="span4 offset2">
                                <div class="span2" style="text-align: center;">
                                    <?php echo form_submit(array('name'  => 'submit', 'value' => 'Save', 'class' => 'btn',)); ?>
                                </div>
                            </div>
                        </fieldset>
                        <?php echo form_close(); ?>
                    </div><!-- /#profile -->
                    
                    <div class="tab-pane" id="access"> <!-- #access tab -->
                        <table class="table table-striped table-bordered table-condensed" id="user-access">
                            <thead>
                                <tr>
                                    <th style="width: 14px;">#</th>
                                    <th><?php echo lang('server_address'); ?></th>
                                    <th><?php echo lang('group'); ?></th>
                                    <th style="width: 100px;"><?php echo lang('access'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$servers): ?>
                                <tr><td colspan="3" class="center"><?php echo lang('user_has_no_access'); ?>.</td></tr>
                                
                                <?php elseif (!array_key_exists(0, $no_access)): ?>
                                <tr id="<?php echo $servers[0]['ID']; ?>">
                                    <td><a href="#" data-scope="del-access" data-serverID="0" data-userID="<?php echo $userID; ?>" title="<?php echo lang('del_access_on_server'); ?>">0</a></td>
                                    <td><?php echo $servers[0]['address']; ?></td>
                                    <td><?php echo isset($servers[0]['group_name']) ? $servers[0]['group_name'] : '-'; ?></td>
                                    <td><a class="has-access" href="<?php echo site_url('acp/user/'.$userID.'/access/0/') ?>" title="<?php echo lang('edit_access_on_server'); ?>"> <?php echo (array_key_exists($servers[0]['ID'], $no_access)) ? lang('no') : lang('yes'); ?></a></td>
                                </tr>
                                </tr>
                                
                                <?php else: ?>
                                    <?php foreach($servers as $server): ?>
                                    <tr id="<?php echo $server['ID']; ?>">
                                        <td><a href="#" data-scope="del-access" data-serverID="<?php echo $server['ID']; ?>" data-userID="<?php echo $userID; ?>" title="<?php echo lang('del_access_on_server'); ?>"><?php echo $server['ID']; ?></a></td>
                                        <td><?php echo $server['address']; ?></td>
                                        <td><?php echo isset($server['group_name']) ? $server['group_name'] : '-'; ?></td>
                                        <td><a class="has-access" href="<?php echo site_url('acp/user/'.$userID.'/access/'.$server['ID'].'/') ?>" title="<?php echo lang('edit_access_on_server'); ?>"> <?php echo (array_key_exists($server['ID'], $no_access)) ? lang('no') : lang('yes'); ?></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div> <!-- /#access tab -->
            </div> <!-- /.tab-content -->
            
        </div> <!-- /.tabbable -->
    </div></div><!-- /#content -->
</div><!-- /#content-wrapper -->

