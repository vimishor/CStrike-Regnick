<!DOCTYPE html public "ಠ_ಠ">
<!--[if lt IE 7 ]><html lang="en" class="ie6 ielt7 ielt8 ielt9"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="ie7 ielt8 ielt9"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="ie8 ielt9"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title><?php echo $page_title; ?> | <?php echo $template['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php echo $template['metadata']; ?>

    <!-- css -->
    <link href="<?php echo assets_base(); ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo assets_base(); ?>css/regnick.css" rel="stylesheet">
    <!-- /css -->
    
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo assets_base(); ?>img/favicon.ico">
</head>
<body>
    
    <div class="container">
        <header id="header">
          <div id="header-inner">
            
            <div id="left-area" class="span7">
              <h1 id="logo"><a href="<?php echo site_url(); ?>"><?php echo $site_name; ?></a></h1>
            </div> <!-- /#left-area -->
            
            <div id="right-area" class="span3">
              <div id="user-area" class="span3 offset1">
                
                <?php if ($this->regnick_auth->logged_in()): ?>
                <div class="btn-group">
                  <a class="btn btn-head" href="<?php echo site_url('ucp/dashboard'); ?>"><i class="icon-gravatar icon-white"><img src="<?php echo $this->user_model->getAvatar($this->session->userdata('email')); ?>"></i> <?php echo $this->session->userdata('identity'); ?></a>
                  <a class="btn btn-head dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="<?php echo site_url('ucp/dashboard'); ?>"><i class="icon-cog"></i> <?php echo lang('my_settings'); ?></a></li>
                    
                    <?php if ($this->regnick_auth->isOwner($this->session->userdata('user_id'))): ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('acp/dashboard'); ?>"><i class="icon-wrench"></i> <?php echo lang('administration'); ?></a></li>
                    <?php endif; ?>
                    
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('ucp/logout'); ?>"><i class="icon-lock"></i> <?php echo lang('logout'); ?></a></li>
                  </ul>
                </div><!-- /.btn-group -->
                <?php else: ?>
                <a class="btn btn-head" href="<?php echo site_url('ucp/login'); ?>"><?php echo lang('login'); ?></a>
                <a class="btn btn-head" href="<?php echo site_url('ucp/register'); ?>"><?php echo lang('register'); ?></a>
                <?php endif; ?>
                
              </div><!-- /#user-area -->
            </div><!-- /#right-area -->
            
          </div><!-- /#header-inner -->
        </header>
        
        <!-- notice @todo[1] -->
        <div class="user-notify"></div>
        <?php if ( ($msg = get_userNotice()) OR (form_has_error()) ): ?>
        <div class="alert alert-<?php echo $msg['type']; ?>">
            <a class="close" data-dismiss="alert">x</a>
            <?php echo $msg['body']; ?>
            <?php if (form_has_error()) { echo validation_errors(); } ?>
        </div>
        <?php endif; ?>
        <!-- /notice -->
        
        <div class="row">
            