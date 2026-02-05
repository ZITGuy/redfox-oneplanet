{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php 
    $st = false;
    foreach($siblings as $k => $sibling){ if($st) {echo ",";}  
?>			{
            "id":"<?php echo $k; ?>",
            "name":"<?php echo $sibling['name']; ?>",
            "age":"<?php echo $sibling['age']; ?>",
            "sex":"<?php echo $sibling['sex']; ?>",
            "grade":"<?php echo $sibling['grade']; ?>"			}
<?php $st = true; } ?>		]
}