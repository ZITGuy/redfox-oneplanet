<?php $actions = array('C' => 'Created', 'U' => 'Edited', 'D' => 'Deleted') ?>
{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($audit_trails as $audit_trail){ if($st) echo ","; ?>			{
            "id":"<?php echo $audit_trail['AuditTrail']['id']; ?>",
            "user":"<?php echo $audit_trail['User']['username']; ?>",
            "session_name":"<?php echo $audit_trail['AuditTrail']['session_name']; ?>",
            "action_made":"<?php echo $audit_trail['AuditTrail']['action_made']; ?>",
            "table_name":"<?php echo $audit_trail['AuditTrail']['table_name']; ?>",
            "work_done":"<?php echo $audit_trail['AuditTrail']['audit_desc'] == ''? $audit_trail['User']['username'] . ' ' . 
                    $actions[$audit_trail['AuditTrail']['action_made']] . ' record of table ' .
                    $audit_trail['AuditTrail']['table_name']: $audit_trail['AuditTrail']['audit_desc']; ?>",
            "old_value":"<?php echo $audit_trail['AuditTrail']['old_value']; ?>",
            "new_value":"<?php echo $audit_trail['AuditTrail']['new_value']; ?>",
            "record_id":"<?php echo $audit_trail['AuditTrail']['record_id']; ?>",
            "created":"<?php echo $audit_trail['AuditTrail']['created']; ?>"			}
<?php $st = true; } ?>		]
}