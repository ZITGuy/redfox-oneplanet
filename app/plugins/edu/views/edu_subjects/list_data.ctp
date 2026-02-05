{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_subjects as $edu_subject){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_subject['EduSubject']['id']; ?>",
            "name":"<?php echo $edu_subject['EduSubject']['name']; ?>",
            "description":"<?php echo $edu_subject['EduSubject']['description']; ?>",
            "courses":"<?php echo count($edu_subject['EduCourse']); ?>",
            "min_for_pass":"<?php echo $edu_subject['EduSubject']['min_for_pass']; ?>",
            "is_mandatory":"<?php echo $edu_subject['EduSubject']['is_mandatory'] == 1? 'Yes': 'No'; ?>",
            "color":"<font color='<?php echo $edu_subject['EduSubject']['color']; ?>'><?php echo $edu_subject['EduSubject']['color']; ?></font>",
            "created":"<?php echo $edu_subject['EduSubject']['created']; ?>",
            "modified":"<?php echo $edu_subject['EduSubject']['modified']; ?>"			}
<?php $st = true; } ?>		]
}