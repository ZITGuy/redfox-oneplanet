<?php //pr($edu_corrections); ?>
{
	success: true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach ($edu_corrections as $correction) { if ($st) { echo ","; } ?>  {
				"id":"<?php echo $correction['EduCorrection']['id']; ?>",
				"section":"<?php echo $correction['EduSection']['name'] . ' - ' . $correction['EduSection']['EduClass']['name']; ?>",
				"term":"<?php echo $correction['EduQuarter']['EduAcademicYear']['name'] . ' - ' . $correction['EduQuarter']['short_name']; ?>",
				"course":"<?php echo $correction['EduCourse']['description']; ?>",
				"student":"<?php echo $correction['EduRegistration']['name']; ?>",
				"assessment":"<?php echo $correction['EduCourse']['description'] . ' - ' . $correction['EduAssessment']['EduAssessmentType']['name']; ?>",
				"assessment_out_of":"<?php echo $correction['EduAssessment']['max_value']; ?>",
				"old_value":"<?php echo $correction['EduCorrection']['old_value']; ?>",
				"new_value":"<?php echo $correction['EduCorrection']['new_value']; ?>",
				"status":"<?php echo $correction['EduCorrection']['status']; ?>",
				"reason":"<?php echo $correction['EduCorrection']['reason']; ?>",
				"rejection_reason":"<?php echo $correction['EduCorrection']['rejection_reason']; ?>"		  }
<?php $st = true; } ?>
	]
}