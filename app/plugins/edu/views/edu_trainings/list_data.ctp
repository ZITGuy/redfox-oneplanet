{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_trainings as $edu_training){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_training['EduTraining']['id']; ?>",
				"name":"<?php echo $edu_training['EduTraining']['name']; ?>",
				"category":"<?php echo $edu_training['EduTrainingCategory']['name']; ?>",
				"deleted":"<?php echo $edu_training['EduTraining']['deleted']; ?>",
				"created":"<?php echo $edu_training['EduTraining']['created']; ?>",
				"modified":"<?php echo $edu_training['EduTraining']['modified']; ?>"			}
<?php $st = true; } ?>		]
}