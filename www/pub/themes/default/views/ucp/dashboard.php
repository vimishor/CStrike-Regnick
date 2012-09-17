
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <!-- stats -->
            <div id="acp-widget" class="span4">
                <h3>My stats</h3>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td><?php echo lang('member_since'); ?></td>
                            <td class="center"><?php echo $stats['member_since']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('email_address'); ?></td>
                            <td class="center"><?php echo $stats['email']; ?></td>
                        </tr>
                        <tr>
                            <td>Regnick owner</td>
                            <td class="center">
                                <span class="badge badge-<?php echo ($stats['is_owner']) ? 'success' : 'important'; ?>"><?php echo ($stats['is_owner']) ? 'Yes' : 'No'; ?></span>
                            </td>
                        </tr>
                  </tbody>
                </table>            
            </div>
            <!-- //stats -->
            
            <!-- access -->
            <div id="acp-widget" class="span4">
                <h3><?php echo lang('membership'); ?></h3>
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <td class="blue strong center"><?php echo lang('server'); ?></td>
                            <td class="blue strong center"><?php echo lang('group'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($membership)>0): ?>
                            <?php foreach ($membership as $m): ?>
                            <tr>
                                <td class="center"><?php echo $m['address']; ?></td>
                                <td class="center"><?php echo $m['group_name']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="2" class="center"><?php echo lang('user_has_no_access'); ?></td></tr>
                        <?php endif; ?>
                  </tbody>
                </table>            
            </div>
            <!-- //access -->
            
        </div>
        <div class="clearfix hspace"></div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
