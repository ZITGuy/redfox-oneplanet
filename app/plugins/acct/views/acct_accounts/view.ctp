//<script>
var store_acctAccount_acctJournals = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','acct_transaction','acct_account','dr','cr','bbf','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'list_data', $acctAccount['AcctAccount']['id'])); ?>'	})
});
		
<?php $acctAccount_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $acctAccount['AcctAccount']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $acctAccount['AcctAccount']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Acct Category', true) . ":</th><td><b>" . $acctAccount['AcctCategory']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Balance', true) . ":</th><td><b>" . $acctAccount['AcctAccount']['balance'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('User', true) . ":</th><td><b>" . $acctAccount['User']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $acctAccount['AcctAccount']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $acctAccount['AcctAccount']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var acctAccount_view_panel_1 = {
			html : '<?php echo $acctAccount_html; ?>',
			frame : true,
			height: 80
		}
		var acctAccount_view_panel_2 = new Ext.TabPanel({
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
				store: store_acctAccount_acctJournals,
				title: '<?php __('AcctJournals'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_acctAccount_acctJournals.getCount() == '')
							store_acctAccount_acctJournals.reload();
					}
				},
				columns: [
					{header: "<?php __('Acct Transaction'); ?>", dataIndex: 'acct_transaction', sortable: true}
,					{header: "<?php __('Acct Account'); ?>", dataIndex: 'acct_account', sortable: true}
,					{header: "<?php __('Dr'); ?>", dataIndex: 'dr', sortable: true}
,					{header: "<?php __('Cr'); ?>", dataIndex: 'cr', sortable: true}
,					{header: "<?php __('Bbf'); ?>", dataIndex: 'bbf', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_acctAccount_acctJournals,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var AcctAccountViewWindow = new Ext.Window({
			title: '<?php __('View AcctAccount'); ?>: <?php echo $acctAccount['AcctAccount']['name']; ?>',
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
				acctAccount_view_panel_1,
				acctAccount_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AcctAccountViewWindow.close();
				}
			}]
		});
