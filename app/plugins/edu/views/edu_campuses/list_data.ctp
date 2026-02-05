{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_campuses as $edu_campus){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_campus['EduCampus']['id']; ?>",
				"name":"<?php echo $edu_campus['EduCampus']['name']; ?>",
				"address":"<?php echo $edu_campus['EduCampus']['address']; ?>",
				"number_of_students":"<?php echo $edu_campus['EduCampus']['number_of_students']; ?>",
				"number_of_users":"<?php echo $edu_campus['EduCampus']['number_of_users']; ?>",
				"created":"<?php echo $edu_campus['EduCampus']['created']; ?>",
				"modified":"<?php echo $edu_campus['EduCampus']['modified']; ?>"			}
<?php $st = true; } ?>		]
}