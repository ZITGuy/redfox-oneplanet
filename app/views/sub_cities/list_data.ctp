{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($sub_cities as $sub_city){ if($st) echo ","; ?>			{
				"id":"<?php echo $sub_city['SubCity']['id']; ?>",
				"name":"<?php echo $sub_city['SubCity']['name']; ?>"			}
<?php $st = true; } ?>		]
}