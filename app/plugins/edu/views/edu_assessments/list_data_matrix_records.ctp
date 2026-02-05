{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach ($assessment_records as $assessment_record) { if ($st) { echo ",";} ?> {
    <?php foreach ($assessment_record['EduAssessmentRecord'] as $key => $value) { ?>
		"<?php echo $key; ?>":"<?php echo $value; ?>",
    <?php } ?>
	}
<?php $st = true;
    } ?>]
}