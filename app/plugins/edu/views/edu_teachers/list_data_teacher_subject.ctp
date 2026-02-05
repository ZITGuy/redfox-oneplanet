{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($subjects as $subject){ if($st) echo ","; ?>			{
        "id":"<?php echo $subject['id']; ?>",
        "name":"<?php echo $subject['name']; ?>"			}
<?php $st = true; } ?>		]
}