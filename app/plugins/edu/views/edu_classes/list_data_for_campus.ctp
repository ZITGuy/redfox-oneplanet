{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_classes as $edu_class){ if($st) echo ","; 
		$num_of_students = 0; 
		foreach ($edu_class['EduSection'] as $section) {
		 	if(in_array($section['id'], $current_section_ids)){
		 		$num_of_students += count($section['EduRegistration']);
		 	}
		 } ?>			{
            "id":"<?php echo $edu_class['EduClass']['id']; ?>",
            "name":"<?php echo $edu_class['EduClass']['name']; ?>",
            "sections":"<?php echo count($edu_class['EduSection']); ?>",
            "students":"<?php echo $num_of_students; ?>"			}
<?php $st = true; } ?>		]
}