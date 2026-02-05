
var store_report_groups = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description','is_builtin','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'groups', 'action' => 'list_data', $report['Report']['id'])); ?>'	})
});
		
<?php $report_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $report['Report']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Description', true) . ":</th><td><b>" . $report['Report']['description'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Function Name', true) . ":</th><td><b>" . $report['Report']['function_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Report Category', true) . ":</th><td><b>" . $report['ReportCategory']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $report['Report']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $report['Report']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var report_view_panel_1 = {
			html : '<?php echo $report_html; ?>',
			frame : true,
			height: 80
		}
		var report_view_panel_2 = new Ext.TabPanel({
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
				store: store_report_groups,
				title: '<?php __('Groups'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_report_groups.getCount() == '')
							store_report_groups.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true}
,					{header: "<?php __('Is Builtin'); ?>", dataIndex: 'is_builtin', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_report_groups,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var ReportViewWindow = new Ext.Window({
			title: '<?php __('View Report'); ?>: <?php echo $report['Report']['name']; ?>',
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
				report_view_panel_1,
				report_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					ReportViewWindow.close();
				}
			}]
		});
