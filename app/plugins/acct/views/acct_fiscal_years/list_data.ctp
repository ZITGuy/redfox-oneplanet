{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($acct_fiscal_years as $acct_fiscal_year){ if($st) echo ","; ?>			{
				"id":"<?php echo $acct_fiscal_year['AcctFiscalYear']['id']; ?>",
				"name":"<?php echo $acct_fiscal_year['AcctFiscalYear']['name']; ?>",
				"start_date":"<?php echo $acct_fiscal_year['AcctFiscalYear']['start_date']; ?>",
				"end_date":"<?php echo $acct_fiscal_year['AcctFiscalYear']['end_date']; ?>",
				"created":"<?php echo $acct_fiscal_year['AcctFiscalYear']['created']; ?>",
				"modified":"<?php echo $acct_fiscal_year['AcctFiscalYear']['modified']; ?>"			}
<?php $st = true; } ?>		]
}