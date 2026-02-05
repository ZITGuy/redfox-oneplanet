<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			RedFox REPORTS - <?php echo Configure::read('company_name'); ?> - <?php echo $this->Session->read('Auth.EduCampus.name'); ?>
		</title>
		
		
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
		<?php
			echo $this->Html->meta('icon');

			echo $this->Html->css('reports/bootstrap/css/bootstrap.min') . "\n";
			echo $this->Html->css('reports/font-awesome/css/font-awesome.min') . "\n";
			echo $this->Html->css('reports/ionicons/css/ionicons.min') . "\n";
			echo $this->Html->css('reports/plugins/datatables/dataTables.bootstrap') . "\n";
			echo $this->Html->css('reports/dist/css/AdminLTE.min') . "\n";
			echo $this->Html->css('reports/dist/css/skins/_all-skins.min') . "\n";
			
		?>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">

			<header class="main-header">
				<!-- Logo -->
				<a href="../../index2.html" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"><b>R</b>R</span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><b>Redfox</b> REPORTS</span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<!-- Control Sidebar Toggle Button -->
							<li>
								<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
							<?php echo $this->Html->image('redfox_logo.png',  array('class' => 'img-circle', 'alt' => 'Redfox Logo')); ?>
						</div>
						<div class="pull-left info">
							<p>RedFox Reports</p>
						</div>
					</div>
					<!-- search form -->
					<form action="#" method="get" class="sidebar-form">
						<div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search...">
							<span class="input-group-btn">
								<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header">REPORTS MENU</li>
						<li>
							<a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'report_index')); ?>">
								<i class="fa fa-dashboard"></i> <span>Dashboard</span>
							</a>
						</li>
						<li class="treeview">
							<a href="#">
								<i class="fa fa-table"></i>
								<span>Students</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_all_students')); ?>"><i class="fa fa-circle-o"></i> All</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_active_students')); ?>"><i class="fa fa-circle-o"></i> Active</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_enrolled_students')); ?>"><i class="fa fa-circle-o"></i> Enrolled</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_promoted_students')); ?>"><i class="fa fa-circle-o"></i> Promoted</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_not_promoted_students')); ?>"><i class="fa fa-circle-o"></i> Not Promoted</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_not_registered_students')); ?>"><i class="fa fa-circle-o"></i> Not Registered</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_due_payment_students')); ?>"><i class="fa fa-circle-o"></i> Due Payment</a></li>
							</ul>
						</li>
						<!--//li class="treeview">
							<a href="#">
								<i class="fa fa-table"></i>
								<span>School</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="#"><i class="fa fa-circle-o"></i> Enrolled Students</a></li>
								<li><a href="#"><i class="fa fa-circle-o"></i> Registered Students</a></li>
								<li><a href="#"><i class="fa fa-circle-o"></i> Current Active Students</a></li>
							</ul>
						</li>
						<li class="treeview">
							<a href="#">
								<i class="fa fa-table"></i> <span>Others</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
								<li>
									<a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
									<ul class="treeview-menu">
										<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
										<li>
											<a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
											<ul class="treeview-menu">
												<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
												<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
							</ul>
						</li //-->
						<li class="header">LABELS</li>
						<li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
						<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
						<li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
					</ul>
				</section>
			<!-- /.sidebar -->
			</aside>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<?php echo $content_for_layout; ?>

			</div><!-- /.content-wrapper -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 2.3.0
				</div>
				<strong>Copyright &copy; 2014-2016 <a href="http://redfox.abdinfotech.com"></a>Redfox</strong> All rights reserved.
			</footer>
		</div><!-- ./wrapper -->
		<?php
			echo $this->Html->script('reports/plugins/jQuery/jQuery-2.1.4.min') . "\n";
			echo $this->Html->script('reports/bootstrap/js/bootstrap.min') . "\n";
			echo $this->Html->script('reports/plugins/datatables/jquery.dataTables.min') . "\n";
			echo $this->Html->script('reports/plugins/datatables/dataTables.bootstrap.min') . "\n";
			echo $this->Html->script('reports/plugins/slimScroll/jquery.slimscroll.min') . "\n";
			echo $this->Html->script('reports/plugins/fastclick/fastclick.min') . "\n";
			echo $this->Html->script('reports/dist/js/app.min') . "\n";
			echo $this->Html->script('reports/dist/js/demo') . "\n";
			
			echo $scripts_for_layout . "\n";
		?>

		<!-- page script -->
		<script>
			$(function () {
				$("#example1").DataTable();
				$('#example2').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": false,
					"ordering": true,
					"info": true,
					"autoWidth": false
				});
			});
		</script>
	</body>
</html>
