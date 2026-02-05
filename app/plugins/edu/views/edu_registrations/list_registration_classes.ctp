{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_classes as $edu_class) { foreach($edu_sections as $sec) { if($sec['EduSection']['edu_class_id'] <> $edu_class['EduClass']['id']) continue; if($st) echo ","; ?>			{
				"id":"<?php echo $edu_class['EduClass']['id'] . '_' . $sec['EduSection']['id']; ?>",
				"name":"<?php echo $edu_class['EduClass']['name'] . '-' . $sec['EduSection']['name']; ?>",
				"class_id":"<?php echo $edu_class['EduClass']['id']; ?>"
			}
<?php $st = true; } } ?>		]
}
