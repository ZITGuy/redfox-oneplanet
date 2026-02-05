{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_outlines as $edu_outline){ if($st) echo ","; ?>			{
                "id":"<?php echo $edu_outline['EduOutline']['id']; ?>",
                "name":"<?php echo $edu_outline['EduOutline']['name']; ?>",
                "edu_course":"<?php echo $edu_outline['EduCourse']['description']; ?>",
                "list_order":"<?php echo $edu_outline['EduOutline']['list_order']; ?>",
                "created":"<?php echo $edu_outline['EduOutline']['created']; ?>",
                "modified":"<?php echo $edu_outline['EduOutline']['modified']; ?>"			}
<?php $st = true; } ?>		]
}