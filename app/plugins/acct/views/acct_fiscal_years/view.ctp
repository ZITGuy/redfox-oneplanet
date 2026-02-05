//<script>
var store_acctFiscalYear_acctTransactions = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description','cheque_number','invoice_number','acct_fiscal_year','user','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'list_data', $acctFiscalYear['AcctFiscalYear']['id'])); ?>'	})
});
		
<?php $acctFiscalYear_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $acctFiscalYear['AcctFiscalYear']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Start Date', true) . ":</th><td><b>" . $acctFiscalYear['AcctFiscalYear']['start_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('End Date', true) . ":</th><td><b>" . $acctFiscalYear['AcctFiscalYear']['end_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $acctFiscalYear['AcctFiscalYear']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $acctFiscalYear['AcctFiscalYear']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var acctFiscalYear_view_panel_1 = {
			html : '<?php echo $acctFiscalYear_html; ?>',
			frame : true,
			height: 80
		}
		var acctFiscalYear_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_acctFiscalYear_acctTransactions,
				title: '<?php __('AcctTransactions'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_acctFiscalYear_acctTransactions.getCount() == '')
							store_acctFiscalYear_acctTransactions.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true}
,					{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true}
,					{header: "<?php __('Invoice Number'); ?>", dataIndex: 'invoice_number', sortable: true}
,					{header: "<?php __('Acct Fiscal Year'); ?>", dataIndex: 'acct_fiscal_year', sortable: true}
,					{header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_acctFiscalYear_acctTransactions,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var AcctFiscalYearViewWindow = new Ext.Window({
			title: '<?php __('View AcctFiscalYear'); ?>: <?php echo $acctFiscalYear['AcctFiscalYear']['name']; ?>',
			width: 500,
			height:345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				acctFiscalYear_view_panel_1,
				acctFiscalYear_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AcctFiscalYearViewWindow.close();
				}
			}]
		});
