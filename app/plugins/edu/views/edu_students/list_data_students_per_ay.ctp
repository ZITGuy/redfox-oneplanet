{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_students as $edu_student){ if($st) {echo ",";} ?>			{
                "id":"<?php echo $edu_student['EduStudent']['id']; ?>",
                "name":"<?php echo strtoupper($edu_student['EduStudent']['name']); ?>",
                "identity_number":"<?php echo $edu_student['EduStudent']['identity_number']; ?>",
                "registration_date":"<?php echo $edu_student['EduStudent']['registration_date']; ?>",
                "edu_parent":"<?php echo $edu_student['EduParent']['authorized_person']; ?>",
                "status":"<?php echo $edu_student['Status']['name']; ?>",
                "created":"<?php echo $edu_student['EduStudent']['created']; ?>",
                "modified":"<?php echo $edu_student['EduStudent']['modified']; ?>"			}
<?php $st = true; } ?>		]
}