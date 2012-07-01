
<div id="content-wrapper" class="span12 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 14px;">#</th>
                    <th><?php echo lang('address'); ?></th>
                    <th style="width: 100px;"><?php echo lang('view'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$servers): ?>
                <tr>
                    <td colspan="3" style="text-align: center;"><?php echo lang('no_results'); ?>.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($servers as $server): ?>
                    <tr>
                        <td><?php echo $server->ID; ?></td>
                        <td><a rel="tooltip" title="<?php echo lang('connect_with_steam'); ?>" href="steam://connect/<?php echo $server->address; ?>"><?php echo ($server->name != '') ? $server->name : $server->address; ?></a> </td>
                        <td><a rel="tooltip" title="<?php echo lang('server_team'); ?>" href="<?php echo site_url('server/'.$server->ID.'/team/'); ?>"><?php echo lang('team');?></a> | <a rel="tooltip" title="<?php echo lang('server_members');?>" href="<?php echo site_url('server/'.$server->ID.'/members/'); ?>"><?php echo lang('members');?></a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            </table>
            
            <?php echo $this->pagination->create_links(); ?>
            
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
