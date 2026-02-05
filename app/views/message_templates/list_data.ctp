{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($message_templates as $message_template){ if($st) echo ","; ?>			{
				"id":"<?php echo $message_template['MessageTemplate']['id']; ?>",
				"name":"<?php echo $message_template['MessageTemplate']['name']; ?>",
				"body":"<?php echo $message_template['MessageTemplate']['body']; ?>",
				"default_body":"<?php echo $message_template['MessageTemplate']['default_body']; ?>",
				"placeholders":"<?php echo $message_template['MessageTemplate']['placeholders']; ?>",
				"created":"<?php echo $message_template['MessageTemplate']['created']; ?>",
				"modified":"<?php echo $message_template['MessageTemplate']['modified']; ?>"			}
<?php $st = true; } ?>		]
}