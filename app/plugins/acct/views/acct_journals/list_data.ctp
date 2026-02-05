{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($acct_journals as $acct_journal){ if($st) echo ","; ?>			{
				"id":"<?php echo $acct_journal['AcctJournal']['id']; ?>",
				"acct_transaction":"<?php echo $acct_journal['AcctTransaction']['name']; ?>",
				"acct_account":"<?php echo $acct_journal['AcctAccount']['name']; ?>",
				"dr":"<?php echo $acct_journal['AcctJournal']['dr']; ?>",
				"cr":"<?php echo $acct_journal['AcctJournal']['cr']; ?>",
				"bbf":"<?php echo $acct_journal['AcctJournal']['bbf']; ?>",
				"created":"<?php echo $acct_journal['AcctJournal']['created']; ?>",
				"modified":"<?php echo $acct_journal['AcctJournal']['modified']; ?>"			}
<?php $st = true; } ?>		]
}