{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_class_levels as $edu_class_level){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_class_level['EduClassLevel']['id']; ?>",
				"name":"<?php echo $edu_class_level['EduClassLevel']['name']; ?>",
				"remark":"<?php echo $edu_class_level['EduClassLevel']['remark']; ?>"			}
<?php $st = true; } ?>		]
}