{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($reports as $report){ if($st) echo ","; ?>			{
				"id":"<?php echo $report['Report']['id']; ?>",
				"name":"<?php echo $report['Report']['name']; ?>",
				"description":"<?php echo $report['Report']['description']; ?>",
				"function_name":"<?php echo $report['Report']['function_name']; ?>",
				"report_category":"<?php echo $report['ReportCategory']['name']; ?>",
				"created":"<?php echo $report['Report']['created']; ?>",
				"modified":"<?php echo $report['Report']['modified']; ?>"			}
<?php $st = true; } ?>		]
}