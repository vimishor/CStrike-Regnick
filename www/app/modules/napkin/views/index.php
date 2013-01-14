    
<div id="content-wrapper" class="span12 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data" style="margin: 0 auto;">
            <div class="alert">
               &nbsp;&nbsp;This process can irreversible change database structure and/or the data contained in this database. Therefore, we recomend to create a 
               database backup before proceeding using the options available bellow. [<a href="<?php echo site_url('update/backup/db'); ?>">backup database</a>]<br>
            </div>
            
            
            <?php if ($this->uri->segment(3)): ?>

                <?php if ( ($this->uri->segment(3) == 'check') AND ($database['total'] == 0) ): ?>
                <div class="alert alert-success">
                    &nbsp;&nbsp;Excellent! No database problems found.
                </div>
                <?php endif; ?>

                <?php if ( ($this->uri->segment(3) == 'repair') AND ($database['fixed'] > 0) ): ?>
                <div class="alert alert-success">
                    &nbsp;&nbsp;<?php echo $database['fixed']; ?> database problems have been successfully repaired. <a href="<?php echo site_url('napkin/database/check/'); ?>">check again</a>
                </div>
                <?php elseif ( ($this->uri->segment(3) == 'repair') AND ($database['fixed'] == 0) ): ?>
                <div class="alert alert-success">
                    &nbsp;&nbsp;No database problems found, so nothing was fixed.
                </div>
                <?php endif; ?>

            <?php endif; ?>


            <div id="acp-widget" class="span6">
                <h3>Database</h3>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td>Orphan accounts</td>
                            <td class="center">
                                <?php if (is_int($database['orphan_accounts'])): ?>
                                    <?php echo $database['orphan_accounts']; ?>
                                <?php else: ?>
                                    Not checked
                                <?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td>Orphan accesses</td>
                            <td class="center">
                                <?php if (is_numeric($database['orphan_accesses'])): ?>
                                    <?php echo $database['orphan_accesses']; ?>
                                <?php else: ?>
                                    Not checked
                                <?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td>Accounts with invalid emails</td>
                            <td class="center">
                                <?php if (is_numeric($database['invalid_emails'])): ?>
                                    <?php echo $database['invalid_emails']; ?>
                                <?php else: ?>
                                    Not checked
                                <?php endif;?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="center">
                                <a href="<?php echo site_url('napkin/database/check'); ?>" class="btn btn-mini" type="button">Check</a>
                                <a href="<?php echo site_url('napkin/database/repair'); ?>" class="btn btn-mini" type="button">Repair</a>
                            </td>
                        </tr>
                  </tbody>
                </table>            
            </div>            
                  
            <div class="clearfix hspace"></div>
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
