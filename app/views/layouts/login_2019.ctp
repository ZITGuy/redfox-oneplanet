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
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
            <?php __('Login to'); ?> <?php echo Configure::read('app_name'); ?>
        </title>
        <?php
            echo $this->Html->meta('icon');
		?>
			
		<?php echo $this->Html->css('login_vendor/bootstrap/css/bootstrap.min', array('rel' => 'stylesheet')) . "\n"; ?>
		<?php echo $this->Html->css('login_fonts/font-awesome-4.7.0/css/font-awesome', array('rel' => 'stylesheet')) . "\n"; ?>
		<?php echo $this->Html->css('login_vendor/animate/animate', array('rel' => 'stylesheet')) . "\n"; ?>
		<?php echo $this->Html->css('login_vendor/css-hamburgers/hamburgers.min', array('rel' => 'stylesheet')) . "\n"; ?>
		<?php echo $this->Html->css('login_vendor/select2/select2', array('rel' => 'stylesheet')) . "\n"; ?>
		<?php echo $this->Html->css('login_css/util', array('rel' => 'stylesheet')) . "\n"; ?>
		<?php echo $this->Html->css('login_css/main', array('rel' => 'stylesheet')) . "\n"; ?>
<!--===============================================================================================-->
		<!--===============================================================================================-->	
		<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
		<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
		<link rel="stylesheet" type="text/css" href="css2/util.css">
		<link rel="stylesheet" type="text/css" href="css2/main.css">
	
		<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
	</head>
    <body>
		<div class="limiter">
			<div class="container-login100">
				<div class="wrap-login100">
					<div class="login100-pic js-tilt" data-tilt>
						<?php echo $this->Html->image('login_images/img-01.png', array('alt' => 'RedFox')); ?>
					</div>

					<form class="login100-form validate-form" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>" >
						<span class="login100-form-title">
							&nbsp;
						</span>
						<?php echo $this->Session->flash(); ?>
						<div class="wrap-input100 validate-input" data-validate = "Valid username is required.">
							<input class="input100" type="text" name="data[User][username]" placeholder="Username">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</span>
						</div>

						<div class="wrap-input100 validate-input" data-validate = "Password is required">
							<input class="input100" type="password" name="data[User][passwd]" placeholder="Password">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
						
						<div class="container-login100-form-btn">
							<button class="login100-form-btn">
								Login
							</button>
						</div>

						<div class="text-center p-t-12">
							<span class="txt1">
								Forgot
							</span>
							<a class="txt2" href="#">
								Username / Password?
							</a>
						</div>

						<div class="text-center p-t-136">
							<a class="txt2" href="#">
								&nbsp;
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	
        <?php
        echo $this->Html->script('login_vendor/jquery/jquery-3.2.1.min') . "\n";
        echo $this->Html->script('login_vendor/bootstrap/js/popper') . "\n";
        echo $this->Html->script('login_vendor/bootstrap/js/bootstrap') . "\n";
        echo $this->Html->script('login_vendor/select2/select2.min') . "\n";
        //echo $this->Html->script('login_vendor/tilt/tilt.jquery.min') . "\n";
        
        ?>
		<script >
			/*$('.js-tilt').tilt({
				scale: 1.1
			})*/
		</script>
		<?php
        echo $this->Html->script('login') . "\n";
        
        echo $scripts_for_layout . "\n";
        ?>
    </body>
</html>