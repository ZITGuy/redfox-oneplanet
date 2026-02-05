<?php //pr($assessment_records); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($assessment_records as $assessment_record){ if($st) echo ","; ?>			{
		"id":"<?php echo $assessment_record['EduAssessmentRecord']['id']; ?>",
		"student":"<?php echo $assessment_record['EduAssessmentRecord']['student']; ?>",
		"identity_number":"<?php echo $assessment_record['EduAssessmentRecord']['identity_number']; ?>",
		"rvalue":"<?php echo ($assessment_record['EduAssessmentRecord']['rvalue'] == -1? "": $assessment_record['EduAssessmentRecord']['rvalue']); ?>",
		"max_value":"<?php echo $assessment_record['EduAssessmentRecord']['max_value']; ?>"   }
<?php $st = true; } ?>		]
}