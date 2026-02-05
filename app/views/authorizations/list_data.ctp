{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($authorizations as $authorization){ if($st) echo ","; ?>			{
				"id":"<?php echo $authorization['Authorization']['id']; ?>",
				"name":"<?php echo $authorization['Authorization']['name']; ?>",
				"command_script":"<?php echo $authorization['Authorization']['command_script']; ?>",
				"maker":"<?php echo $authorization['Maker']['username']; ?>",
				"authorizer":"<?php echo $authorization['Authorizer']['username']; ?>",
				"status":"<?php echo $authorization['Authorization']['status']; ?>",
				"created":"<?php echo $authorization['Authorization']['created']; ?>",
				"modified":"<?php echo $authorization['Authorization']['modified']; ?>"			}
<?php $st = true; } ?>		]
}