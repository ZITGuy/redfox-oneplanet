//<script>
var store_departments = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','user', 'created'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'list_data')); ?>'
	}),	
	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'user'
});


function AddDepartment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var department_data = response.responseText;
			
			eval(department_data);
			
			DepartmentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Department add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditDepartment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var departments_data = response.responseText;
			    
			eval(departments_data);
			
			DepartmentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Department edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function AssociateEduDepartment(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'associate')); ?>/" + id,
		success: function (response, opts) {
			var edu_department_data = response.responseText;

			eval(edu_department_data);

			EduDepartmentAssociateWindow.show();
		},
		failure: function (response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", 
					"<?php __('Cannot get the Department Association form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewDepartment(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var department_data = response.responseText;

            eval(department_data);

            DepartmentViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Department view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewParentDepartmentCourses(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_departments_courses', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_department_courses_data = response.responseText;

            eval(parent_department_courses_data);

            parentDepartmentCoursesViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the courses view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteDepartment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Department successfully deleted!'); ?>');
			RefreshDepartmentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Department add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchDepartment(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_departments', 'action' => 'search')); ?>',
		success: function(response, opts){
			var department_data = response.responseText;

			eval(department_data);

			departmentSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Department search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByDepartmentName(value){
	var conditions = '\'EduDepartment.name LIKE\' => \'%' + value + '%\'';
	store_departments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshDepartmentData() {
	store_departments.reload();
}


if(center_panel.find('id', 'department-tab') != "") {
	var p = center_panel.findById('department-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Departments'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'department-tab',
		xtype: 'grid',
		store: store_departments,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Departments" : "Department"]})'
        }),
		listeners: {
			celldblclick: function(){
				ViewDepartment(Ext.getCmp('department-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Department</b><br />Click here to create a new Department'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddDepartment();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-department',
					tooltip:'<?php __('<b>Edit Department</b><br />Click here to modify the selected Department'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditDepartment(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Associate'); ?>",
					id: 'associate-eduDepartment',
					tooltip: "<?php __('<b>Associate Department</b><br />Click here to associate the selected Department'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							AssociateEduDepartment(sel.data.id);
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-department',
					tooltip:'<?php __('<b>Delete Department(s)</b><br />Click here to remove the selected Department(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Department'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteDepartment(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Department'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Department'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteDepartment(sel_ids);
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
					text: '<?php __('View Department'); ?>',
					id: 'view-department',
					tooltip:'<?php __('<b>View Department</b><br />Click here to see details of the selected Department'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewDepartment(sel.data.id);
						};
					},
					menu : {
						items: [{
							text: '<?php __('View Department Courses'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentDepartmentCourses(sel.data.id);
								};
							}
						}]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'department_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchBDepartmentName(Ext.getCmp('department_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'department_go_button',
					handler: function(){
						SearchByDepartmentName(Ext.getCmp('department_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchDepartment();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_departments,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-department').enable();
		p.getTopToolbar().findById('associate-eduDepartment').enable();
		p.getTopToolbar().findById('delete-department').enable();
		p.getTopToolbar().findById('view-department').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-department').disable();
			p.getTopToolbar().findById('associate-eduDepartment').disable();
			p.getTopToolbar().findById('view-department').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-department').disable();
			p.getTopToolbar().findById('associate-eduDepartment').disable();
			p.getTopToolbar().findById('view-department').disable();
			p.getTopToolbar().findById('delete-department').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-department').enable();
			p.getTopToolbar().findById('associate-eduDepartment').enable();
			p.getTopToolbar().findById('view-department').enable();
			p.getTopToolbar().findById('delete-department').enable();
		}
		else{
			p.getTopToolbar().findById('edit-department').disable();
			p.getTopToolbar().findById('associate-eduDepartment').disable();
			p.getTopToolbar().findById('view-department').disable();
			p.getTopToolbar().findById('delete-department').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_departments.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
