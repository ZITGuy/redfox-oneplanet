//<script>
var store_eduAssignments = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_teacher','edu_course','edu_section','start_date','end_date','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'list_data')); ?>"
	})
        ,	sortInfo:{field: 'edu_teacher_id', direction: "ASC"},
	groupField: 'edu_course_id'
});


function AddEduAssignment() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var eduAssignment_data = response.responseText;
			
			eval(eduAssignment_data);
			
			EduAssignmentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduAssignment add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditEduAssignment(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var eduAssignment_data = response.responseText;
			
			eval(eduAssignment_data);
			
			EduAssignmentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduAssignment edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewEduAssignment(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var eduAssignment_data = response.responseText;

            eval(eduAssignment_data);

            EduAssignmentViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduAssignment view form. Error code'); ?>: " + response.status);
        }
    });
}

function DeleteEduAssignment(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'delete')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('EduAssignment successfully deleted!'); ?>");
			RefreshEduAssignmentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduAssignment add form. Error code'); ?>: " + response.status);
		}
	});
}

function SearchEduAssignment(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduAssignments', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduAssignment_data = response.responseText;

			eval(eduAssignment_data);

			eduAssignmentSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduAssignment search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByEduAssignmentName(value){
	var conditions = '\'EduAssignment.name LIKE\' => \'%' + value + '%\'';
	store_eduAssignments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduAssignmentData() {
	store_eduAssignments.reload();
}


if(center_panel.find('id', 'eduAssignment-tab') != "") {
	var p = center_panel.findById('eduAssignment-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Assignments'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduAssignment-tab',
		xtype: 'grid',
		store: store_eduAssignments,
		columns: [
			{header: "<?php __('EduTeacher'); ?>", dataIndex: 'edu_teacher', sortable: true},
			{header: "<?php __('EduCourse'); ?>", dataIndex: 'edu_course', sortable: true},
			{header: "<?php __('EduSection'); ?>", dataIndex: 'edu_section', sortable: true},
			{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
			{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduAssignments" : "EduAssignment"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduAssignment(Ext.getCmp('eduAssignment-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add EduAssignments</b><br />Click here to create a new EduAssignment'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduAssignment();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-eduAssignment',
					tooltip: "<?php __('<b>Edit EduAssignments</b><br />Click here to modify the selected EduAssignment'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduAssignment(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-eduAssignment',
					tooltip: "<?php __('<b>Delete EduAssignments(s)</b><br />Click here to remove the selected EduAssignment(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove EduAssignment'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduAssignment(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove EduAssignment'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected EduAssignments'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduAssignment(sel_ids);
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
					text: "<?php __('View EduAssignment'); ?>",
					id: 'view-eduAssignment',
					tooltip: "<?php __('<b>View EduAssignment</b><br />Click here to see details of the selected EduAssignment'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduAssignment(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  "<?php __('EduTeacher'); ?>: ", {
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
							store_eduAssignments.reload({
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
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'eduAssignment_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduAssignmentName(Ext.getCmp('eduAssignment_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "eduAssignment_go_button",
					handler: function(){
						SearchByEduAssignmentName(Ext.getCmp('eduAssignment_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchEduAssignment();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduAssignments,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduAssignment').enable();
		p.getTopToolbar().findById('delete-eduAssignment').enable();
		p.getTopToolbar().findById('view-eduAssignment').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduAssignment').disable();
			p.getTopToolbar().findById('view-eduAssignment').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduAssignment').disable();
			p.getTopToolbar().findById('view-eduAssignment').disable();
			p.getTopToolbar().findById('delete-eduAssignment').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduAssignment').enable();
			p.getTopToolbar().findById('view-eduAssignment').enable();
			p.getTopToolbar().findById('delete-eduAssignment').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduAssignment').disable();
			p.getTopToolbar().findById('view-eduAssignment').disable();
			p.getTopToolbar().findById('delete-eduAssignment').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduAssignments.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}