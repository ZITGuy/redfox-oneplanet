{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php 
    $st = false; 
    foreach($prev_schools as $k => $prev_school){ if($st) {echo ",";}  
?>			{
            "id":"<?php echo $k; ?>",
            "country":"<?php echo $prev_school['country']; ?>",
            "year_attended":"<?php echo $prev_school['year_attended']; ?>",
            "grade_levels":"<?php echo $prev_school['grade_levels']; ?>",
            "languages":"<?php echo $prev_school['languages']; ?>"			}
<?php $st = true; } ?>		]
}