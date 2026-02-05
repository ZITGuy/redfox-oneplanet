{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_evaluation_areas as $edu_evaluation_area){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_evaluation_area['EduEvaluationArea']['id']; ?>",
				"name":"<?php echo $edu_evaluation_area['EduEvaluationArea']['name']; ?>",
				"edu_evaluation_category":"<?php echo $edu_evaluation_area['EduEvaluationCategory']['name']; ?>",
				"created":"<?php echo $edu_evaluation_area['EduEvaluationArea']['created']; ?>",
				"modified":"<?php echo $edu_evaluation_area['EduEvaluationArea']['modified']; ?>"			}
<?php $st = true; } ?>		]
}