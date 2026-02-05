{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($registrations as $registration){ if($st) echo ","; ?>			{
		"id":"<?php echo $registration['EduRegistration']['id']; ?>",
		"name":"<?php echo $registration['EduStudent']['identity_number'] . ' - ' . $registration['EduStudent']['name']; ?>",
	}
<?php $st = true; } ?>		]
}