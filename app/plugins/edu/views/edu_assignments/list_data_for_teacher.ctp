{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_assignments as $edu_assignment){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_assignment['EduAssignment']['id']; ?>",
				"edu_teacher":"<?php echo $edu_assignment['EduTeacher']['name']; ?>",
				"edu_course":"<?php echo $edu_assignment['EduCourse']['id']; ?>",
				"edu_section":"<?php echo $edu_assignment['EduSection']['name']; ?>",
				"start_date":"<?php echo $edu_assignment['EduAssignment']['start_date']; ?>",
				"end_date":"<?php echo $edu_assignment['EduAssignment']['end_date']; ?>",
				"created":"<?php echo $edu_assignment['EduAssignment']['created']; ?>",
				"modified":"<?php echo $edu_assignment['EduAssignment']['modified']; ?>"			}
<?php $st = true; } ?>		]
}