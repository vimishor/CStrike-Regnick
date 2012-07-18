
<div id="content-wrapper" class="span8 offset2 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            
            <table class="table table-condensed table-bordered">
                <tbody>
                    <tr>
                        <td>Cache directory is writable</td>
                        <td class="center" style="<?php echo ($app_dir_cache == 'Yes') ? 'color: green;' : 'color: red;'; ?>"><?php echo $app_dir_cache; ?></td>
                    </tr>
                    <tr>
                        <td>Logs directory is writable</td>
                        <td class="center" style="<?php echo ($app_dir_logs == 'Yes') ? 'color: green;' : 'color: red;'; ?>"><?php echo $app_dir_logs; ?></td>
                    </tr>
                    <tr>
                        <td>Global config directory is writable</td>
                        <td class="center" style="<?php echo ($app_cfg == 'Yes') ? 'color: green;' : 'color: red;'; ?>"><?php echo $app_cfg; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo ucfirst(ENVIRONMENT);?> config directory is writable</td>
                        <td class="center" style="<?php echo ($app_cfg_env == 'Yes') ? 'color: green;' : 'color: red;'; ?>"><?php echo $app_cfg_env; ?></td>
                    </tr>
                    <!--<tr>
                        <td>Database config file is writable</td>
                        <td class="center" style="<?php echo ($app_file_db == 'Yes') ? 'color: green;' : 'color: red;'; ?>"><?php echo $app_file_db; ?></td>
                    </tr>
                    <tr>
                        <td>Email config file is writable</td>
                        <td class="center" style="<?php echo ($app_file_mail == 'Yes') ? 'color: green;' : 'color: red;'; ?>"><?php echo $app_file_mail; ?></td>
                    </tr>-->
                </tbody>
            </table>
            
            <div class="span4 offset3">
                <?php if ($is_error): ?>
                <a class="btn btn-danger" href="<?php echo site_url('install/step1'); ?>">Test again</a></div>
                <?php else: ?>
                <a class="btn btn-success" href="<?php echo site_url('install/step2'); ?>">Let's do this !</a></div>
                <?php endif; ?>
            </div>
            
            <div class="clearfix hspace"></div>
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
