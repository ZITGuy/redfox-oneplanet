{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_communications as $edu_communication){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_communication['EduCommunication']['id']; ?>",
				"student":"<?php echo strtoupper($edu_communication['EduCommunication']['student']); ?>",
				"identity_number":"<?php echo $edu_communication['EduCommunication']['identity_number']; ?>",
				"comment":"<?php echo $edu_communication['EduCommunication']['comment']; ?>" }
<?php $st = true; } ?>		]
}