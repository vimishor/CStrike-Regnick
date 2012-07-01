
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <?php if (!$groups): ?>
            <h3><?php echo lang('no_groups'); ?>.</h3>
            
            <?php else: ?>
            
                <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>
                <fieldset>                        
                    <div class="control-group">
                        <?php echo form_label($serverName, 'sname', array('class' => 'control-label')); ?>
                        <div class="controls">
                            <select name="user_group">
                            <?php foreach($groups as $group): ?>
                                
                                <?php if ($group->ID == $userGroup): ?>
                                <option value="<?php echo $group->ID; ?>" selected="selected"><?php echo $group->name; ?></option>
                                
                                <?php else: ?>
                                <option value="<?php echo $group->ID; ?>"><?php echo $group->name; ?></option>
                                
                                <?php endif; ?>                                
                            
                            <?php endforeach; ?>
                            </select>                                
                        </div>
                        
                        <hr>
                                                    
                        <div class="span4 offset2">
                            <a class="btn" href="<?php echo site_url('acp/user/edit/'.$userID); ?>">&larr; <?php echo lang('profile_settings'); ?></a> 
                                <?php echo form_submit(array('name'  => 'submit', 'value' => lang('save'), 'class' => 'btn',)); ?>
                        </div>
                    </div>    
                </fieldset>
                <?php echo form_close(); ?>
            
            <?php endif; ?>
            
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
