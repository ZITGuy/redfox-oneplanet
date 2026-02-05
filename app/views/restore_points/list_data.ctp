{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($restore_points as $restore_point){ if($st) echo ","; ?>			{
				"id":"<?php echo $restore_point['RestorePoint']['id']; ?>",
				"name":"<?php echo $restore_point['RestorePoint']['name']; ?>",
				"created":"<?php echo $restore_point['RestorePoint']['created']; ?>",
				"modified":"<?php echo $restore_point['RestorePoint']['modified']; ?>"			}
<?php $st = true; } ?>		]
}