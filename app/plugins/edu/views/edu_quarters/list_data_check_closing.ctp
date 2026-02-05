
<?php 
    $ok_image = $this->Html->image('symbol_check.png', array('title' => 'Completed!'));
    $ok_image = str_replace('"', "'", $ok_image);
    
    $not_ok_image = $this->Html->image('symbol_delete.png', array('title' => 'Not Completed!'));
    $not_ok_image = str_replace('"', "'", $not_ok_image);

    $warning_image = $this->Html->image('symbol_delete.png', array('title' => 'Not Completed!'));
    $warning_image = str_replace('"', "'", $warning_image);

    $statuses = array(
            'OK' => $ok_image,
            'WARNING' => $warning_image,
            'NOT_OK' => $not_ok_image
        );
	
?>
{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($issues as $issue){ if($st) echo ","; ?>			{
        "issue":"<?php echo $issue['issue']; ?>",
        "status":"<?php echo $statuses[$issue['status']]; ?>"			}
<?php $st = true; } ?>		]
}