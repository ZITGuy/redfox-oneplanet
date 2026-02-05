{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_assessment_types as $edu_assessment_type){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_assessment_type['EduAssessmentType']['id']; ?>",
				"name":"<?php echo $edu_assessment_type['EduAssessmentType']['name']; ?>"			}
<?php $st = true; } ?>		]
}