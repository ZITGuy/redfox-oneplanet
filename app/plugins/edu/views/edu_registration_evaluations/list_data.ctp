{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registration_evaluations as $edu_registration_evaluation){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration_evaluation['EduRegistrationEvaluation']['id']; ?>",
				"edu_registration":"<?php echo $edu_registration_evaluation['EduRegistration']['name']; ?>",
				"edu_evaluation":"<?php echo $edu_registration_evaluation['EduEvaluation']['id']; ?>",
				"edu_quarter":"<?php echo $edu_registration_evaluation['EduQuarter']['name']; ?>",
				"edu_evaluation_value":"<?php echo $edu_registration_evaluation['EduEvaluationValue']['name']; ?>",
				"created":"<?php echo $edu_registration_evaluation['EduRegistrationEvaluation']['created']; ?>",
				"modified":"<?php echo $edu_registration_evaluation['EduRegistrationEvaluation']['modified']; ?>"			}
<?php $st = true; } ?>		]
}