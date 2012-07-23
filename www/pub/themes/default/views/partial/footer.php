    </div><!-- /.row -->
</div><!-- /.container -->

<div class="clearfix hspace"></div>

<footer id="footer" class="container">
    <div class="row">
        <p class="span4 pull-right">
            Powered by <a rel="tooltip" href="http://www.gentle.ro/proiecte/cstrike-regnick/" title="by Gentle Software Solutions">CStrike-Regnick</a>
        </p>
        
        <p class="span4 offset2">
            Copyright &copy; <?php echo date('Y'); ?> <a rel="tooltip" title="<?php echo $site_name; ?>" href="<?php echo site_url(); ?>"><?php echo $site_name; ?></a>
        </p>
    </div>
</footer>

<script>var RN_URL = "<?php echo base_url(); ?>";</script>
<script src="<?php echo assets_base(); ?>js/jquery/jquery-1.7.1.min.js"></script> 

<!-- js -->
<script src="<?php echo assets_base(); ?>js/jquery/plugins/cookie.min.js"></script>
    
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/transition.min.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/alert.min.js"></script> 
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/modal.min.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/dropdown.min.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/tab.min.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/button.min.js"></script>  
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/collapse.min.js"></script>
<!-- /js -->

<?php if ($ui_tooltips): ?>
<script>var UI_TOOLTIPS = true;</script>
<script src="<?php echo assets_base(); ?>js/bootstrap-2.0.3/tooltip.min.js"></script>
<?php endif; ?>

<script src="<?php echo assets_base(); ?>js/regnick.js"></script>
</body>
</html>