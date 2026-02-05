//<script>
var store_parent_edu_teachers_trainings = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_training','from_date','to_date','trainer','remark','deleted','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers_trainings', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduTeachersTraining() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers_trainings', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduTeachersTraining_data = response.responseText;
			
			eval(parent_eduTeachersTraining_data);
			
			EduTeachersTrainingAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher Training add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduTeachersTraining(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers_trainings', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduTeachersTraining_data = response.responseText;
			
			eval(parent_eduTeachersTraining_data);
			
			EduTeachersTrainingEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher Training edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteParentEduTeachersTraining(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers_trainings', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Teacher Training(s) successfully deleted!'); ?>');
			RefreshParentEduTeachersTrainingData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher Training to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduTeachersTrainingName(value){
	var conditions = '\'EduTraining.name LIKE\' => \'%' + value + '%\'';
	store_parent_edu_teachers_trainings.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduTeachersTrainingData() {
	store_parent_edu_teachers_trainings.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Teacher Trainings'); ?>',
	store: store_parent_edu_teachers_trainings,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduTeachersTrainingGrid',
	columns: [
		{header: "<?php __('Teacher'); ?>", dataIndex: 'edu_teacher', sortable: true},
		{header: "<?php __('Training'); ?>", dataIndex: 'edu_training', sortable: true},
		{header: "<?php __('From'); ?>", dataIndex: 'from_date', sortable: true},
		{header: "<?php __('To'); ?>", dataIndex: 'to_date', sortable: true},
		{header: "<?php __('Trainer'); ?>", dataIndex: 'trainer', sortable: true},
		{header: "<?php __('Remark'); ?>", dataIndex: 'remark', sortable: true, hidden: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}	
	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Teacher Training</b><br />Click here to create a new Teacher Training'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduTeachersTraining();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduTeachersTraining',
				tooltip:'<?php __('<b>Edit Teacher Training</b><br />Click here to modify the selected Teacher Training'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduTeachersTraining(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduTeachersTraining',
				tooltip:'<?php __('<b>Delete Teacher Training(s)</b><br />Click here to remove the selected Teacher Training(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Teacher Training'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeleteParentEduTeachersTraining(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Teacher Training'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Teacher Training'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteParentEduTeachersTraining(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduTeachersTraining_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduTeachersTrainingName(Ext.getCmp('parent_eduTeachersTraining_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduTeachersTraining_go_button',
				handler: function(){
					SearchByParentEduTeachersTrainingName(Ext.getCmp('parent_eduTeachersTraining_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_edu_teachers_trainings,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduTeachersTraining').enable();
	g.getTopToolbar().findById('delete-parent-eduTeachersTraining').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduTeachersTraining').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduTeachersTraining').disable();
		g.getTopToolbar().findById('delete-parent-eduTeachersTraining').enable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduTeachersTraining').enable();
		g.getTopToolbar().findById('delete-parent-eduTeachersTraining').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduTeachersTraining').disable();
		g.getTopToolbar().findById('delete-parent-eduTeachersTraining').disable();
	}
});

var parentEduTeachersTrainingsViewWindow = new Ext.Window({
	title: 'Teacher Trainings',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
    modal: true,
	items: [
		g
	],
	buttons: [{
		text: 'Close',
		handler: function(btn){
			parentEduTeachersTrainingsViewWindow.close();
		}
	}]
});

store_parent_edu_teachers_trainings.load({
    params: {
        start: 0,    
        limit: list_size
    }
});