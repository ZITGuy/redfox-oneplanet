{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_scales as $edu_scale){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_scale['EduScale']['id']; ?>",
				"min":"<?php echo $edu_scale['EduScale']['min']; ?>",
				"max":"<?php echo $edu_scale['EduScale']['max']; ?>",
				"scale":"<?php echo $edu_scale['EduScale']['scale']; ?>",
				"remark":"<?php echo $edu_scale['EduScale']['remark']; ?>",
				"created":"<?php echo $edu_scale['EduScale']['created']; ?>",
				"modified":"<?php echo $edu_scale['EduScale']['modified']; ?>"			}
<?php $st = true; } ?>		]
}