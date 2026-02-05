<?php $ev_groups = array(
	1 => 'Formal (Excellent, Very Good, Good, Fair, Needs Improvement, Poor)', 
	2 => 'Observatory (Needs Improvement, Satisfactory, Excellent)', 
	3 => 'Scaly (A, B, C, D, F)',
	4 => 'NA',
	5 => 'Excellent, Satisfactory, Needs Improvement'
); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_evaluation_categories as $edu_evaluation_category){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_evaluation_category['EduEvaluationCategory']['id']; ?>",
				"name":"<?php echo $edu_evaluation_category['EduEvaluationCategory']['name']; ?>",
				"evaluation_value_group":"<?php echo $ev_groups[$edu_evaluation_category['EduEvaluationCategory']['evaluation_value_group']]; ?>",
				"list_order":"<?php echo $edu_evaluation_category['EduEvaluationCategory']['list_order']; ?>"			}
<?php $st = true; } ?>		]
}