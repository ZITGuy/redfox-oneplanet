{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($lesson_plan_items as $lesson_plan_item){ if($st) echo ","; ?>			{
            "id":"<?php echo $lesson_plan_item['id']; ?>",
            "date":"<?php echo $lesson_plan_item['date']; ?>",
            "period":"<?php echo $lesson_plan_item['period']; ?>",
            "outline":"<?php echo $lesson_plan_item['outline']; ?>",
            "activity":"<?php echo $lesson_plan_item['activity']; ?>",
            "materials_needed":"<?php echo $lesson_plan_item['materials_needed']; ?>"			}
<?php $st = true; } ?>		]
}