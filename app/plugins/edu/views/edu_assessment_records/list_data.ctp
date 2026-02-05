{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($assessment_records as $assessment_record){ if($st) echo ","; ?>			{
				"id":"<?php echo $assessment_record['id']; ?>",
				"student_id":"<?php echo $assessment_record['student_id']; ?>",
				"student":"<?php echo $assessment_record['first_name']; ?>",
				"assessment":"<?php echo $assessment_record['assessment_id']; ?>",
				"rank":"<?php echo $assessment_record['rank']; ?>"			}
<?php $st = true; } ?>		]
}