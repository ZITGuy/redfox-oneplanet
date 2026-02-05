<?php 
    $statuses = array(
        1 => "<font color='green'>Active</font>", 
        2 => "<font color='green'>Inactive</font>", 
        8 => "<font color='gray'>Closed</font>"
    );
    //pr($edu_academic_years);
?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_academic_years as $edu_academic_year){ if($st) echo ","; ?>			{
                    "id":"<?php echo $edu_academic_year['EduAcademicYear']['id']; ?>",
                    "name":"<?php echo $edu_academic_year['EduAcademicYear']['name']; ?>",
                    "start_date":"<?php echo $edu_academic_year['EduAcademicYear']['start_date']; ?>",
                    "status":"<?php echo $statuses[$edu_academic_year['EduAcademicYear']['status_id']]; ?>",
                    "end_date":"<?php echo $edu_academic_year['EduAcademicYear']['end_date']; ?>",
                    "created":"<?php echo $edu_academic_year['EduAcademicYear']['created']; ?>",
                    "modified":"<?php echo $edu_academic_year['EduAcademicYear']['modified']; ?>"			}
<?php $st = true; } ?>		]
}