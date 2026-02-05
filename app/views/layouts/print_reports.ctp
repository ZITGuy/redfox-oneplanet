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

		<?php echo $content_for_layout; ?>

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
	</body>
</html>
