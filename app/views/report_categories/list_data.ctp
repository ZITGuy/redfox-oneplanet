{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($report_categories as $report_category){ if($st) echo ","; ?>			{
				"id":"<?php echo $report_category['ReportCategory']['id']; ?>",
				"name":"<?php echo $report_category['ReportCategory']['name']; ?>"			}
<?php $st = true; } ?>		]
}