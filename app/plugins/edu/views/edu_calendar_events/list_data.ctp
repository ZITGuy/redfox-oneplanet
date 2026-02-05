{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_calendar_events as $edu_calendar_event){ if($st) echo ","; ?>			{
                "id":"<?php echo $edu_calendar_event['EduCalendarEvent']['id']; ?>",
                "name":"<?php echo $edu_calendar_event['EduCalendarEvent']['name']; ?>",
                "edu_calendar_event_type":"<?php echo $edu_calendar_event['EduCalendarEventType']['name']; ?>",
                "start_date":"<?php echo $edu_calendar_event['EduCalendarEvent']['start_date']; ?>",
                "end_date":"<?php echo $edu_calendar_event['EduCalendarEvent']['end_date']; ?>",
                "edu_quarter":"<?php echo $edu_calendar_event['EduQuarter']['name']; ?>",
                "edu_campus":"<?php echo ($edu_calendar_event['EduCampus']['name']? $edu_calendar_event['EduCampus']['name']: 'All Campuses'); ?>",
                "created":"<?php echo $edu_calendar_event['EduCalendarEvent']['created']; ?>",
                "modified":"<?php echo $edu_calendar_event['EduCalendarEvent']['modified']; ?>"			}
<?php $st = true; } ?>		]
}