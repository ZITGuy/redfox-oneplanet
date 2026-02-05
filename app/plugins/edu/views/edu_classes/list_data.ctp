<?php $grading_types = array('N' => 'Numeric', 'A' => 'GPA', 'G' => 'Observation'); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_classes as $edu_class){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_class['EduClass']['id']; ?>",
            "name":"<?php echo $edu_class['EduClass']['name']; ?>",
            "cvalue":"<?php echo $edu_class['EduClass']['cvalue']; ?>",
            "min_for_promotion":"<?php echo $edu_class['EduClass']['min_for_promotion']; ?> %",
            "courses":"<?php echo count($edu_class['EduCourse']); ?>",
            "payment_schedules":"<?php echo count($edu_class['EduPaymentSchedule']); ?>",
            "sections":"<?php echo count($edu_class['EduSection']); ?>",
            "class_level":"<?php echo $edu_class['EduClassLevel']['name']; ?>",
            "uni_teacher":"<?php echo $edu_class['EduClass']['uni_teacher']? 'True': 'False'; ?>",
            "grading_type":"<?php echo $grading_types[$edu_class['EduClass']['grading_type']]; ?>",
            "course_item_enabled":"<?php echo $edu_class['EduClass']['course_item_enabled']? '<font color=green>True</font>': 'False'; ?>",
            "created":"<?php echo $edu_class['EduClass']['created']; ?>",
            "modified":"<?php echo $edu_class['EduClass']['modified']; ?>"			}
<?php $st = true; } ?>		]
}