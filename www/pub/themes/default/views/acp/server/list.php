
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <div class="pull-right hspace">
                <a class="btn btn-info" href="<?php echo site_url('acp/server/add'); ?>"><?php echo lang('add_server'); ?></a>
            </div>
                
            <table class="table table-striped table-bordered table-condensed hspace">
                <thead>
                    <tr>
                        <th style="width: 14px;">#</th>
                        <th><?php echo lang('name'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$servers): ?>
                    <tr><td colspan="4" style="text-align: center;"><?php echo lang('no_results'); ?>.</td></tr>
                    
                    <?php else: ?>
                        <?php foreach($servers as $server): ?>
                        <tr>
                            <td><a href="#" data-scope="del-server" data-value="<?php echo $server->ID; ?>" title="<?php echo lang('del_server'); ?>" rel="tooltip"><?php echo $server->ID; ?></a></td>
                            <td><a href="<?php echo site_url('acp/server/edit/'.$server->ID.'/'); ?>" title="<?php echo lang('edit_server'); ?>" rel="tooltip"><?php echo (!empty($server->name)) ? $server->name : $server->address; ?> </a> 
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
