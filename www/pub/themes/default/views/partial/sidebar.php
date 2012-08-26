<div id="sidebar" class="span3">

    <?php if (isset($show_search)): ?>
    <div class="box">
        <?php echo show_search_form($show_search); ?>
    </div>
    <?php endif; ?>
    
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
