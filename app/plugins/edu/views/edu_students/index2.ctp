//<script>
var store_parent_eduStudents = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','birth_date','registration_date','edu_parent', 'user','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduStudent() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduStudent_data = response.responseText;
			
			eval(parent_eduStudent_data);
			
			EduStudentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduStudent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduStudent_data = response.responseText;
			
			eval(parent_eduStudent_data);
			
			EduStudentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduStudent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduStudent_data = response.responseText;

			eval(eduStudent_data);

			EduStudentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduStudentEduRegistrations(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduRegistrations_data = response.responseText;

			eval(parent_eduRegistrations_data);

			parentEduRegistrationsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the registrations view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduStudent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Student(s) successfully deleted!'); ?>');
			RefreshParentEduStudentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Student to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduStudentName(value){
	var conditions = '\'EduStudent.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduStudents.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduStudentData() {
	store_parent_eduStudents.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Students'); ?>',
	store: store_parent_eduStudents,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduStudentGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Birth Date'); ?>", dataIndex: 'birth_date', sortable: true},
		{header: "<?php __('Registration Date'); ?>", dataIndex: 'registration_date', sortable: true},
		{header: "<?php __('Parent'); ?>", dataIndex: 'edu_parent', sortable: true},
		{header: "<?php __('Username'); ?>", dataIndex: 'user', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduStudent(Ext.getCmp('eduStudentGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Student</b><br />Click here to create a new Student'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduStudent();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduStudent',
				tooltip:'<?php __('<b>Edit Student</b><br />Click here to modify the selected Student'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduStudent(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduStudent',
				tooltip:'<?php __('<b>Delete Student(s)</b><br />Click here to remove the selected Student(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Student'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduStudent(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Student'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Student'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteParentEduStudent(sel_ids);
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
				text: '<?php __('View EduStudent'); ?>',
				id: 'view-eduStudent2',
				tooltip:'<?php __('<b>View EduStudent</b><br />Click here to see details of the selected EduStudent'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduStudent(sel.data.id);
					};
				},
				menu : {
					items: [{
							text: '<?php __('View Edu Registrations'); ?>',
							icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = g.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewEduStudentEduRegistrations(sel.data.id);
								};
							}
						}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduStudent_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduStudentName(Ext.getCmp('parent_eduStudent_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduStudent_go_button',
				handler: function(){
					SearchByParentEduStudentName(Ext.getCmp('parent_eduStudent_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduStudents,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduStudent').enable();
	g.getTopToolbar().findById('delete-parent-eduStudent').enable();
        g.getTopToolbar().findById('view-eduStudent2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduStudent').disable();
                g.getTopToolbar().findById('view-eduStudent2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduStudent').disable();
		g.getTopToolbar().findById('delete-parent-eduStudent').enable();
                g.getTopToolbar().findById('view-eduStudent2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduStudent').enable();
		g.getTopToolbar().findById('delete-parent-eduStudent').enable();
                g.getTopToolbar().findById('view-eduStudent2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduStudent').disable();
		g.getTopToolbar().findById('delete-parent-eduStudent').disable();
                g.getTopToolbar().findById('view-eduStudent2').disable();
	}
});



var parentEduStudentsViewWindow = new Ext.Window({
	title: 'Student Under the selected Item',
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
			parentEduStudentsViewWindow.close();
		}
	}]
});

store_parent_eduStudents.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
