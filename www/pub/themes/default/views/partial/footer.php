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

<!-- js -->
<script>var RN_URL = "<?php echo base_url(); ?>";</script>

<script src="<?php echo assets_base(); ?>js/jquery/jquery-1.7.1.js"></script> 
<script src="<?php echo assets_base(); ?>js/jquery/plugins/cookie.js"></script>
    
<script src="<?php echo assets_base(); ?>js/bootstrap/transition.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap/alert.js"></script> 
<script src="<?php echo assets_base(); ?>js/bootstrap/modal.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap/dropdown.js"></script>
<script src="<?php echo assets_base(); ?>js/bootstrap/tab.js"></script>

<?php if ($ui_tooltips): ?>
<script>var UI_TOOLTIPS = true;</script>
<script src="<?php echo assets_base(); ?>js/bootstrap/tooltip.js"></script>
<?php endif; ?>

<script src="<?php echo assets_base(); ?>js/bootstrap/button.js"></script>  
<script src="<?php echo assets_base(); ?>js/bootstrap/collapse.js"></script>
<script src="<?php echo assets_base(); ?>js/regnick.js"></script>
</body>
</html>