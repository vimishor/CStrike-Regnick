
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
                    <?php echo form_label( lang('name'), 's-name', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($input_name); ?></div>
                </div>
                
                <div class="control-group">
                    <?php echo form_label( lang('address'), 's-address', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($input_address); ?></div>
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
