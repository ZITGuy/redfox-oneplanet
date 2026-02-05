{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php 
    $st = false; 
    foreach($edu_parents as $edu_parent){ 
        if($st) {echo ",";}  
        $mother = '';
        $father = '';
        $guardian = '';
        foreach($edu_parent['EduParentDetail'] as $pd){
            if($pd['relationship'] == 'mother'){
                $mother .= $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
            }
            if($pd['relationship'] == 'father'){
                $father .= $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
            }
            if($pd['relationship'] == 'guardian'){
                $guardian .= $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
            }
        }
?>			{
            "id":"<?php echo $edu_parent['EduParent']['id']; ?>",
            "mother":"<?php echo $mother; ?>",
            "father":"<?php echo $father; ?>",
            "guardian":"<?php echo $guardian; ?>",
            "modified":"<?php echo date('F d, Y', strtotime($edu_parent['EduParent']['modified'])); ?>"			}
<?php $st = true; } ?>		]
}