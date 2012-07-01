
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            <div class="pull-right hspace">
                <a class="btn btn-info" href="<?php echo site_url('acp/user/add'); ?>"><?php echo lang('add_user'); ?></a>
            </div>
            
            <table class="table table-striped table-bordered table-condensed hspace">
                <thead>
                    <tr>
                        <th style="width: 14px;">#</th>
                        <th>Login</th>
                        <th style="width: 14px;"><?php echo lang('active'); ?></th>
                        <th style="width: 100px;"><?php echo lang('member_since'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$users): ?>
                    <tr><td colspan="4" style="text-align: center;"><?php echo lang('no_results'); ?>.</td></tr>
                    <?php else: ?>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><a href="#" data-scope="del-user" data-value="<?php echo $user->ID; ?>" title="<?php echo lang('del_user'); ?>" rel="tooltip"><?php echo $user->ID; ?></a></td>
                            <td><a href="<?php echo site_url('acp/user/edit/'.$user->ID.'/'); ?>" title="<?php echo lang('edit_user'); ?>" rel="tooltip"><?php echo $user->login; ?> </a>
                            <td><?php echo ($user->active == 1) ? lang('yes') : lang('no'); ?></td>
                            <td><?php echo date('d/M/Y', $user->register_date); ?></td>
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
