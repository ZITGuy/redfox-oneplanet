{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registration_quarters as $edu_registration_quarter){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration_quarter['EduRegistrationQuarter']['id']; ?>",
				"edu_registration":"<?php echo $edu_registration_quarter['EduRegistration']['name']; ?>",
				"edu_quarter":"<?php echo $edu_registration_quarter['EduQuarter']['name']; ?>",
				"quarter_average":"<?php echo $edu_registration_quarter['EduRegistrationQuarter']['quarter_average']; ?>",
				"quarter_rank":"<?php echo $edu_registration_quarter['EduRegistrationQuarter']['quarter_rank']; ?>",
				"created":"<?php echo $edu_registration_quarter['EduRegistrationQuarter']['created']; ?>",
				"modified":"<?php echo $edu_registration_quarter['EduRegistrationQuarter']['modified']; ?>"			}
<?php $st = true; } ?>		]
}