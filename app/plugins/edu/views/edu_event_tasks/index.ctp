
var store_eduEventTasks = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_calendar_event','task','permissions','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_calendar_event_id', direction: "ASC"},
	groupField: 'task'
});


function AddEduEventTask() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduEventTask_data = response.responseText;
			
			eval(eduEventTask_data);
			
			EduEventTaskAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduEventTask(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduEventTask_data = response.responseText;
			
			eval(eduEventTask_data);
			
			EduEventTaskEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduEventTask(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduEventTask_data = response.responseText;

            eval(eduEventTask_data);

            EduEventTaskViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduEventTask(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduEventTask successfully deleted!'); ?>');
			RefreshEduEventTaskData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEventTask add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduEventTask(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduEventTask_data = response.responseText;

			eval(eduEventTask_data);

			eduEventTaskSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduEventTask search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduEventTaskName(value){
	var conditions = '\'EduEventTask.name LIKE\' => \'%' + value + '%\'';
	store_eduEventTasks.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduEventTaskData() {
	store_eduEventTasks.reload();
}


if(center_panel.find('id', 'eduEventTask-tab') != "") {
	var p = center_panel.findById('eduEventTask-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Event Tasks'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduEventTask-tab',
		xtype: 'grid',
		store: store_eduEventTasks,
		columns: [
			{header: "<?php __('EduCalendarEvent'); ?>", dataIndex: 'edu_calendar_event', sortable: true},
			{header: "<?php __('Task'); ?>", dataIndex: 'task', sortable: true},
			{header: "<?php __('Permissions'); ?>", dataIndex: 'permissions', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduEventTasks" : "EduEventTask"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduEventTask(Ext.getCmp('eduEventTask-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduEventTasks</b><br />Click here to create a new EduEventTask'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduEventTask();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduEventTask',
					tooltip:'<?php __('<b>Edit EduEventTasks</b><br />Click here to modify the selected EduEventTask'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduEventTask(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduEventTask',
					tooltip:'<?php __('<b>Delete EduEventTasks(s)</b><br />Click here to remove the selected EduEventTask(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduEventTask'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduEventTask(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduEventTask'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduEventTasks'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduEventTask(sel_ids);
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
					text: '<?php __('View EduEventTask'); ?>',
					id: 'view-eduEventTask',
					tooltip:'<?php __('<b>View EduEventTask</b><br />Click here to see details of the selected EduEventTask'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduEventTask(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduCalendarEvent'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($educalendarevents as $item){if($st) echo ",
							";?>['<?php echo $item['EduCalendarEvent']['id']; ?>' ,'<?php echo $item['EduCalendarEvent']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduEventTasks.reload({
								params: {
									start: 0,
									limit: list_size,
									educalendarevent_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduEventTask_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduEventTaskName(Ext.getCmp('eduEventTask_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduEventTask_go_button',
					handler: function(){
						SearchByEduEventTaskName(Ext.getCmp('eduEventTask_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduEventTask();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduEventTasks,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduEventTask').enable();
		p.getTopToolbar().findById('delete-eduEventTask').enable();
		p.getTopToolbar().findById('view-eduEventTask').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEventTask').disable();
			p.getTopToolbar().findById('view-eduEventTask').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEventTask').disable();
			p.getTopToolbar().findById('view-eduEventTask').disable();
			p.getTopToolbar().findById('delete-eduEventTask').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduEventTask').enable();
			p.getTopToolbar().findById('view-eduEventTask').enable();
			p.getTopToolbar().findById('delete-eduEventTask').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduEventTask').disable();
			p.getTopToolbar().findById('view-eduEventTask').disable();
			p.getTopToolbar().findById('delete-eduEventTask').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduEventTasks.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
