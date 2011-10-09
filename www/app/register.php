<?php 
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    add_template('overall_header'); 
?>

<body>

<header>
    <div class="title">Register nickname</div>
</header>

<div id="content">
    <?php add_template('overall_menu'); ?>
    <div class="page">    
        <form id="global" method="post" action="" autocomplete="off">
            <fieldset>	
                <label for="nickname">Nickname</label>
                <input type="text" name="nickname" id="nickname" placeholder="Nickname / SteamID / IP" title="Enter your nickname" class="required">
                <span class="nickname result hide"></span>
                
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="" title="Enter your password" class="required">
                <span class="password result hide"></span>
                
                <label for="check_password">Repeat password</label>
                <input type="password" name="check_password" id="check_password" placeholder="" title="Repeat your password" class="required">
                <span class="check_password result hide"></span>
                
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="yourname@domain.tld" title="Enter your e-mail address" class="required email">
                <span class="email result hide"></span>

                <label for="server">Server</label>
                <select name="server" id="server" title="Select the server you want your nickname registred" class="">
                    <?php foreach ($config['servers'] as $server): ?>
                        <option value="<?php echo $server['id']; ?>"><?php echo $server['address']; ?></option>
                    <?php endforeach; ?>
                </select>
                                
                <input type="submit" name="register" class="button" id="register" value="Register" />

            </fieldset>
        </form>
        
    </div>
</div><!-- //end #content -->

<?php add_template('overall_footer'); ?>