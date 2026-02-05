{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registration_evaluations as $edu_registration_evaluation){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration_evaluation['EduRegistrationEvaluation']['id']; ?>",
				"student_name":"<?php echo $edu_registration_evaluation['EduRegistration']['EduStudent']['name']; ?>",
				"evaluation_value":"<?php echo $edu_registration_evaluation['EduEvaluationValue']['description']; ?>"			}
<?php $st = true; } ?>		]
}