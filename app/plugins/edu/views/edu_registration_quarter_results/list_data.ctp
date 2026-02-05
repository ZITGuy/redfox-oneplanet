{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registration_quarter_results as $edu_registration_quarter_result){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration_quarter_result['EduRegistrationQuarterResult']['id']; ?>",
				"edu_registration_quarter":"<?php echo $edu_registration_quarter_result['EduRegistrationQuarter']['id']; ?>",
				"edu_course":"<?php echo $edu_registration_quarter_result['EduCourse']['id']; ?>",
				"course_result":"<?php echo $edu_registration_quarter_result['EduRegistrationQuarterResult']['course_result']; ?>",
				"result_indicator":"<?php echo $edu_registration_quarter_result['EduRegistrationQuarterResult']['result_indicator']; ?>",
				"created":"<?php echo $edu_registration_quarter_result['EduRegistrationQuarterResult']['created']; ?>",
				"modified":"<?php echo $edu_registration_quarter_result['EduRegistrationQuarterResult']['modified']; ?>"			}
<?php $st = true; } ?>		]
}