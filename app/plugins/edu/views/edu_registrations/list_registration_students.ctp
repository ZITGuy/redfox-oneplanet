{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_students as $edu_student){ if($st) echo ","; ?>			{
		"id":"<?php echo $edu_student['EduStudent']['id']; ?>",
		"name":"<?php echo $edu_student['EduStudent']['identity_number'] . ' - ' . 
                        $edu_student['EduStudent']['name'] . ' - (Grade ' . $edu_student['EduStudent']['current_class_name'] . ')'
                        //(isset($edu_student['EduStudent']['EduRegistration'])? 
                        //$edu_student['EduStudent']['EduRegistration'][count($edu_student['EduStudent']['EduRegistration'])-1]['edu_campus_id']: '1'); 
                  ?>",
			}
<?php $st = true; } ?>		]
}
