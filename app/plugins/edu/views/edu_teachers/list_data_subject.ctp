{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_teachers as $edu_teacher){ if($st) echo ","; $p = $edu_teacher['User']['Person']; ?>			{
        "id":"<?php echo $edu_teacher['EduTeacher']['id']; ?>",
        "name":"<?php echo $p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name']; ?>",
        "identity_number":"<?php echo $edu_teacher['EduTeacher']['identity_number']; ?>"			}
<?php $st = true; } ?>		]
}