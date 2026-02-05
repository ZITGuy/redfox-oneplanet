{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach ($courses as $course) { if ($st) { echo ",";} ?>			{
				"id":"<?php echo $course['id']; ?>",
				"course":"<?php echo $course['EduCourse']['description']; ?>",
				"edu_course_id":"<?php echo $course['EduCourse']['id']; ?>",
				"teacher":"<?php echo str_replace('.', ' ', strtoupper(@$course['EduTeacher']['User']['username'])); ?>",
				"section":"<?php echo $course['EduClass']['name'] . ' - ' . $course['EduSection']['name']; ?>",
				"edu_section_id":"<?php echo $course['EduSection']['id']; ?>",
				"status":"<?php echo $course['status'] ?>",
				"quarter":"<?php echo $course['EduQuarter']['name']; ?>"			}
<?php $st = true;
   } ?>		]
}
