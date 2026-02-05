//<script>
		var store_eduTraining_eduTeachersTrainings = new Ext.data.Store({
			reader: new Ext.data.JsonReader({
				root:'rows',
				totalProperty: 'results',
				fields: [
					'id','edu_teacher','edu_training','from_date','to_date','trainer','remark','deleted','created','modified'		]
			}),
			proxy: new Ext.data.HttpProxy({
				url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'list_data_v', $edu_training['EduTraining']['id'])); ?>'	}),
			sortInfo:{field: 'edu_teacher', direction: "ASC"}
		});

<?php $eduTraining_html = "<table cellspacing=3>" . 		
        "<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $edu_training['EduTraining']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Category', true) . ":</th><td><b>" . $edu_training['EduTrainingCategory']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduTraining_view_panel_1 = {
			html : '<?php echo $eduTraining_html; ?>',
			frame : true,
			height: 80
		}
		var eduTraining_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:290,
			plain:true,
			defaults:{autoScroll: true},
			items:[{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_eduTraining_eduTeachersTrainings,
				title: '<?php __('Teachers Trainings'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduTraining_eduTeachersTrainings.getCount() == '')
							store_eduTraining_eduTeachersTrainings.reload();
					}
				},
				columns: [
					{header: "<?php __('Teacher'); ?>", width: 200, dataIndex: 'edu_teacher', sortable: true},
					{header: "<?php __('From Date'); ?>", dataIndex: 'from_date', sortable: true},
					{header: "<?php __('To Date'); ?>", dataIndex: 'to_date', sortable: true},
					{header: "<?php __('Trainer'); ?>", dataIndex: 'trainer', sortable: true},
					{header: "<?php __('Remark'); ?>", dataIndex: 'remark', sortable: true, hidden: true},
					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduTraining_eduTeachersTrainings,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}]
		});

		var EduTrainingViewWindow = new Ext.Window({
			title: '<?php __('View EduTraining'); ?>: <?php echo $edu_training['EduTraining']['name']; ?>',
			width: 700,
			height: 445,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				eduTraining_view_panel_1,
				eduTraining_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduTrainingViewWindow.close();
				}
			}]
		});
