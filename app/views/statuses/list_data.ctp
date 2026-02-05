{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($statuses as $status){ if($st) echo ","; ?>			{
				"id":"<?php echo $status['Status']['id']; ?>",
				"name":"<?php echo $status['Status']['name']; ?>",
				"tables":"<?php echo $status['Status']['tables']; ?>",
				"remark":"<?php echo $status['Status']['remark']; ?>"			}
<?php $st = true; } ?>		]
}