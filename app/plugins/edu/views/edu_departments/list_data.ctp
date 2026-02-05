{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($departments as $department){ if($st) echo ","; ?>			{
				"id":"<?php echo $department['EduDepartment']['id']; ?>",
				"name":"<?php echo $department['EduDepartment']['name']; ?>",
				"user":"<?php echo $department['User']['username']; ?>",
				"created":"<?php echo $department['EduDepartment']['created']; ?>"			}
<?php $st = true; } ?>		]
}