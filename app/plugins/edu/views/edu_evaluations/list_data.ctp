{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_evaluations as $edu_evaluation){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_evaluation['EduEvaluation']['id']; ?>",
				"edu_class":"<?php echo $edu_evaluation['EduClass']['name']; ?>",
				"edu_evaluation_area":"<?php echo $edu_evaluation['EduEvaluationArea']['name']; ?>",
				"edu_evaluation_category":"<?php echo $categories[$edu_evaluation['EduEvaluationArea']['edu_evaluation_category_id']]; ?>",
				"order_level":"<?php echo $edu_evaluation['EduEvaluation']['order_level']; ?>",
				"created":"<?php echo $edu_evaluation['EduEvaluation']['created']; ?>",
				"modified":"<?php echo $edu_evaluation['EduEvaluation']['modified']; ?>"			}
<?php $st = true; } ?>		]
}