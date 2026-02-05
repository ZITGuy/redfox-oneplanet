{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($holidays as $holiday){ if($st) echo ","; ?>			{
            "id":"<?php echo $holiday['Holiday']['id']; ?>",
            "name":"<?php echo $holiday['Holiday']['name']; ?>",
            "date":"<?php echo date('F j, Y', strtotime($holiday['Holiday']['from_date'])) . ' - ' . date('F j, Y', strtotime($holiday['Holiday']['to_date'])); ?>",
            "from_date":"<?php echo $holiday['Holiday']['from_date']; ?>",
            "to_date":"<?php echo $holiday['Holiday']['to_date']; ?>",
            "is_recurrent":"<?php echo $holiday['Holiday']['is_recurrent']? 'True': 'False'; ?>",
            "created":"<?php echo $holiday['Holiday']['created']; ?>",
            "modified":"<?php echo $holiday['Holiday']['modified']; ?>"			}
<?php $st = true; } ?>		]
}