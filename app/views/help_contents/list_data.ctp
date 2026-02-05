{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($help_contents as $help_content){ if($st) echo ","; ?>       {
        "id":"<?php echo $help_content['HelpContent']['id']; ?>",
        "name":"<?php echo $help_content['HelpContent']['name']; ?>",
        "code":"<?php echo $help_content['HelpContent']['code']; ?>",
        "created":"<?php echo $help_content['HelpContent']['created']; ?>",
        "modified":"<?php echo $help_content['HelpContent']['modified']; ?>"        }
<?php $st = true; } ?>  ]
}