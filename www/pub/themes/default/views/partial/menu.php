<!-- top navbar -->
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                
                <a class="brand" href="<?php echo $site_url; ?>"><?php echo $site_name; ?></a>
          
                <div class="nav-collapse">
                    <ul class="nav">
                        <li><a href="<?php echo site_url('/'); ?>">Home</a></li>
                        <li><a href="<?php echo site_url('server/list/'); ?>">Servers</a></li>
                    </ul>
                    
                    <ul class="nav pull-right">
                        
                        <?php if ($this->regnick_auth->logged_in()): ?>
                            <li><a href="<?php echo siteLink('ucp/logout/'); ?>">Logout</a></li>
                            <li class="divider-vertical"></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">UCP <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo siteLink('ucp/dashboard/'); ?>">Dashboard</a></li>
                                    <li><a href="<?php echo siteLink('ucp/password/'); ?>">Change password</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo siteLink('ucp/settings/'); ?>">Settings</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo siteLink('ucp/logout/'); ?>">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li><a href="<?php echo siteLink('ucp/login/'); ?>">Login</a></li>
                            <li class="divider-vertical"></li>
                            <li><a href="<?php echo siteLink('ucp/register/'); ?>">Register</a></li>
                        <?php endif;?>
                        
                        <?php if ($this->user_model->isOwner($this->session->getData('userID'))): ?>
                        <li class="divider-vertical"></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">ACP <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo siteLink('acp/user/list/'); ?>">Users</a></li>
                                <li><a href="<?php echo siteLink('acp/server/list/'); ?>">Servers</a></li>
                                <li><a href="<?php echo siteLink('acp/group/list/'); ?>">Groups</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                    </ul>
                    
                </div><!--/.nav-collapse -->
                
            </div> <!-- /.container -->
        </div> <!-- /.navbar-inner -->
    </div>
    <!-- //top navbar -->
    
    