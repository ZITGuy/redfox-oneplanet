//<script>
var store_edu_teacher_eduAssignments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_course','edu_section','start_date','end_date','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assignments', 'action' => 'list_data', $edu_teacher['EduTeacher']['id'])); ?>'	})
});

var store_edu_teacher_subjects = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name'		
		]
	}),
	sortInfo: {field: 'name', direction: "ASC"},
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data_teacher_subject', $edu_teacher['EduTeacher']['id'])); ?>'	})
});

var store_edu_teacher_classes = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name'		
		]
	}),
	sortInfo: {field: 'name', direction: "ASC"},
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data_teacher_class', $edu_teacher['EduTeacher']['id'])); ?>'	})
});

<?php 
$t_obj = $edu_teacher['EduTeacher'];
$edu_teacher_html = "<table cellspacing=3>" . 		
		"<tr><td align=left rowspan=4>" . $this->Html->image('teachers/' . ($t_obj['photo'] == 'No file'? 'default-m.jpg': $t_obj['photo']), array('height' => '120px')) . "</td>" . 
		"    <th align=right>" . __('ID', true) . ":</th><td><b>" . $t_obj['identity_number'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Phone', true) . ":</th><td><b>" . ($t_obj['telephone_mobile'] == ''? 'N/A': $t_obj['telephone_mobile']) . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Qualification', true) . ":</th><td><b>" . ($t_obj['qualification'] == ''? 'N/A': $t_obj['qualification']) . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Campus', true) . ":</th><td><b>" . $edu_teacher['User']['EduCampus']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var edu_teacher_view_panel_1 = {
			html : '<?php echo $edu_teacher_html; ?>',
			frame : true,
			height: 150
		}
		var edu_teacher_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height: 190,
			plain:true,
			defaults:{autoScroll: true},
			items:[{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_edu_teacher_eduAssignments,
				title: '<?php __('Assignments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_edu_teacher_eduAssignments.getCount() == '')
							store_edu_teacher_eduAssignments.reload();
					}
				},
				columns: [
					{header: "<?php __('Teacher'); ?>", dataIndex: 'edu_teacher', sortable: true, hidden: true},
					{header: "<?php __('Course'); ?>", dataIndex: 'edu_course', sortable: true},
					{header: "<?php __('Section'); ?>", dataIndex: 'edu_section', sortable: true},
					{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
					{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_edu_teacher_eduAssignments,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}, {
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_edu_teacher_subjects,
				title: '<?php __('Subjects'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_edu_teacher_subjects.getCount() == '')
							store_edu_teacher_subjects.reload();
					}
				},
				columns: [
					{header: "<?php __('Subject Name'); ?>", dataIndex: 'name', sortable: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_edu_teacher_subjects,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}, {
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_edu_teacher_classes,
				title: '<?php __('Classes'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_edu_teacher_classes.getCount() == '')
							store_edu_teacher_classes.reload();
					}
				},
				columns: [
					{header: "<?php __('Class Name'); ?>", dataIndex: 'name', sortable: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_edu_teacher_classes,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}]
		});

		var EduTeacherViewWindow = new Ext.Window({
			title: '<?php __('View Teacher'); ?>: <?php echo $edu_teacher['User']['Person']['first_name']; ?>',
			width: 650,
			height: 415,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
            modal: true,
			items: [ 
				edu_teacher_view_panel_1,
				edu_teacher_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduTeacherViewWindow.close();
				}
			}]
		});
