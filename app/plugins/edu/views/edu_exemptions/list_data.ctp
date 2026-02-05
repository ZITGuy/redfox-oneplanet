{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_exemptions as $edu_exemption){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_exemption['EduExemption']['id']; ?>",
				"edu_student":"<?php echo $edu_exemption['EduStudent']['name']; ?>",
				"edu_course":"<?php echo $edu_exemption['EduCourse']['description']; ?>",
				"edu_academic_year":"<?php echo $edu_exemption['EduAcademicYear']['name']; ?>",
				"edu_quarter":"<?php echo ($edu_exemption['EduExemption']['edu_quarter_id'] == 0) ? 'All' : $edu_exemption['EduQuarter']['name']; ?>",
				"created":"<?php echo $edu_exemption['EduExemption']['created']; ?>",
				"modified":"<?php echo $edu_exemption['EduExemption']['modified']; ?>"
			}
<?php $st = true; } ?>		]
}
