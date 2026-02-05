{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($text_messages as $text_message){ if($st) echo ","; ?>			{
		"id":"<?php echo $text_message['TextMessage']['id']; ?>",
		"receiver":"<?php echo $text_message['TextMessage']['receiver']; ?>",
		"message":"<?php echo $text_message['TextMessage']['message']; ?>",
		"status":"<?php echo $text_message['TextMessage']['status'] == 'N'? 'Not Sent': 'Sent'; ?>",
		"remark":"<?php echo $text_message['TextMessage']['remark'] == ''? 'No Remark': $text_message['TextMessage']['remark']; ?>",
		"created":"<?php echo $text_message['TextMessage']['created']; ?>",
		"modified":"<?php echo $text_message['TextMessage']['modified']; ?>"			}
<?php $st = true; } ?>		]
}