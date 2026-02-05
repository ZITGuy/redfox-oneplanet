{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_training_categories as $edu_training_category){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_training_category['EduTrainingCategory']['id']; ?>",
				"name":"<?php echo $edu_training_category['EduTrainingCategory']['name']; ?>",
				"deleted":"<?php echo $edu_training_category['EduTrainingCategory']['deleted']; ?>",
				"created":"<?php echo $edu_training_category['EduTrainingCategory']['created']; ?>",
				"modified":"<?php echo $edu_training_category['EduTrainingCategory']['modified']; ?>"			}
<?php $st = true; } ?>		]
}