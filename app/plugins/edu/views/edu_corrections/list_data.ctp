<?php pr($edu_corrections); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach ($edu_corrections as $correction) { if ($st) { echo ","; } ?>			{
				"id":"<?php echo $correction['EduCorrection']['id']; ?>",
				"name":"<?php echo $correction['EduCorrection']['name']; ?>",
				"student":"<?php echo $correction['EduRegistration']['name']; ?>",
				"assessment":"<?php echo $correction['EduAssessment']['name']; ?>",
				"status":"<?php echo $correction['EduCorrection']['status']; ?>",
				"reason":"<?php echo $correction['EduCorrection']['reason']; ?>"			}
<?php $st = true;
	} ?>
	]
}