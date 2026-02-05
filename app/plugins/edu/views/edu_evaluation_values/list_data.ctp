{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_evaluation_values as $edu_evaluation_value){ if($st) echo ","; ?>			{
		"id":"<?php echo $edu_evaluation_value['EduEvaluationValue']['id']; ?>",
		"name":"<?php echo $edu_evaluation_value['EduEvaluationValue']['name']; ?>",
		"description":"<?php echo $edu_evaluation_value['EduEvaluationValue']['description']; ?>",
		"evaluation_value_group":"<?php echo $edu_evaluation_value['EduEvaluationValue']['evaluation_value_group']; ?>"			}
<?php $st = true; } ?>		]
}