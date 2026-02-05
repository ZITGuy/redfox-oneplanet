{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($students as $edu_student){ if($st) echo ","; ?>			{
            "student_id":"<?php echo $edu_student['EduStudent']['id']; ?>",
            "full_name":"<?php echo $edu_student['EduStudent']['name']; ?>",
            "grade":"<?php echo $edu_student['EduStudent']['name']; ?>",
            "status":"<?php echo $edu_student['EduStudent']['status']; ?>",
            "photo":"<?php echo $edu_student['EduStudent']['photo_file_name']; ?>"			}
<?php $st = true; } ?>		]
}