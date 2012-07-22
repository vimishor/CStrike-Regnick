<div id="sidebar" class="span3">
    <div class="box">
        <form class="form-search">
            <input type="text" class="input-medium search-query" placeholder="search ..." disabled="disabled">
            <button type="submit" class="btn btn-small" disabled="disabled">Go</button>
        </form>
    </div>
    
    <?php if ($this->navigation->user_can_use('member')): ?>
    <div class="box-3d sidemenu">
        <div class="sidebar-title"><?php echo lang('my_profile'); ?></div>
        <?php echo $this->navigation->show('member'); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($this->navigation->user_can_use('owner')): ?>
    <div class="box-3d sidemenu">
        <div class="sidebar-title"><?php echo lang('administration'); ?></div>
        <?php echo $this->navigation->show('owner'); ?>
    </div>
    <?php endif; ?>
        
</div><!-- /#sidebar -->
