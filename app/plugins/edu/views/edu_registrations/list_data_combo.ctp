{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration['EduRegistration']['id']; ?>",
				"name":"<?php echo $edu_registration['EduRegistration']['name']; ?>"			}
<?php $st = true; } ?>		]
}