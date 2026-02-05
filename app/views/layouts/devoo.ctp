<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('Dashboard'); ?>
        </title>

        <?php
            echo $this->Html->meta('icon');
            
            echo $this->Html->css('devoo/plugins/bootstrap/bootstrap') . "\n";
            echo $this->Html->css('devoo/plugins/jquery-ui/jquery-ui.min') . "\n";
            echo $this->Html->css('devoo/font-awesome/4.4.0/css/font-awesome.min') . "\n";
            echo $this->Html->css('devoo/font-awesome/4.4.0/css/ionicons.min') . "\n";
            echo $this->Html->css('devoo/plugins/fancybox/jquery.fancybox') . "\n";
            //echo $this->Html->css('devoo/plugins/fullcalendar/fullcalendar') . "\n";
            echo $this->Html->css('devoo/plugins/xcharts/xcharts.min') . "\n";
            echo $this->Html->css('devoo/plugins/select2/select2') . "\n";
            echo $this->Html->css('devoo/style') . "\n";
        ?>
        <?php
            echo $this->Html->script('devoo/plugins/jquery/jquery-2.1.0.min') . "\n";
            echo $this->Html->script('devoo/plugins/jquery-ui/jquery-ui.min') . "\n";
            echo $this->Html->script('devoo/plugins/bootstrap/bootstrap.min') . "\n";
            echo $this->Html->script('devoo/plugins/justified-gallery/jquery.justifiedgallery.min') . "\n";
        ?>
	</head>
    <body bgcolor="white">
        <div id="main" class="container-fluid">
            <?php echo $content_for_layout; ?>
        </div>
        
	
        <?php
            //echo $this->Html->script('devoo/devoops') . "\n";
        ?>
    </body>
</html>