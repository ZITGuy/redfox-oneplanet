{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($assessments as $assessment){ if($st) echo ","; ?>			{
				"id":"<?php echo $assessment['EduAssessment']['id'] . '-' . (count($assessment['EduAssessmentRecord']) > 0? 'SAVED': 'CREATED'); ?>",
				"name":"<?php echo $assessment['EduAssessmentType']['name'] . ' (out of ' . $assessment['EduAssessment']['max_value'] . ')'; ?>"			}
<?php $st = true; } ?>		]
}