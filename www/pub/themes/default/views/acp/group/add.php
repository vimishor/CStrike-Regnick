
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
                    <?php echo form_label( lang('name'), 'g-name', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($input_name); ?></div>
                </div>
                
                <div class="control-group">
                    <?php echo form_label( lang('access_flags'), 'g-access', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($input_flags); ?></div>
                </div>
                
                <div class="control-group">
                    <div class="controls">
                        <?php echo form_label( lang('make_group_public').' ?', 'g-public', array('class' => 'checkbox inline')); ?>
                        <?php echo form_checkbox('g-public'); ?>
                        <p class="help-block"><span class="label label-warning"><?php echo lang('public_group_desc'); ?></span></p>
                    </div>
                </div>
                                                
                <hr>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => lang('save'), 'class' => 'btn',)); ?>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
                
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
