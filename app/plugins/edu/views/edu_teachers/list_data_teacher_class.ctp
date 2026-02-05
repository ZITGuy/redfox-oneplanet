{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($classes as $class){ if($st) echo ","; ?>			{
        "id":"<?php echo $class['id']; ?>",
        "name":"Grade <?php echo $class['name']; ?>",
		"remark":""   }
<?php $st = true; } ?>		]
}