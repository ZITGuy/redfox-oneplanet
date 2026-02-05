{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($records as $record){ if($st) echo ","; ?>			{
		<?php foreach($record as $key => $value) { ?>
		"<?php echo $key; ?>":"<?php echo $value; ?>",
		<?php } ?>   }
<?php $st = true; } ?>		
   ]
}