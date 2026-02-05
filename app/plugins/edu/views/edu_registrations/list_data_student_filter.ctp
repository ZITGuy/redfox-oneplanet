<?php //pr($edu_registrations); ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($edu_registrations as $edu_registration){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_registration['EduRegistration']['id']; ?>",
				"name":"<?php echo $edu_registration['EduRegistration']['name']; ?>",
				"edu_section":"<?php echo $edu_registration['EduClass']['name'] . ' - ' . ($edu_registration['EduRegistration']['edu_section_id'] == 0? 'Not Sectioned Yet': $edu_registration['EduSection']['name']); ?>",
				"grand_total_average":"<?php echo $edu_registration['EduRegistration']['grand_total_average']; ?>",
				"rank":"<?php echo $edu_registration['EduRegistration']['rank']; ?>",
				"allowed":"<?php echo $edu_registration['EduRegistration']['allowed'] == 'A'? '<font color=green>Allowed</font>': '<font color=red>Not Allowed</font>'; ?>",
				"is_allowed": <?php echo $edu_registration['EduRegistration']['allowed'] == 'N'? 'true': 'false'; ?>
			}
<?php $st = true; } ?>		]
}