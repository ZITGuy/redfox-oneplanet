{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($assessments as $assessment){ if($st) echo ","; ?>			{
				"id":"<?php echo $assessment['EduAssessment']['id']; ?>",
				"name":"<?php echo $assessment['EduAssessmentType']['name'] . ' (out of ' . $assessment['EduAssessment']['max_value'] . ')'; ?>",
				"teacher":"<?php echo $assessment['EduTeacher']['identity_number']; ?>",
				"section":"<?php echo $assessment['EduSection']['name']; ?>",
				"max_value":"<?php echo $assessment['EduAssessment']['max_value']; ?>"			}
<?php $st = true; } ?>		]
}