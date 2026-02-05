
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($permissions as $permission){ if($st) echo ","; ?>			
        {
				"id":"<?php echo $permission['Permission']['id']; ?>",
				"name":"<?php echo $permission['Permission']['name']; ?>",
				"description":"<?php echo $permission['Permission']['description']; ?>",
				"task":"<?php echo $permission['Task']['name']; ?>"
        }
<?php $st = true; } ?>		
    ]
}