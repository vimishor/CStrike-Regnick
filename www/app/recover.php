<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	add_template('overall_header'); 
?>

<body>

<header>
    <div class="title">Recover account</div>
</header>

<div id="content">  
    <?php add_template('overall_menu'); ?>
    <div class="page">
        <form id="global" method="post" action="" autocomplete="off">
            <fieldset>	
                <label for="rec_email">E-mail</label>
                <input type="email" name="rec_email" placeholder="yourname@domain.tld" title="Enter e-mail address used for registration" class="required email">

                <input type="submit" name="recover" class="button" id="recover" value="Recover" />

            </fieldset>
        </form>
    </div>
</div><!-- //end #content -->

<?php add_template('overall_footer'); ?>