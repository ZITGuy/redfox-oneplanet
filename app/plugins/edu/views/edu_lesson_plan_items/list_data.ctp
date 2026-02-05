
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_lesson_plan_items as $edu_lesson_plan_item){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_lesson_plan_item['EduLessonPlanItem']['id']; ?>",
            "edu_lesson_plan":"<?php echo $edu_lesson_plan_item['EduLessonPlan']['id']; ?>",
            "edu_period":"<?php echo $edu_lesson_plan_item['EduPeriod']['period']; ?>",
            "edu_day":"<?php echo date('l M d, Y', strtotime($edu_lesson_plan_item['EduDay']['date'])); ?>",
            "edu_outline":"<?php echo $edu_lesson_plan_item['EduOutline']['name']; ?>",
            "activity":"<?php echo $edu_lesson_plan_item['EduLessonPlanItem']['activity']; ?>",
            "materials_needed":"<?php echo $edu_lesson_plan_item['EduLessonPlanItem']['materials_needed']; ?>",
            "created":"<?php echo $edu_lesson_plan_item['EduLessonPlanItem']['created']; ?>",
            "modified":"<?php echo $edu_lesson_plan_item['EduLessonPlanItem']['modified']; ?>"			}
<?php $st = true; } ?>		]
}