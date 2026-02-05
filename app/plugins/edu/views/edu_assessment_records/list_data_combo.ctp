{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_assessment_records as $edu_assessment_record) { if($st) echo ","; ?>			{
				"id":"<?php echo $edu_assessment_record['id']; ?>",
				"mark":"<?php echo $edu_assessment_record['mark']; ?>"		}
<?php $st = true; } ?>		]
}