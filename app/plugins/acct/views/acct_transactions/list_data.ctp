{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($acct_transactions as $acct_transaction){ if($st) echo ","; ?>			{
				"id":"<?php echo $acct_transaction['AcctTransaction']['id']; ?>",
				"name":"<?php echo $acct_transaction['AcctTransaction']['name']; ?>",
				"description":"<?php echo $acct_transaction['AcctTransaction']['description']; ?>",
				"cheque_number":"<?php echo $acct_transaction['AcctTransaction']['cheque_number']; ?>",
				"invoice_number":"<?php echo $acct_transaction['AcctTransaction']['invoice_number']; ?>",
				"transaction_date":"<?php echo $acct_transaction['AcctTransaction']['transaction_date']; ?>",
				"acct_fiscal_year":"<?php echo $acct_transaction['AcctFiscalYear']['name']; ?>",
				"user":"<?php echo $acct_transaction['User']['id']; ?>",
				"created":"<?php echo $acct_transaction['AcctTransaction']['created']; ?>",
				"modified":"<?php echo $acct_transaction['AcctTransaction']['modified']; ?>"			}
<?php $st = true; } ?>		]
}