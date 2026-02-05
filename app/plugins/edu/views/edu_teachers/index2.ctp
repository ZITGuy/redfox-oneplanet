//<script>
var store_parent_eduTeachers = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','user'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduTeacher() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduTeacher_data = response.responseText;
			
			eval(parent_eduTeacher_data);
			
			EduTeacherAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduTeacher(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduTeacher_data = response.responseText;
			
			eval(parent_eduTeacher_data);
			
			EduTeacherEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduTeacher(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduTeacher_data = response.responseText;

			eval(eduTeacher_data);

			EduTeacherViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduTeacherEduAssignments(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assignments', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduAssignments_data = response.responseText;

			eval(parent_eduAssignments_data);

			parentEduAssignmentsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessments view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduTeacher(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Teacher(s) successfully deleted!'); ?>');
			RefreshParentEduTeacherData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Teacher to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduTeacherName(value){
	var conditions = '\'EduTeacher.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduTeachers.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduTeacherData() {
	store_parent_eduTeachers.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('Teachers'); ?>',
	store: store_parent_eduTeachers,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduTeacherGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header:"<?php __('user'); ?>", dataIndex: 'user', sortable: true}	
	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduTeacher(Ext.getCmp('eduTeacherGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Teacher</b><br />Click here to create a new Teacher'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduTeacher();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduTeacher',
				tooltip:'<?php __('<b>Edit Teacher</b><br />Click here to modify the selected Teacher'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduTeacher(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduTeacher',
				tooltip:'<?php __('<b>Delete Teacher(s)</b><br />Click here to remove the selected Teacher(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Teacher'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduTeacher(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Teacher'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Teacher'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduTeacher(sel_ids);
											}
									}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', {
				xtype: 'tbsplit',
				text: '<?php __('View Teacher'); ?>',
				id: 'view-eduTeacher2',
				tooltip:'<?php __('<b>View Teacher</b><br />Click here to see details of the selected Teacher'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduTeacher(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Assignments'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewEduTeacherEduAssignments(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduTeacher_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduTeacherName(Ext.getCmp('parent_eduTeacher_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduTeacher_go_button',
				handler: function(){
					SearchByParentEduTeacherName(Ext.getCmp('parent_eduTeacher_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduTeachers,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduTeacher').enable();
	g.getTopToolbar().findById('delete-parent-eduTeacher').enable();
        g.getTopToolbar().findById('view-eduTeacher2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduTeacher').disable();
                g.getTopToolbar().findById('view-eduTeacher2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduTeacher').disable();
		g.getTopToolbar().findById('delete-parent-eduTeacher').enable();
                g.getTopToolbar().findById('view-eduTeacher2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduTeacher').enable();
		g.getTopToolbar().findById('delete-parent-eduTeacher').enable();
                g.getTopToolbar().findById('view-eduTeacher2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduTeacher').disable();
		g.getTopToolbar().findById('delete-parent-eduTeacher').disable();
                g.getTopToolbar().findById('view-eduTeacher2').disable();
	}
});



var parentEduTeachersViewWindow = new Ext.Window({
	title: 'Teacher Under the selected Item',
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
			parentEduTeachersViewWindow.close();
		}
	}]
});

store_parent_eduTeachers.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
