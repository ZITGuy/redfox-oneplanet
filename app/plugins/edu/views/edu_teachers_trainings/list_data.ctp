<?php //pr($edu_teacher); // $edu_teacher['User']['Person']['first_name'] . ' ' . $edu_teacher['User']['Person']['middle_name'] . ' (' . ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_teachers_trainings as $edu_teachers_training){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_teachers_training['EduTeachersTraining']['id']; ?>",
				"edu_teacher":"<?php echo $edu_teacher['EduTeacher']['identity_number']; ?>",
				"edu_training":"<?php echo $edu_teachers_training['EduTraining']['name']; ?>",
				"from_date":"<?php echo $edu_teachers_training['EduTeachersTraining']['from_date']; ?>",
				"to_date":"<?php echo $edu_teachers_training['EduTeachersTraining']['to_date']; ?>",
				"trainer":"<?php echo $edu_teachers_training['EduTeachersTraining']['trainer']; ?>",
				"remark":"<?php echo $edu_teachers_training['EduTeachersTraining']['remark']; ?>",
				"deleted":"<?php echo $edu_teachers_training['EduTeachersTraining']['deleted']; ?>",
				"created":"<?php echo $edu_teachers_training['EduTeachersTraining']['created']; ?>",
				"modified":"<?php echo $edu_teachers_training['EduTeachersTraining']['modified']; ?>"			}
<?php $st = true; } ?>		]
}