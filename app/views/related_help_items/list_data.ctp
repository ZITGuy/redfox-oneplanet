{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($related_help_items as $related_help_item){ if($st) echo ","; ?>			{
				"id":"<?php echo $related_help_item['RelatedHelpItem']['id']; ?>",
				"help_item":"<?php echo $related_help_item['HelpItem']['title']; ?>",
				"related_help_item":"<?php echo $related_help_item['RelatedHelpItem']['id']; ?>"			}
<?php $st = true; } ?>		]
}