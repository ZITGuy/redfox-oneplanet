{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_days as $date){ if($st) {echo ",";} ?>   {
            "id":"<?php echo $date['EduDay']['id']; ?>",
            "name":"<?php echo $date['EduDay']['date']; ?>"	}
<?php $st = true; } ?>  ]
}
