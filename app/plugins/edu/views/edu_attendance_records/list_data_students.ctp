{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($students as $student){ if($st) {echo ",";} ?>   {
            "id":"<?php echo $student['EduStudent']['id']; ?>",
            "student":"<?php echo $student['EduStudent']['name']; ?>",
            "status":"<?php echo $student['EduStudent']['status']; ?>",
            "remark":"<?php echo $student['EduStudent']['remark']; ?>"	}
<?php $st = true; } ?>  ]
}
