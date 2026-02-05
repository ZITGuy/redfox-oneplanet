{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_registration['EduRegistration']['id']; ?>",
            "edu_student":"<?php echo strtoupper($edu_registration['EduStudent']['name']); ?>",
            "identity_number":"<?php echo $edu_registration['EduStudent']['identity_number']; ?>",
            "edu_class":"<?php echo $edu_registration['EduClass']['name']; ?>",
            "edu_section":"<?php echo $edu_registration['EduRegistration']['edu_section_id'] == 0? 'Not Sectioned Yet': $edu_registration['EduSection']['name']; ?>",
			"portal_record": "<?php echo $edu_registration['EduStudent']['portal_student_id'] == '-'? '<font color=red>Not Published</font>': '<font color=green>Published</font>'; ?>",
            "created":"<?php echo $edu_registration['EduRegistration']['created']; ?>",
            "modified":"<?php echo $edu_registration['EduRegistration']['modified']; ?>"			}
<?php $st = true; } ?>		]
}