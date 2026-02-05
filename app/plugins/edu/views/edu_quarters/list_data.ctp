<?php 
    $ac_image = $this->Html->image('symbol_check.png');
    $ac_image = str_replace('"', "'", $ac_image);
    
    $cl_image = $this->Html->image('symbol_restricted.png');
    $cl_image = str_replace('"', "'", $cl_image);
    
    $cr_image = $this->Html->image('symbol_question.png');
    $cr_image = str_replace('"', "'", $cr_image);
    
    $statuses = array(
        9 => $cr_image . " <b>Created / Not Started</b>", 
        8 => $cl_image . " <font color='red'><b>Closed</b></font>", 
        1 => $ac_image . " <font color='darkgreen'><b> Active / Open</b></font>"
    );
    
    $quarter_types = array('E' => 'Educational', 'N' => 'Non-Educational');
    //print_r($edu_quarters);
?>
{
    success:true,
    results: <?php echo $results; ?>,
    rows: [ 
<?php $st = false; foreach($edu_quarters as $edu_quarter){ if($st) echo ","; ?>			{
        "id":"<?php echo $edu_quarter['EduQuarter']['id']; ?>",
        "name":"<?php echo $edu_quarter['EduQuarter']['name']; ?>",
        "short_name":"<?php echo $edu_quarter['EduQuarter']['short_name']; ?>",
        "start_date":"<?php echo $edu_quarter['EduQuarter']['start_date']; ?>",
        "end_date":"<?php echo $edu_quarter['EduQuarter']['end_date']; ?>",
        "edu_academic_year":"<?php echo $edu_quarter['EduAcademicYear']['name']; ?>",
        "status":"<?php echo $statuses[$edu_quarter['EduQuarter']['status_id']]; ?>",
        "summarizable":"<?php echo $edu_quarter['EduQuarter']['summarizable']; ?>",
        "status_id":"<?php echo $edu_quarter['EduQuarter']['status_id']; ?>",
        "openable":"<?php echo $edu_quarter['EduQuarter']['id'] == $openable_quarter_id? 1: 0; ?>",
        "quarter_type":"<?php echo $quarter_types[$edu_quarter['EduQuarter']['quarter_type']]; ?>",
        "created":"<?php echo $edu_quarter['EduQuarter']['created']; ?>",
        "modified":"<?php echo $edu_quarter['EduQuarter']['modified']; ?>"			}
<?php $st = true; } ?>		]
}