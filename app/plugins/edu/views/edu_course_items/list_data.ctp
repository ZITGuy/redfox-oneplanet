{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_course_items as $edu_course_item){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_course_item['EduCourseItem']['id']; ?>",
				"name":"<?php echo $edu_course_item['EduCourseItem']['name']; ?>",
				"description":"<?php echo $edu_course_item['EduCourseItem']['description']; ?>",
				"edu_course":"<?php echo $edu_course_item['EduCourse']['description']; ?>",
				"max_mark":"<?php echo $edu_course_item['EduCourseItem']['max_mark']; ?>",
				"created":"<?php echo $edu_course_item['EduCourseItem']['created']; ?>",
				"modified":"<?php echo $edu_course_item['EduCourseItem']['modified']; ?>"			}
<?php $st = true; } ?>		]
}