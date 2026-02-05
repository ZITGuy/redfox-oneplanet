{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_days as $edu_day){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_day['EduDay']['id']; ?>",
				"date":"<?php echo $edu_day['EduDay']['date']; ?>",
				"week_day":"<?php echo $edu_day['EduDay']['week_day']; ?>",
				"edu_quarter":"<?php echo $edu_day['EduQuarter']['name']; ?>",
				"created":"<?php echo $edu_day['EduDay']['created']; ?>",
				"modified":"<?php echo $edu_day['EduDay']['modified']; ?>"			}
<?php $st = true; } ?>		]
}