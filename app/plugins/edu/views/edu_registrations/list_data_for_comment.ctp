{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_registration['rqr_id']; ?>",
            "edu_student":"<?php echo $edu_registration['EduStudent']['name']; ?>",
            "identity_number":"<?php echo $edu_registration['EduStudent']['identity_number']; ?>",
            "course_result":"<?php echo $edu_registration['course_result']; ?>",
            "teacher_comment":"<?php echo $edu_registration['teacher_comment']; ?>"			}
<?php $st = true; } ?>		]
}