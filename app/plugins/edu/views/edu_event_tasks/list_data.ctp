{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_event_tasks as $edu_event_task){ if($st) echo ","; ?>			{
            "id":"<?php echo $edu_event_task['EduEventTask']['id']; ?>",
            "edu_calendar_event_type":"<?php echo $edu_event_task['EduCalendarEventType']['name']; ?>",
            "task":"<?php echo $edu_event_task['EduEventTask']['task']; ?>",
            "permissions":"<?php echo $edu_event_task['EduEventTask']['permissions']; ?>",
            "created":"<?php echo $edu_event_task['EduEventTask']['created']; ?>",
            "modified":"<?php echo $edu_event_task['EduEventTask']['modified']; ?>"			}
<?php $st = true; } ?>		]
}