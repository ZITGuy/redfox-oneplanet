{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_periods as $edu_period){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_period['EduPeriod']['id']; ?>",
				"edu_section":"<?php echo $edu_period['EduSection']['name']; ?>",
				"edu_course_Id":"<?php echo $edu_period['EduPeriod']['edu_course_Id']; ?>",
				"edu_schedule":"<?php echo $edu_period['EduSchedule']['name']; ?>",
				"day":"<?php echo $edu_period['EduPeriod']['day']; ?>",
				"period":"<?php echo $edu_period['EduPeriod']['period']; ?>"			}
<?php $st = true; } ?>		]
}