
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
                <h3>Stats</h3>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td><?php echo lang('application_version'); ?></td>
                            <td class="center"><?php echo $stats['app_version']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('database_version'); ?></td>
                            <td class="center"><?php echo $stats['db_version']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('registred_users'); ?></td>
                            <td class="center"><?php echo $stats['registred_users']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('registred_servers'); ?></td>
                            <td class="center"><?php echo $stats['registred_servers']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('mysql_version'); ?></td>
                            <td class="center"><?php echo $stats['mysql_version']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('php_version'); ?></td>
                            <td class="center"><?php echo $stats['php_version']; ?></td>
                        </tr>
                  </tbody>
                </table>            
            </div>
            <!-- //stats -->
            
            <!-- security -->
            <div id="acp-widget" class="span4">
                <h3>Security</h3>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td><a href="http://docs.gentle.ro/cstrike-regnick/general/configuration/#encryption-key" rel="external">Unique encryption key</a></td>
                            <td class="center">
                                <span class="badge badge-<?php echo ($security['key']) ? 'success' : 'important'; ?>"><?php echo ($security['key']) ? 'OK' : 'Not set'; ?></span> 
                            </td>
                        </tr>
                        <tr>
                            <td><a href="http://docs.gentle.ro/cstrike-regnick/general/configuration/#xss-filter" rel="external">XSS protection</a></td>
                            <td class="center">
                                <span class="badge badge-<?php echo ($security['xss']) ? 'success' : 'warning'; ?>"><?php echo ($security['xss']) ? 'OK' : 'important'; ?></span> 
                            </td>
                        </tr>
                        <tr>
                            <td><a href="http://docs.gentle.ro/cstrike-regnick/general/configuration/#csrf" rel="external">CSRF</a></a></td>
                            <td class="center">
                                <span class="badge badge-<?php echo ($security['csrf']) ? 'success' : 'warning'; ?>"><?php echo ($security['csrf']) ? 'OK' : 'Warning'; ?></span> 
                            </td>
                        </tr>
                  </tbody>
                </table>            
            </div>
            <!-- //security -->
            
            <!-- speed -->          
            <div id="acp-widget" class="span4">
                <h3>Optimization &amp; speed</h3>
                <table class="table table-condensed table-bordered">
                    <tbody>
                        <tr>
                            <td><a href="http://docs.gentle.ro/cstrike-regnick/general/configuration/#logs" rel="external">Debug logs are active</a></td>
                            <td class="center">
                                <span class="badge badge-<?php echo ($speed['logs']) ? 'success' : 'warning'; ?>"><?php echo ($speed['logs']) ? 'No' : 'Yes'; ?></span> 
                            </td>
                        </tr>
                        <tr>
                            <td><a href="http://docs.gentle.ro/cstrike-regnick/general/configuration/#output-compression" rel="external">Output compression</a></td>
                            <td class="center">
                                <span class="badge badge-<?php echo ($speed['gzip_output']) ? 'success' : 'warning'; ?>"><?php echo ($speed['gzip_output']) ? 'OK' : 'Disabled'; ?></span> 
                            </td>
                        </tr>
                  </tbody>
                </table>            
            </div>
            <!-- //speed -->
            
        </div>
        <div class="clearfix hspace"><br></div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
