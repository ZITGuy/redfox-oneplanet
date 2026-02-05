{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($grade_rules as $grade_rule){ if($st) echo ","; ?>			{
				"id":"<?php echo $grade_rule['GradeRule']['id']; ?>",
				"name":"<?php echo $grade_rule['GradeRule']['name']; ?>",
				"type":"<?php echo $grade_rule['GradeRule']['type']; ?>",
				"created_date":"<?php echo $grade_rule['GradeRule']['created_date']; ?>"			}
<?php $st = true; } ?>		]
}