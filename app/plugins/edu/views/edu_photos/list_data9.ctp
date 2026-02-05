{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php 
    $st = false; 
	$relationships = array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian', 'S' => 'Student');
    foreach($photos as $k => $photo){ if($st) {echo ",";}  
?>			{
            "id":"<?php echo $k; ?>",
            "title":"<?php echo $photo['title']; ?>",
            "relationship":"<?php echo $relationships[$photo['relationship']]; ?>",
            "photo_file":'<?php echo $this->Html->image('tmpphotos/' . $photo['photo_file'], array('height' => '100px')); ?>'			}
<?php $st = true; } ?>		]
}