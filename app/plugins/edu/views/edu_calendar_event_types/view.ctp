
var store_eduCalendarEventType_eduCalendarEvents = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_calendar_event_type','start_date','end_date','edu_quarter','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'list_data', $eduCalendarEventType['EduCalendarEventType']['id'])); ?>'	})
});
		
<?php $eduCalendarEventType_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduCalendarEventType['EduCalendarEventType']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduCalendarEventType_view_panel_1 = {
			html : '<?php echo $eduCalendarEventType_html; ?>',
			frame : true,
			height: 80
		}
		var eduCalendarEventType_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduCalendarEventType_eduCalendarEvents,
				title: '<?php __('EduCalendarEvents'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduCalendarEventType_eduCalendarEvents.getCount() == '')
							store_eduCalendarEventType_eduCalendarEvents.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Edu Calendar Event Type'); ?>", dataIndex: 'edu_calendar_event_type', sortable: true}
,					{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true}
,					{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true}
,					{header: "<?php __('Edu Quarter'); ?>", dataIndex: 'edu_quarter', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduCalendarEventType_eduCalendarEvents,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduCalendarEventTypeViewWindow = new Ext.Window({
			title: '<?php __('View EduCalendarEventType'); ?>: <?php echo $eduCalendarEventType['EduCalendarEventType']['name']; ?>',
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
				eduCalendarEventType_view_panel_1,
				eduCalendarEventType_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduCalendarEventTypeViewWindow.close();
				}
			}]
		});
