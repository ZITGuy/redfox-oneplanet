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
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_enrolled_students')); ?>"><i class="fa fa-circle-o"></i> Enrolleds</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_promoted_students')); ?>"><i class="fa fa-circle-o"></i> Promoted</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_not_promoted_students')); ?>"><i class="fa fa-circle-o"></i> Not Promoted</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_not_registered_students')); ?>"><i class="fa fa-circle-o"></i> Not Registered</a></li>
								<li><a href="<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'rpt_due_payment_students')); ?>"><i class="fa fa-circle-o"></i> Due Payment</a></li>
							</ul>
						</li>
						<!--// li class="treeview">
							<a href="#">
								<i class="fa fa-table"></i>
								<span>Financials</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="#"><i class="fa fa-circle-o"></i> Report One</a></li>
								<li><a href="#"><i class="fa fa-circle-o"></i> Report Two</a></li>
							</ul>
						</li>
						<li class="treeview">
							<a href="#">
								<i class="fa fa-table"></i> <span>Others</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="#"><i class="fa fa-circle-o"></i> Report One</a></li>
								<li><a href="#"><i class="fa fa-circle-o"></i> Report Two</a></li>
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
			echo $this->Html->script('reports/plugins/chartjs/Chart.min') . "\n";
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
				
				// Get context with jQuery - using jQuery's .get() method.
				//var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
				// This will get the first returned node in the jQuery collection.
				//var areaChart = new Chart(areaChartCanvas);
				
				var areaChartData = {
					labels: ["2012/13", "2013/14", "2014/15", "2015/16", "2016/17"],
					datasets: [
						{
							name: "Male",
							label: "Male",
							fillColor: "rgba(210, 214, 222, 1)",
							strokeColor: "rgba(210, 214, 222, 1)",
							pointColor: "rgba(210, 214, 222, 1)",
							pointStrokeColor: "#c1c7d1",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(220,220,220,1)",
							data: [65, 59, 80, 81, 56]
						},
						{
							name: "Female",
							label: "Female",
							fillColor: "rgba(60,141,188,0.9)",
							strokeColor: "rgba(60,141,188,0.8)",
							pointColor: "#3b8bba",
							pointStrokeColor: "rgba(60,141,188,1)",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(60,141,188,1)",
							data: [28, 48, 40, 19, 86]
						}
					]
				};
				
				//-------------
				//- BAR CHART -
				//-------------
				var barChartCanvas = $("#barChart").get(0).getContext("2d");
				var barChart = new Chart(barChartCanvas);
				var barChartData = areaChartData;
				barChartData.datasets[1].fillColor = "#00a65a";
				barChartData.datasets[1].strokeColor = "#00a65a";
				barChartData.datasets[1].pointColor = "#00a65a";
				var barChartOptions = {
					//Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
					scaleBeginAtZero: true,
					//Boolean - Whether grid lines are shown across the chart
					scaleShowGridLines: true,
					//String - Colour of the grid lines
					scaleGridLineColor: "rgba(0,0,0,.05)",
					//Number - Width of the grid lines
					scaleGridLineWidth: 1,
					//Boolean - Whether to show horizontal lines (except X axis)
					scaleShowHorizontalLines: true,
					//Boolean - Whether to show vertical lines (except Y axis)
					scaleShowVerticalLines: true,
					//Boolean - If there is a stroke on each bar
					barShowStroke: true,
					//Number - Pixel width of the bar stroke
					barStrokeWidth: 2,
					//Number - Spacing between each of the X value sets
					barValueSpacing: 5,
					//Number - Spacing between data sets within X values
					barDatasetSpacing: 1,
					//String - A legend template
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
					//Boolean - whether to make the chart responsive
					responsive: true,
					maintainAspectRatio: true
				};

				barChartOptions.datasetFill = false;
				barChart.Bar(barChartData, barChartOptions);
			});
			
		</script>
	</body>
</html>
