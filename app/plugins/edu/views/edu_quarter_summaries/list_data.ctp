{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_quarter_summaries as $edu_quarter_summary){ if($st) echo ","; ?> {
        "id":"<?php echo $edu_quarter_summary['EduQuarterSummary']['id']; ?>",
        "quarter_name":"<?php echo $edu_quarter_summary['EduQuarter']['name']; ?>",
        "class_name":"<?php echo $edu_quarter_summary['EduClass']['name']; ?>",
        "status":"<?php echo $edu_quarter_summary['EduQuarterSummary']['status']; ?>"	}
<?php $st = true; } ?>		]
}