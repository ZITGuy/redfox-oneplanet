{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($assessments as $assessment){ if($st) echo ","; ?>			{
			<?php $deletable = 1; foreach($assessment['EduAssessmentRecord'] as $rec) { if($rec['mark'] > 0) { $deletable = 0; break;} } ?>
				"id":"<?php echo $assessment['EduAssessment']['id']; ?>",
				"assessment_type":"<?php echo $assessment['EduAssessmentType']['name']; ?>",
				"teacher":"<?php echo $assessment['EduTeacher']['identity_number']; ?>",
				"section":"<?php echo $assessment['EduSection']['name']; ?>",
				"max_value":"<?php echo $assessment['EduAssessment']['max_value']; ?>",
				"date":"<?php echo $assessment['EduAssessment']['date']; ?>",
				"status":"<?php echo $assessment['EduAssessment']['status']=='S'? 'Saved': 'Submitted'; ?>",
				"detail":"<?php echo $assessment['EduAssessment']['detail']; ?>",
				"user_id":"<?php echo $assessment['EduAssessment']['user_id']; ?>",
				"deletable":"<?php echo $deletable; ?>",
				"created_by":"<?php echo $assessment['User']['username']; ?>",
				"quarter":"<?php echo $assessment['EduQuarter']['name']; ?>",
				"subject":"<?php echo $assessment['EduCourse']['id']; ?>"			}
<?php $st = true; } ?>		]
}