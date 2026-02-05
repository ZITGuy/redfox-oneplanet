{
	success:true,
	results: 200,
	rows: [
<?php $st = false; foreach($results as $edu_nonavailable_period){ if($st) echo ","; ?>			{
				"id":"<?php echo $edu_nonavailable_period['id']; ?>",
				"edu_section":"<?php echo $edu_nonavailable_period['section_id']; ?>",
				"edu_schedule":"<?php echo $edu_nonavailable_period['schedule_id']; ?>",
				"period":"<?php echo $edu_nonavailable_period['period']; ?>",
				<?php  foreach($edu_nonavailable_period['days'] as $key=>$av){ ?>
				 "<?php echo $key; ?>":"<?php echo $av; ?>",
				<?php }  ?>				}
<?php $st = true; } ?>		]
}