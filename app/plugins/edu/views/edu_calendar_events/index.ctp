
var store_eduCalendarEvents = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_calendar_event_type','start_date','end_date','edu_quarter','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'edu_calendar_event_type_id'
});


function AddEduCalendarEvent() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduCalendarEvent_data = response.responseText;
			
			eval(eduCalendarEvent_data);
			
			EduCalendarEventAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCalendarEvent add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduCalendarEvent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduCalendarEvent_data = response.responseText;
			
			eval(eduCalendarEvent_data);
			
			EduCalendarEventEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCalendarEvent edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduCalendarEvent(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduCalendarEvent_data = response.responseText;

            eval(eduCalendarEvent_data);

            EduCalendarEventViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCalendarEvent view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduCalendarEvent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduCalendarEvent successfully deleted!'); ?>');
			RefreshEduCalendarEventData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduCalendarEvent add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduCalendarEvent(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduCalendarEvent_data = response.responseText;

			eval(eduCalendarEvent_data);

			eduCalendarEventSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduCalendarEvent search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduCalendarEventName(value){
	var conditions = '\'EduCalendarEvent.name LIKE\' => \'%' + value + '%\'';
	store_eduCalendarEvents.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduCalendarEventData() {
	store_eduCalendarEvents.reload();
}


if(center_panel.find('id', 'eduCalendarEvent-tab') != "") {
	var p = center_panel.findById('eduCalendarEvent-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Calendar Events'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduCalendarEvent-tab',
		xtype: 'grid',
		store: store_eduCalendarEvents,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('EduCalendarEventType'); ?>", dataIndex: 'edu_calendar_event_type', sortable: true},
			{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
			{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
			{header: "<?php __('EduQuarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduCalendarEvents" : "EduCalendarEvent"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduCalendarEvent(Ext.getCmp('eduCalendarEvent-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduCalendarEvents</b><br />Click here to create a new EduCalendarEvent'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduCalendarEvent();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduCalendarEvent',
					tooltip:'<?php __('<b>Edit EduCalendarEvents</b><br />Click here to modify the selected EduCalendarEvent'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduCalendarEvent(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduCalendarEvent',
					tooltip:'<?php __('<b>Delete EduCalendarEvents(s)</b><br />Click here to remove the selected EduCalendarEvent(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduCalendarEvent'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduCalendarEvent(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduCalendarEvent'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduCalendarEvents'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduCalendarEvent(sel_ids);
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
					text: '<?php __('View EduCalendarEvent'); ?>',
					id: 'view-eduCalendarEvent',
					tooltip:'<?php __('<b>View EduCalendarEvent</b><br />Click here to see details of the selected EduCalendarEvent'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduCalendarEvent(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduCalendarEventType'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($educalendareventtypes as $item){if($st) echo ",
							";?>['<?php echo $item['EduCalendarEventType']['id']; ?>' ,'<?php echo $item['EduCalendarEventType']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduCalendarEvents.reload({
								params: {
									start: 0,
									limit: list_size,
									educalendareventtype_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduCalendarEvent_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduCalendarEventName(Ext.getCmp('eduCalendarEvent_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduCalendarEvent_go_button',
					handler: function(){
						SearchByEduCalendarEventName(Ext.getCmp('eduCalendarEvent_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduCalendarEvent();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduCalendarEvents,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduCalendarEvent').enable();
		p.getTopToolbar().findById('delete-eduCalendarEvent').enable();
		p.getTopToolbar().findById('view-eduCalendarEvent').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduCalendarEvent').disable();
			p.getTopToolbar().findById('view-eduCalendarEvent').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduCalendarEvent').disable();
			p.getTopToolbar().findById('view-eduCalendarEvent').disable();
			p.getTopToolbar().findById('delete-eduCalendarEvent').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduCalendarEvent').enable();
			p.getTopToolbar().findById('view-eduCalendarEvent').enable();
			p.getTopToolbar().findById('delete-eduCalendarEvent').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduCalendarEvent').disable();
			p.getTopToolbar().findById('view-eduCalendarEvent').disable();
			p.getTopToolbar().findById('delete-eduCalendarEvent').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduCalendarEvents.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
