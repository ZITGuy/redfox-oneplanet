//<script>
var store_eduTeachers = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'teacher', 'identity_number', 'class_level', 'telephone', 'mobile'	
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data')); ?>"
	}),	
	sortInfo:{field: 'teacher', direction: "ASC"},
        groupField: 'class_level'
});


function AddEduTeacher() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var eduTeacher_data = response.responseText;
			
			eval(eduTeacher_data);
			
			EduTeacherAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditEduTeacher(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var eduTeacher_data = response.responseText;
			
			eval(eduTeacher_data);
			
			EduTeacherEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewEduTeacher(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var eduTeacher_data = response.responseText;

            eval(eduTeacher_data);

            EduTeacherViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher view form. Error code'); ?>: " + response.status);
        }
    });
}
function ViewParentEduAssignments(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'edu_assignments', 'action' => 'index2')); ?>/"+id,
        success: function(response, opts) {
            var parent_eduAssignments_data = response.responseText;

            eval(parent_eduAssignments_data);

            parentEduAssignmentsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the assessments view form. Error code'); ?>: " + response.status);
        }
    });
}


function DeleteEduTeacher(id) {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'delete')); ?>/"+id,
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Teacher successfully deleted!'); ?>");
                RefreshEduTeacherData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher add form. Error code'); ?>: " + response.status);
            }
	});
}

function SearchEduTeacher(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduTeacher_data = response.responseText;

			eval(eduTeacher_data);

			eduTeacherSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Teacher search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByEduTeacherName(value){
	var conditions = '\'EduTeacher.name LIKE\' => \'%' + value + '%\'';
	store_eduTeachers.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduTeacherData() {
	store_eduTeachers.reload();
}


if(center_panel.find('id', 'eduTeacher-tab') != "") {
	var p = center_panel.findById('eduTeacher-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Teachers'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduTeacher-tab',
		xtype: 'grid',
		store: store_eduTeachers,
		columns: [
                    {header: "<?php __('Teacher'); ?>", dataIndex: 'teacher', sortable: true},
                    {header: "<?php __('ID Number'); ?>", dataIndex: 'identity_number', sortable: true},
                    {header: "<?php __('Class Level (Cycle)'); ?>", dataIndex: 'class_level', sortable: true},
                    {header: "<?php __('Telephone'); ?>", dataIndex: 'telephone', sortable: true},
                    {header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true}
		],
		view: new Ext.grid.GroupingView({
                    forceFit: true,
                    groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Teachers" : "Teacher"]})'
                }),
		listeners: {
                    celldblclick: function(){
                        ViewEduTeacher(Ext.getCmp('eduTeacher-tab').getSelectionModel().getSelected().data.id);
                    }
		},
		sm: new Ext.grid.RowSelectionModel({
                    singleSelect: true
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add Teachers</b><br />Click here to create a new Teacher'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduTeacher();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-eduTeacher',
					tooltip: "<?php __('<b>Edit Teachers</b><br />Click here to modify the selected Teacher'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduTeacher(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-eduTeacher',
					tooltip: "<?php __('<b>Delete Teachers(s)</b><br />Click here to remove the selected Teacher(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
                                                    if(sel.length==1){
                                                        Ext.Msg.show({
                                                            title: "<?php __('Remove Teacher'); ?>",
                                                            buttons: Ext.MessageBox.YESNO,
                                                            msg: "<?php __('Remove'); ?> <b><i>"+sel[0].data.teacher+'</i></b>?',
                                                            icon: Ext.MessageBox.QUESTION,
                                                            fn: function(btn){
                                                                if (btn == 'yes'){
                                                                    DeleteEduTeacher(sel[0].data.id);
                                                                }
                                                            }
                                                        });
                                                    }
						} else {
                                                    Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: "<?php __('View Teacher'); ?>",
					id: 'view-eduTeacher',
					tooltip: "<?php __('<b>View Teacher</b><br />Click here to see details of the selected Teacher'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduTeacher(sel.data.id);
						};
					},
					menu : {
						items: [{
                                                    text: '<?php __('View Assignments'); ?>',
                                                    icon: 'img/table_view.png',
                                                    cls: 'x-btn-text-icon',
                                                    handler: function(btn) {
                                                            var sm = p.getSelectionModel();
                                                            var sel = sm.getSelected();
                                                            if (sm.hasSelection()){
                                                                    ViewParentEduAssignments(sel.data.id);
                                                            };
                                                    }
						}
						]
					}
				}, ' ', '-',  "<?php __('User'); ?>: ", {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($users as $item){if($st) echo ",
							";?>['<?php echo $item['User']['id']; ?>' ,'<?php echo $item['User']['username']; ?>']<?php $st = true;}?>]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduTeachers.reload({
								params: {
									start: 0,
									limit: list_size,
									user_id : combo.getValue()
								}
							});
						}
					}
				}, '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'eduTeacher_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduTeacherName(Ext.getCmp('eduTeacher_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "eduTeacher_go_button",
					handler: function(){
						SearchByEduTeacherName(Ext.getCmp('eduTeacher_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchEduTeacher();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduTeachers,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduTeacher').enable();
		p.getTopToolbar().findById('delete-eduTeacher').enable();
		p.getTopToolbar().findById('view-eduTeacher').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTeacher').disable();
			p.getTopToolbar().findById('view-eduTeacher').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduTeacher').disable();
			p.getTopToolbar().findById('view-eduTeacher').disable();
			p.getTopToolbar().findById('delete-eduTeacher').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduTeacher').enable();
			p.getTopToolbar().findById('view-eduTeacher').enable();
			p.getTopToolbar().findById('delete-eduTeacher').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduTeacher').disable();
			p.getTopToolbar().findById('view-eduTeacher').disable();
			p.getTopToolbar().findById('delete-eduTeacher').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduTeachers.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}