{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($countries as $country){ if($st) echo ","; ?>			{
				"id":"<?php echo $country['Country']['id']; ?>",
				"name":"<?php echo $country['Country']['name']; ?>",
				"code":"<?php echo $country['Country']['code']; ?>",
				"currency":"<?php echo $country['Country']['currency']; ?>",
				"nationality":"<?php echo $country['Country']['nationality']; ?>",
				"language":"<?php echo $country['Country']['language']; ?>"			}
<?php $st = true; } ?>		]
}