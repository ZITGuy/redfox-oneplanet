
var store_reportCategory_reports = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description','function_name','report_category','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'list_data', $reportCategory['ReportCategory']['id'])); ?>'	})
});
		
<?php $reportCategory_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $reportCategory['ReportCategory']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var reportCategory_view_panel_1 = {
			html : '<?php echo $reportCategory_html; ?>',
			frame : true,
			height: 80
		}
		var reportCategory_view_panel_2 = new Ext.TabPanel({
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
				store: store_reportCategory_reports,
				title: '<?php __('Reports'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_reportCategory_reports.getCount() == '')
							store_reportCategory_reports.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true}
,					{header: "<?php __('Function Name'); ?>", dataIndex: 'function_name', sortable: true}
,					{header: "<?php __('Report Category'); ?>", dataIndex: 'report_category', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_reportCategory_reports,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var ReportCategoryViewWindow = new Ext.Window({
			title: '<?php __('View ReportCategory'); ?>: <?php echo $reportCategory['ReportCategory']['name']; ?>',
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
				reportCategory_view_panel_1,
				reportCategory_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					ReportCategoryViewWindow.close();
				}
			}]
		});
