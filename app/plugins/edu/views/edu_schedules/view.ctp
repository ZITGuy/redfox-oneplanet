
var store_eduSchedule_eduNonavailablePeriods = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_schedule','day','period'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduNonavailablePeriods', 'action' => 'list_data', $eduSchedule['EduSchedule']['id'])); ?>'	})
});
var store_eduSchedule_eduPeriods = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_section','edu_course_Id','edu_schedule','day','period'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'list_data', $eduSchedule['EduSchedule']['id'])); ?>'	})
});
		
<?php $eduSchedule_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduSchedule['EduSchedule']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Periods', true) . ":</th><td><b>" . $eduSchedule['EduSchedule']['periods'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Days', true) . ":</th><td><b>" . $eduSchedule['EduSchedule']['days'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Status', true) . ":</th><td><b>" . $eduSchedule['EduSchedule']['status'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduSchedule['EduSchedule']['created'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduSchedule_view_panel_1 = {
			html : '<?php echo $eduSchedule_html; ?>',
			frame : true,
			height: 80
		}
		var eduSchedule_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduSchedule_eduNonavailablePeriods,
				title: '<?php __('EduNonavailablePeriods'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduSchedule_eduNonavailablePeriods.getCount() == '')
							store_eduSchedule_eduNonavailablePeriods.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Teacher'); ?>", dataIndex: 'edu_teacher', sortable: true}
,					{header: "<?php __('Edu Schedule'); ?>", dataIndex: 'edu_schedule', sortable: true}
,					{header: "<?php __('Day'); ?>", dataIndex: 'day', sortable: true}
,					{header: "<?php __('Period'); ?>", dataIndex: 'period', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduSchedule_eduNonavailablePeriods,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			},
{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_eduSchedule_eduPeriods,
				title: '<?php __('EduPeriods'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduSchedule_eduPeriods.getCount() == '')
							store_eduSchedule_eduPeriods.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Section'); ?>", dataIndex: 'edu_section', sortable: true}
,					{header: "<?php __('Edu Course Id'); ?>", dataIndex: 'edu_course_Id', sortable: true}
,					{header: "<?php __('Edu Schedule'); ?>", dataIndex: 'edu_schedule', sortable: true}
,					{header: "<?php __('Day'); ?>", dataIndex: 'day', sortable: true}
,					{header: "<?php __('Period'); ?>", dataIndex: 'period', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduSchedule_eduPeriods,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduScheduleViewWindow = new Ext.Window({
			title: '<?php __('View EduSchedule'); ?>: <?php echo $eduSchedule['EduSchedule']['name']; ?>',
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
				eduSchedule_view_panel_1,
				eduSchedule_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduScheduleViewWindow.close();
				}
			}]
		});
