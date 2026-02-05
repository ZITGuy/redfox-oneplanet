{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration['EduRegistration']['id']; ?>",
				"name":"<?php echo $edu_registration['EduRegistration']['name']; ?>",
				"edu_student":"<?php echo $edu_registration['EduStudent']['name']; ?>",
				"edu_class":"<?php echo $edu_registration['EduClass']['name']; ?>",
				"edu_section":"<?php echo $edu_registration['EduRegistration']['edu_section_id'] == 0? 'Not Sectioned Yet': $edu_registration['EduSection']['name']; ?>",
				"created":"<?php echo $edu_registration['EduRegistration']['created']; ?>",
				"modified":"<?php echo $edu_registration['EduRegistration']['modified']; ?>"			}
<?php $st = true; } ?>		]
}