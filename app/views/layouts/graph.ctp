<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('Login to'); ?> <?php echo Configure::read('app_name'); ?>
        </title>
        <?php
            echo $this->Html->meta('icon');
			
			echo $this->Html->css('jqplot/jquery.jqplot.min') . "\n";
			echo $this->Html->css('jqplot/examples/examples.min') . "\n";
			echo $this->Html->css('jqplot/examples/syntaxhighlighter/styles/shCoreDefault.min') . "\n";
			echo $this->Html->css('jqplot/examples/syntaxhighlighter/styles/shThemejqPlot.min') . "\n";
			
			echo $this->Html->script('jquery.1.9.min') . "\n";
            
		?>
	</head>
    <body>
		<?php echo $content_for_layout; ?>
		
		
        <?php
		
		echo $this->Html->script('jqplot/jquery.jqplot.min') . "\n";
		echo $this->Html->script('jqplot/examples/syntaxhighlighter/scripts/shCore.min') . "\n";
		echo $this->Html->script('jqplot/examples/syntaxhighlighter/scripts/shBrushJScript.min') . "\n";
		echo $this->Html->script('jqplot/examples/syntaxhighlighter/scripts/shBrushXml.min') . "\n";
		
		echo $this->Html->script('jqplot/plugins/jqplot.cursor.min') . "\n";
		echo $this->Html->script('jqplot/plugins/jqplot.barRenderer.min') . "\n";
		echo $this->Html->script('jqplot/plugins/jqplot.pieRenderer.min') . "\n";
		echo $this->Html->script('jqplot/plugins/jqplot.categoryAxisRenderer.min') . "\n";
		echo $this->Html->script('jqplot/plugins/jqplot.canvasTextRenderer.min') . "\n";
		echo $this->Html->script('jqplot/plugins/jqplot.canvasAxisTickRenderer.min') . "\n";
		echo $this->Html->script('jqplot/plugins/jqplot.pointLabels.min') . "\n";
		
		echo $this->Html->script('jqplot/examples/example') . "\n";
        echo $scripts_for_layout . "\n";
        ?>
    </body>
</html>