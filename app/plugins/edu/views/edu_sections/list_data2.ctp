<?php //pr($edu_sections); ?>
{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_sections as $edu_section){ if($st) echo ","; if(count($edu_section['EduTeacher']['EduTeacher']) > 0) { $p = $edu_section['EduTeacher']['User']['Person']; $p2 = $edu_section['EduTeacher2']['User']['Person']; } ?>			{
        "id":"<?php echo $edu_section['EduSection']['id']; ?>",
        "name":"<?php echo $edu_section['EduSection']['name']; ?>",
        "students":"<?php echo count($edu_section['EduRegistration']); ?>",
        "edu_campus":"<?php echo $edu_section['EduCampus']['name']; ?>",
        "modified":"<?php echo $edu_section['EduSection']['modified']; ?>",
        "homeroom":"<?php echo count($edu_section['EduTeacher']['EduTeacher']) > 0? $p['first_name'] . ' ' . $p['middle_name'] . ' ' . $p['last_name'] . ": " . $edu_section['EduTeacher']['EduTeacher']['identity_number']: 'TBA'; ?>",
        "homeroom2":"<?php echo count($edu_section['EduTeacher2']['EduTeacher']) > 0? $p2['first_name'] . ' ' . $p2['middle_name'] . ' ' . $p2['last_name'] . ": " . $edu_section['EduTeacher2']['EduTeacher']['identity_number']: 'TBA'; ?>"			}
<?php $st = true; } ?>		]
}
