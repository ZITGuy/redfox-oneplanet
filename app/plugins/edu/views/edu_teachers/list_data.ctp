<?php //pr($edu_teachers); ?>
{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_teachers as $edu_teacher){ if($st) echo ","; //$p = $edu_teacher['User']['Person']; ?>			{
        "id":"<?php echo $edu_teacher['EduTeacher']['id']; ?>",
        "teacher":"<?php echo strtolower($edu_teacher['User']['username']); //$p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name']; ?>",
        "id2":"<?php echo strtolower($edu_teacher['User']['username']); //$p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name']; ?>",
        "name":"<?php echo strtolower($edu_teacher['User']['username']); //$p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name']; ?>",
        "identity_number":"<?php echo $edu_teacher['EduTeacher']['identity_number']; ?>",
        "telephone":"<?php echo $edu_teacher['EduTeacher']['telephone_home']; ?>",
        "mobile":"<?php echo $edu_teacher['EduTeacher']['telephone_mobile']; ?>"			}
<?php $st = true; } ?>		]
}