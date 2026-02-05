<?php $statuses = array('-1' => 'All', '1' => 'Active', '2'  => 'Inactive', '3' => 'Dismissed', '4' => 'Withdrawn', '5' => 'Transferred', '6' => 'Incomplete', '7' => 'Enrolled but not registered', '8' => 'Other'); ?>
{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
        "id":"<?php echo $edu_registration['EduRegistration']['id']; ?>",
        "name":"<?php echo $edu_registration['EduStudent']['name']; ?>",
        "birth_date":"<?php echo $edu_registration['EduStudent']['birth_date']; ?>",
        "identity_number":"<?php echo $edu_registration['EduStudent']['identity_number']; ?>",
        "registration_date":"<?php echo $edu_registration['EduStudent']['registration_date']; ?>",
        "edu_parent":"<?php echo $edu_registration['EduStudent']['EduParent']['authorized_person']; ?>",
        "status":"<?php echo $statuses[$edu_registration['EduStudent']['status']]; ?>",
        "user":"<?php echo $edu_registration['EduStudent']['User']['username']; ?>",
        "edu_class":"<?php echo $edu_registration['EduClass']['name']; ?>",
        "edu_section":"<?php echo $edu_registration['EduRegistration']['edu_section_id'] == 0? 'Not Sectioned Yet': $edu_registration['EduSection']['name']; ?>",
        "created":"<?php echo $edu_registration['EduRegistration']['created']; ?>",
        "modified":"<?php echo $edu_registration['EduRegistration']['modified']; ?>"			}
<?php $st = true; } ?>		]
}