{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_calendar_event_types as $edu_calendar_event_type){ if($st) echo ","; ?>			{
        "id":"<?php echo $edu_calendar_event_type['EduCalendarEventType']['id']; ?>",
        "name":"<?php echo $edu_calendar_event_type['EduCalendarEventType']['name']; ?>",
        "educational":"<?php echo $edu_calendar_event_type['EduCalendarEventType']['educational']? 'True': 'False'; ?>"			}
<?php $st = true; } ?>		]
}