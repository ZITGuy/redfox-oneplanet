//<script>
var store_eduSection_eduAssessments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_course','edu_section','edu_quarter','out_of','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssessments', 'action' => 'list_data', $eduSection['EduSection']['id'])); ?>'	})
});
var store_eduSection_eduAssignments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_course','edu_section','start_date','end_date','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'list_data', $eduSection['EduSection']['id'])); ?>'	})
});
var store_eduSection_eduRegistrations = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_student','edu_section','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrations', 'action' => 'list_data', $eduSection['EduSection']['id'])); ?>'	})
});
		
<?php $eduSection_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduSection['EduSection']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Class', true) . ":</th><td><b>" . $eduSection['EduClass']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Academic Year', true) . ":</th><td><b>" . $eduSection['EduAcademicYear']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduSection['EduSection']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduSection['EduSection']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduSection_view_panel_1 = {
			html : '<?php echo $eduSection_html; ?>',
			frame : true,
			height: 80
		}
		var eduSection_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduSection_eduAssessments,
				title: '<?php __('EduAssessments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduSection_eduAssessments.getCount() == '')
							store_eduSection_eduAssessments.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Edu Course'); ?>", dataIndex: 'edu_course', sortable: true}
,					{header: "<?php __('Edu Section'); ?>", dataIndex: 'edu_section', sortable: true}
,					{header: "<?php __('Edu Quarter'); ?>", dataIndex: 'edu_quarter', sortable: true}
,					{header: "<?php __('Out Of'); ?>", dataIndex: 'out_of', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduSection_eduAssessments,
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
				store: store_eduSection_eduAssignments,
				title: '<?php __('EduAssignments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduSection_eduAssignments.getCount() == '')
							store_eduSection_eduAssignments.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Teacher'); ?>", dataIndex: 'edu_teacher', sortable: true}
,					{header: "<?php __('Edu Course'); ?>", dataIndex: 'edu_course', sortable: true}
,					{header: "<?php __('Edu Section'); ?>", dataIndex: 'edu_section', sortable: true}
,					{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true}
,					{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduSection_eduAssignments,
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
				store: store_eduSection_eduRegistrations,
				title: '<?php __('EduRegistrations'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduSection_eduRegistrations.getCount() == '')
							store_eduSection_eduRegistrations.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Edu Student'); ?>", dataIndex: 'edu_student', sortable: true}
,					{header: "<?php __('Edu Section'); ?>", dataIndex: 'edu_section', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduSection_eduRegistrations,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduSectionViewWindow = new Ext.Window({
			title: '<?php __('View EduSection'); ?>: <?php echo $eduSection['EduSection']['name']; ?>',
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
				eduSection_view_panel_1,
				eduSection_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduSectionViewWindow.close();
				}
			}]
		});
