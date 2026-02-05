{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_sections as $edu_section){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_section['EduSection']['id']; ?>",
				"name":"<?php echo $edu_section['EduSection']['name'] . ' - ' . $edu_section['EduAcademicYear']['name']; ?>",
				"edu_class":"<?php echo $edu_section['EduClass']['name']; ?>",
				"edu_academic_year":"<?php echo $edu_section['EduAcademicYear']['name']; ?>",
				"created":"<?php echo $edu_section['EduSection']['created']; ?>",
				"modified":"<?php echo $edu_section['EduSection']['modified']; ?>"			}
<?php $st = true; } ?>		]
}