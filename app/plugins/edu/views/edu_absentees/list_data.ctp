{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_absentees as $absentee){ if($st) echo ","; ?>			{
				"id":"<?php echo $absentee['EduAbsentee']['id']; ?>",
				"student":"<?php echo $absentee['EduStudent']['name']; ?>",
				"status":"<?php echo $absentee['EduAbsentee']['status']; ?>",
				"reason":"<?php echo $absentee['EduAbsentee']['reason']; ?>"			}
<?php $st = true; } ?>		]
}