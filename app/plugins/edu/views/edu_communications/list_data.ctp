{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_communications as $edu_communication){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_communication['EduCommunication']['id']; ?>",
				"edu_student":"<?php echo $edu_communication['EduStudent']['name']; ?>",
				"edu_section":"<?php echo $edu_communication['EduSection']['name']; ?>",
				"post_date":"<?php echo $edu_communication['EduCommunication']['post_date']; ?>",
				"teacher_comment":"<?php echo $edu_communication['EduCommunication']['teacher_comment']; ?>",
				"parent_comment":"<?php echo $edu_communication['EduCommunication']['parent_comment']; ?>",
				"user":"<?php echo $edu_communication['User']['username']; ?>",
				"created":"<?php echo $edu_communication['EduCommunication']['created']; ?>",
				"modified":"<?php echo $edu_communication['EduCommunication']['modified']; ?>"			}
<?php $st = true; } ?>		]
}