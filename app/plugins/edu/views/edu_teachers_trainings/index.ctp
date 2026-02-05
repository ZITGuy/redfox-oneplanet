
var store_eduTeachersTrainings = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_training','from_date','to_date','trainer','remark','deleted','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_teacher_id', direction: "ASC"},
	groupField: 'edu_training_id'
});


function AddEduTeachersTraining() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduTeachersTraining_data = response.responseText;
			
			eval(eduTeachersTraining_data);
			
			EduTeachersTrainingAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTeachersTraining add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduTeachersTraining(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduTeachersTraining_data = response.responseText;
			
			eval(eduTeachersTraining_data);
			
			EduTeachersTrainingEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTeachersTraining edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduTeachersTraining(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduTeachersTraining_data = response.responseText;

            eval(eduTeachersTraining_data);

            EduTeachersTrainingViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTeachersTraining view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduTeachersTraining(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduTeachersTraining successfully deleted!'); ?>');
			RefreshEduTeachersTrainingData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduTeachersTraining add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduTeachersTraining(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTeachersTrainings', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduTeachersTraining_data = response.responseText;

			eval(eduTeachersTraining_data);

			eduTeachersTrainingSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduTeachersTraining search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduTeachersTrainingName(value){
	var conditions = '\'EduTeachersTraining.name LIKE\' => \'%' + value + '%\'';
	store_eduTeachersTrainings.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduTeachersTrainingData() {
	store_eduTeachersTrainings.reload();
}


if(center_panel.find('id', 'eduTeachersTraining-tab') != "") {
	var p = center_panel.findById('eduTeachersTraining-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Teachers Trainings'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduTeachersTraining-tab',
		xtype: 'grid',
		store: store_eduTeachersTrainings,
		columns: [
			{header: "<?php __('EduTeacher'); ?>", dataIndex: 'edu_teacher', sortable: true},
			{header: "<?php __('EduTraining'); ?>", dataIndex: 'edu_training', sortable: true},
			{header: "<?php __('From Date'); ?>", dataIndex: 'from_date', sortable: true},
			{header: "<?php __('To Date'); ?>", dataIndex: 'to_date', sortable: true},
			{header: "<?php __('Trainer'); ?>", dataIndex: 'trainer', sortable: true},
			{header: "<?php __('Remark'); ?>", dataIndex: 'remark', sortable: true},
			{header: "<?php __('Deleted'); ?>", dataIndex: 'deleted', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduTeachersTrainings" : "EduTeachersTraining"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduTeachersTraining(Ext.getCmp('eduTeachersTraining-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduTeachersTrainings</b><br />Click here to create a new EduTeachersTraining'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduTeachersTraining();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduTeachersTraining',
					tooltip:'<?php __('<b>Edit EduTeachersTrainings</b><br />Click here to modify the selected EduTeachersTraining'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduTeachersTraining(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduTeachersTraining',
					tooltip:'<?php __('<b>Delete EduTeachersTrainings(s)</b><br />Click here to remove the selected EduTeachersTraining(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduTeachersTraining'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduTeachersTraining(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduTeachersTraining'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduTeachersTrainings'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduTeachersTraining(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: '<?php __('View EduTeachersTraining'); ?>',
					id: 'view-eduTeachersTraining',
					tooltip:'<?php __('<b>View EduTeachersTraining</b><br />Click here to see details of the selected EduTeachersTraining'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduTeachersTraining(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduTeacher'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($eduteachers as $item){if($st) echo ",
							";?>['<?php echo $item['EduTeacher']['id']; ?>' ,'<?php echo $item['EduTeacher']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduTeachersTrainings.reload({
								params: {
									start: 0,
									limit: list_size,
									eduteacher_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduTeachersTraining_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduTeachersTrainingName(Ext.getCmp('eduTeachersTraining_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduTeachersTraining_go_button',
					handler: function(){
						SearchByEduTeachersTrainingName(Ext.getCmp('eduTeachersTraining_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduTeachersTraining();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduTeachersTrainings,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduTeachersTraining').enable();
		p.getTopToolbar().findById('delete-eduTeachersTraining').enable();
		p.getTopToolbar().findById('view-eduTeachersTraining').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTeachersTraining').disable();
			p.getTopToolbar().findById('view-eduTeachersTraining').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTeachersTraining').disable();
			p.getTopToolbar().findById('view-eduTeachersTraining').disable();
			p.getTopToolbar().findById('delete-eduTeachersTraining').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduTeachersTraining').enable();
			p.getTopToolbar().findById('view-eduTeachersTraining').enable();
			p.getTopToolbar().findById('delete-eduTeachersTraining').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduTeachersTraining').disable();
			p.getTopToolbar().findById('view-eduTeachersTraining').disable();
			p.getTopToolbar().findById('delete-eduTeachersTraining').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduTeachersTrainings.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
