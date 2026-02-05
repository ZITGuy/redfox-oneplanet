//<script>
	<?php
		$this->ExtForm->create('AcctTransaction');
		$this->ExtForm->defineFieldFunctions();
	?>
	
	var popUpWin_1=0;
	
	function popUpWindow(URLStr, left, top, width, height) {
		if(popUpWin_1){
			if(!popUpWin_1.closed) popUpWin_1.close();
		}
		popUpWin_1 = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
	}

	function viewRptDailyTransactions() {
		var rtitle = Ext.getCmp('data[AcctTransaction][report_title]');
		var report_title = rtitle.getValue();
		newstr = report_title.replace(/\s/gi, "_");
		var sdate = Ext.getCmp('data[AcctTransaction][transaction_date]');
		var transaction_date = sdate.getValue();
		var dt = new Date(transaction_date);
		transaction_date = dt.format('Y-m-d'); 

		url = "<?php echo $this->Html->url(array('controller' => 'acct_transactions', 'action' => 'rpt_view_daily_transactions')); ?>/" + transaction_date + '/' + newstr;
		popUpWindow(url, 0, 0, 1200, 1200);
	}
	
	var RptDailyTransactionsForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'',
		defaultType: 'textfield',
		
		items: [
			<?php 
				$options = array('value' => 'Daily Transactions', 'id' => 'data[AcctTransaction][report_title]');
				$this->ExtForm->input('report_title', $options);
			?>,
			<?php 
				$options = array('id' => 'data[AcctTransaction][transaction_date]');
				$options['value'] = date('Y-m-d');
				$options['xtype'] = 'datefield';
				$options['fieldLabel'] = 'Transaction Date';
				$this->ExtForm->input('transaction_date', $options);
			?>
		]
	});
	
	
	var RptDailyTransactionsWindow = new Ext.Window({
		title: '<?php __('Report: Daily Transactions'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: RptDailyTransactionsForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				RptDailyTransactionsForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to set parameters to the Enrolled students report.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(RptDailyTransactionsWindow.collapsed)
					RptDailyTransactionsWindow.expand(true);
				else
					RptDailyTransactionsWindow.collapse(true);
			}
		}],
		buttons: [{
			text: '<?php __('Show report'); ?>',
			handler: function(btn){
				viewRptDailyTransactions();
			}
		}, {
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				RptDailyTransactionsWindow.close();
			}
		}]
	});
	
	RptDailyTransactionsWindow.show();
		