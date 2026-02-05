<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       AbayGAPro
 * @subpackage    AbayGAPro.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php __('Login to'); ?> <?php echo Configure::read('app_name'); ?>
        </title>
        <?php
            echo $this->Html->meta('icon');

            echo $this->Html->css('login/reset') . "\n";
		?>
		<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    	<?php
			echo $this->Html->css('login/font-awesome.min', array('rel' => 'stylesheet prefetch')) . "\n";
			echo $this->Html->css('login/style') . "\n";
        ?>
	</head>
    <body>
		<!-- Form Mixin-->
		<!-- Pen Title-->
		<div class="pen-title">
                    <h1><font color=#33b5e5>RED</font><font color=red>FOX</font></h1>
		</div>
		<!-- Form Module-->
		<div class="module form-module">
			<div class="form">
				<h2>Login to your account</h2>
				<form>
					<input type="text" placeholder="Username"/>
					<input type="password" placeholder="Password"/>
					<button>Login</button>
				</form>
			</div>
			<div class="form">
				<h2>Login to your account</h2>
				<center><?php echo $this->Session->flash(); ?></center>
				<form method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>" >
					<input type="text" name="data[User][username]" placeholder="Username"/>
					<input type="password" name="data[User][passwd]" placeholder="Password"/>
					<button>Login</button>
				</form>
			</div>
			<div class="cta"><a href="#">Forgot your password?</a></div>
		</div>
        <?php
        echo $this->Html->script('login/jquery.min') . "\n";
        echo $this->Html->script('login/index') . "\n";

        echo $scripts_for_layout . "\n";
        ?>
    </body>
</html>