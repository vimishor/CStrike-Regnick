<div id="sidebar" class="span3">
    <div class="box">
        <form class="form-search">
            <input type="text" class="input-medium search-query" placeholder="search ..." disabled="disabled">
            <button type="submit" class="btn btn-small" disabled="disabled">Go</button>
        </form>
    </div>
    
    <?php if ($this->regnick_auth->logged_in()): ?>
    <div class="box-3d sidemenu">
        <div class="sidebar-title"><?php echo lang('my_profile'); ?></div>
        <ul class="nav nav-tabs nav-stacked">
            <li><a href="<?php echo site_url('ucp/settings'); ?>"><?php echo lang('settings'); ?></a></li>
            <li><a href="<?php echo site_url('ucp/password'); ?>"><?php echo lang('password'); ?></a></li>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if ($this->regnick_auth->isOwner($this->session->userdata('user_id'))): ?>
    <div class="box-3d sidemenu">
        <div class="sidebar-title"><?php echo lang('administration'); ?></div>
        <ul class="nav nav-tabs nav-stacked">
            <li><a href="<?php echo site_url('acp/user/list'); ?>"><?php echo lang('users'); ?></a></li>
            <li><a href="<?php echo site_url('acp/group/list'); ?>"><?php echo lang('groups'); ?></a></li>
            <li><a href="<?php echo site_url('acp/server/list'); ?>"><?php echo lang('servers'); ?></a></li>
            <!--<li class="dropdown"><a class="dropdown-toggle" data-toggle="collapse" data-target="#settings"><?php echo lang('settings'); ?> <b class="caret"></b></a>
                <ul id="settings" class="nav subnav nav-pills nav-stacked collapse out">
                    <li><a href="<?php echo site_url('acp/settings/export'); ?>">&nbsp;&rarr; <?php echo lang('export_users'); ?></a></li>
                </ul>
            </li>-->
        </ul>
    </div>
    <?php endif; ?>

    
</div><!-- /#sidebar -->
