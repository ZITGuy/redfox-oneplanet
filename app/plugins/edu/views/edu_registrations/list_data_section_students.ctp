{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration['EduStudent']['identity_number']; ?>",
				"name":"<?php echo $edu_registration['EduRegistration']['name']; ?>"			}
<?php $st = true; } ?>		]
}