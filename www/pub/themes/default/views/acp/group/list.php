
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <div class="pull-right hspace">
                <a class="btn btn-info" href="<?php echo site_url('acp/group/add'); ?>"><?php echo lang('add_group'); ?></a>
            </div>
            
            <table class="table table-striped table-bordered table-condensed hspace">
                <thead>
                    <tr>
                        <th style="width: 14px;">#</th>
                        <th><?php echo lang('name'); ?></th>
                        <th style="width: 120px;"><?php echo lang('access'); ?></th>
                        <th style="width: 20px;"><?php echo lang('public'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$groups): ?>
                    <tr><td colspan="4" style="text-align: center;"><?php echo lang('no_results'); ?>.</td></tr>
                    
                    <?php else: ?>
                        <?php foreach($groups as $group): ?>
                        <tr>
                            <td><a href="#" data-scope="del-group" data-value="<?php echo $group->ID; ?>" title="<?php echo lang('del_group'); ?>" rel="tooltip"><?php echo $group->ID; ?></a></td>
                            <td><a href="<?php echo site_url('acp/group/edit/'.$group->ID.'/'); ?>" title="<?php echo lang('edit_group'); ?>" rel="tooltip"><?php echo $group->name; ?></a></td>
                            <td><?php echo $group->access; ?></td>
                            <td><?php echo ($group->public == 1) ? lang('yes') : lang('no'); ?></td> 
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>            
                
            <!-- paginatie -->
            <?php echo $this->pagination->create_links(); ?>
                
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
