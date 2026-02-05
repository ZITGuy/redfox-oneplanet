{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($grade_rule_values as $grade_rule_value){ if($st) echo ","; ?>			{
				"id":"<?php echo $grade_rule_value['GradeRuleValue']['id']; ?>",
				"min":"<?php echo $grade_rule_value['GradeRuleValue']['min']; ?>",
				"max":"<?php echo $grade_rule_value['GradeRuleValue']['max']; ?>",
				"code":"<?php echo $grade_rule_value['GradeRuleValue']['code']; ?>",
				"grade_rule":"<?php echo $grade_rule_value['GradeRule']['name']; ?>"			}
<?php $st = true; } ?>		]
}