<?php //print_r($assocs); ?>
{
	success:true,
	results: <?php echo count($assocs); ?>,
	rows: [
<?php $st = false; foreach($assocs as $assoc){ if($st) echo ","; ?>			{
				"id":"<?php echo $assoc['EduCourseTeacherAssociation']['id']; ?>",
				"edu_section_id":"<?php echo $assoc['EduCourseTeacherAssociation']['edu_section_id']; ?>",
				"edu_course_id":"<?php echo $assoc['EduCourseTeacherAssociation']['edu_course_id']; ?>",
				"edu_teacher_id":"<?php echo $assoc['EduCourseTeacherAssociation']['edu_teacher_id']; ?>",
				"course":"<?php echo $assoc['EduCourse']['description']; ?>",
				"teacher":"<?php echo isset($teachers[$assoc['EduTeacher']['id']])? $teachers[$assoc['EduTeacher']['id']]: ''; ?>",
				"created":"<?php echo $assoc['EduCourseTeacherAssociation']['created']; ?>",
				"modified":"<?php echo $assoc['EduCourseTeacherAssociation']['modified']; ?>"			}
<?php $st = true; } ?>		]
}