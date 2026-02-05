//<script>
var store_eduRegistration_eduResults = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration','edu_assessment','result','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduResults', 'action' => 'list_data', $eduRegistration['EduRegistration']['id'])); ?>'	})
});
		
<?php $eduRegistration_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduRegistration['EduRegistration']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Student', true) . ":</th><td><b>" . $eduRegistration['EduStudent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Section', true) . ":</th><td><b>" . $eduRegistration['EduSection']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduRegistration['EduRegistration']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduRegistration['EduRegistration']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduRegistration_view_panel_1 = {
			html : '<?php echo $eduRegistration_html; ?>',
			frame : true,
			height: 80
		}
		var eduRegistration_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduRegistration_eduResults,
				title: '<?php __('EduResults'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduRegistration_eduResults.getCount() == '')
							store_eduRegistration_eduResults.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Registration'); ?>", dataIndex: 'edu_registration', sortable: true}
,					{header: "<?php __('Edu Assessment'); ?>", dataIndex: 'edu_assessment', sortable: true}
,					{header: "<?php __('Result'); ?>", dataIndex: 'result', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduRegistration_eduResults,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduRegistrationViewWindow = new Ext.Window({
			title: '<?php __('View EduRegistration'); ?>: <?php echo $eduRegistration['EduRegistration']['name']; ?>',
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
				eduRegistration_view_panel_1,
				eduRegistration_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduRegistrationViewWindow.close();
				}
			}]
		});
