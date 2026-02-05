//<script>
var store_acctCategory_acctAccounts = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','code','acct_category','balance','user','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'acct_accounts', 'action' => 'list_data', $acctCategory['AcctCategory']['id'])); ?>'	})
});
		
<?php $acctCategory_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Prefix', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['prefix'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Postfix', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['postfix'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Last Code', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['last_code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $acctCategory['AcctCategory']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var acctCategory_view_panel_1 = {
			html : '<?php echo $acctCategory_html; ?>',
			frame : true,
			height: 80
		}
		var acctCategory_view_panel_2 = new Ext.TabPanel({
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
				store: store_acctCategory_acctAccounts,
				title: '<?php __('AcctAccounts'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_acctCategory_acctAccounts.getCount() == '')
							store_acctCategory_acctAccounts.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},					
					{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},					
					{header: "<?php __('Acct Category'); ?>", dataIndex: 'acct_category', sortable: true},					
					{header: "<?php __('Balance'); ?>", dataIndex: 'balance', sortable: true},					
					{header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true},					
					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},					
					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_acctCategory_acctAccounts,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var AcctCategoryViewWindow = new Ext.Window({
			title: '<?php __('View AcctCategory'); ?>: <?php echo $acctCategory['AcctCategory']['name']; ?>',
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
				acctCategory_view_panel_1,
				acctCategory_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AcctCategoryViewWindow.close();
				}
			}]
		});
