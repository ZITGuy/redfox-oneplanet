{
    success:true,
    results: <?php echo $results; ?>,
    rows: [ 
<?php $st = false; foreach($edu_quarters as $edu_quarter){ if($st) echo ","; ?>			{
        "id":"<?php echo $edu_quarter['EduQuarter']['id']; ?>",
        "name":"<?php echo $edu_quarter['EduQuarter']['name']; ?>",
        "short_name":"<?php echo $edu_quarter['EduQuarter']['short_name']; ?>"			}
<?php $st = true; } ?>		]
}