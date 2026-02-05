<?php $status = array('N' => 'Not Submitted', 'S' => 'Submitted'); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($attendance_records as $attendance_record){ if($st) echo ","; ?>			{
				"id":"<?php echo $attendance_record['EduAttendanceRecord']['id']; ?>",
				"user":"<?php echo $attendance_record['User']['username']; ?>",
				"section":"<?php echo $attendance_record['EduSection']['name']; ?>",
				"quarter":"<?php echo $attendance_record['EduQuarter']['name']; ?>",
				"status":"<?php echo $status[$attendance_record['EduAttendanceRecord']['status']]; ?>",
				"date":"<?php echo $attendance_record['EduDay']['date']; ?>",
				"created":"<?php echo $attendance_record['EduAttendanceRecord']['created']; ?>"			}
<?php $st = true; } ?>		]
}