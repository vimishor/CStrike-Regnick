    
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
                        <th><?php echo lang('user'); ?></th>
                        <th style="width: 100px;"><?php echo lang('access'); ?></th>
                    </tr>
                </thead>
                    
                <tbody>
                    <?php if (!$users): ?>
                    <tr><td colspan="3" style="text-align: center;"><?php echo lang('no_results'); ?>.</td></tr>
                    
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['login']; ?></td>
                            <td><?php echo $user['name']; ?></td>
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
