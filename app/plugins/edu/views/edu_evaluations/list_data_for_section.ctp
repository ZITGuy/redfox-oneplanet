{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_evaluations as $edu_evaluation){ if($st) echo ","; ?>  {
				"id":"<?php echo $edu_evaluation['EduEvaluation']['id']; ?>",
				"name":"<?php echo $edu_evaluation['EduEvaluationArea']['name'] . (isset($evaluateds[$edu_evaluation['EduEvaluation']['id']])? ' [Evaluated]': ''); ?>"   }
<?php $st = true; } ?>		]
}