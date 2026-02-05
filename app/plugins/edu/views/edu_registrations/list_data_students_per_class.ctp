{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_students as $edu_student){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_student['EduStudent']['id']; ?>",
            "student_name":"<?php echo $edu_student['EduStudent']['name']; ?>",
            "identity_number":"<?php echo $edu_student['EduStudent']['identity_number']; ?>",
            "edu_class":"<?php echo (!empty($edu_class)? $edu_class['EduClass']['name']: 'NA'); ?>",
            "edu_section":"NA",
            "created":"<?php echo $edu_student['EduStudent']['created']; ?>",
            "modified":"<?php echo $edu_student['EduStudent']['modified']; ?>"			}
<?php $st = true; } ?>		]
}
