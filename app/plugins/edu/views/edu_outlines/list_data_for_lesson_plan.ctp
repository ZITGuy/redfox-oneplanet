{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_outlines as $edu_outline){ if($st) echo ","; ?>			{
                "id":"<?php echo $edu_outline['EduOutline']['id']; ?>",
                "name":"<?php echo $edu_outline['EduOutline']['list_order'] . '. ' . $edu_outline['EduOutline']['name']; ?>"			}
<?php $st = true; } ?>		]
}