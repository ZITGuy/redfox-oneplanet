{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_schedules as $edu_schedule){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_schedule['EduSchedule']['id']; ?>",
				"name":"<?php echo $edu_schedule['EduSchedule']['name']; ?>",
				"periods":"<?php echo $edu_schedule['EduSchedule']['periods']; ?>",
				"days":"<?php echo $edu_schedule['EduSchedule']['days']; ?>",
				"status":"<?php echo $edu_schedule['EduSchedule']['status']; ?>",
				"created":"<?php echo $edu_schedule['EduSchedule']['created']; ?>"			}
<?php $st = true; } ?>		]
}