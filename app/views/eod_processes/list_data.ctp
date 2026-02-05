<?php $statuses = array('P' => 'Pending...', 'C' => 'Completed', 'I' => 'Incremental', 'F' => 'Full Backup'); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($eod_processes as $eod_process){ if($st) echo ","; ?>			{
				"id":"<?php echo $eod_process['EodProcess']['id']; ?>",
				"name":"<?php echo $eod_process['EodProcess']['name']; ?>",
				"process_date":"<?php echo $eod_process['EodProcess']['process_date']; ?>",
				"user":"<?php echo $eod_process['User']['username']; ?>",
				"task1_backup_taken":"<?php echo $statuses[$eod_process['EodProcess']['task1_backup_taken']]; ?>",
				"task2_portal_updated":"<?php echo $statuses[$eod_process['EodProcess']['task2_portal_updated']]; ?>",
				"task3_ftp_sent":"<?php echo $statuses[$eod_process['EodProcess']['task3_ftp_sent']]; ?>",
				"backup_type":"<?php echo $statuses[$eod_process['EodProcess']['backup_type']]; ?>",
				"incremental_count":"<?php echo $eod_process['EodProcess']['incremental_count']; ?>",
				"backup_incremental_file":"<?php echo $eod_process['EodProcess']['backup_incremental_file']; ?>",
				"backup_full_file":"<?php echo $eod_process['EodProcess']['backup_full_file']; ?>",
				"created":"<?php echo $eod_process['EodProcess']['created']; ?>",
				"modified":"<?php echo $eod_process['EodProcess']['modified']; ?>"			}
<?php $st = true; } ?>		]
}