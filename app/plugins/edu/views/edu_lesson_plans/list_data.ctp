<?php //pr($edu_lesson_plans); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_lesson_plans as $edu_lesson_plan){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_lesson_plan['EduLessonPlan']['id']; ?>",
            "edu_course":"<?php echo $edu_lesson_plan['EduCourse']['description']; ?>",
            "edu_section":"<?php echo $edu_lesson_plan['EduSection']['name']; ?>",
            "maker":"<?php echo $edu_lesson_plan['Maker']['username']; ?>",
            "checker":"<?php echo $edu_lesson_plan['Checker']['username']; ?>",
            "is_posted":"<?php echo $edu_lesson_plan['EduLessonPlan']['is_posted']? 'Yes': 'No'; ?>",
            "posts":"<?php echo $edu_lesson_plan['EduLessonPlan']['posts']; ?>",
            "status":"<?php echo $edu_lesson_plan['EduLessonPlan']['status']; ?>",
            "reason":"<?php echo $edu_lesson_plan['EduLessonPlan']['reason']; ?>",
            "created":"<?php echo $edu_lesson_plan['EduLessonPlan']['created']; ?>",
            "modified":"<?php echo $edu_lesson_plan['EduLessonPlan']['modified']; ?>"			}
<?php $st = true; } ?>		]
}