
var store_absentees = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','attendance_record','student','code','reason'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'absentees', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'attendance_record_id', direction: "ASC"},
	groupField: 'student_id'
});


function AddAbsentee() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'absentees', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var absentee_data = response.responseText;
			
			eval(absentee_data);
			
			AbsenteeAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditAbsentee(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'absentees', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var absentee_data = response.responseText;
			
			eval(absentee_data);
			
			AbsenteeEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAbsentee(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'absentees', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var absentee_data = response.responseText;

            eval(absentee_data);

            AbsenteeViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteAbsentee(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'absentees', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Absentee successfully deleted!'); ?>');
			RefreshAbsenteeData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the absentee add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchAbsentee(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'absentees', 'action' => 'search')); ?>',
		success: function(response, opts){
			var absentee_data = response.responseText;

			eval(absentee_data);

			absenteeSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the absentee search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByAbsenteeName(value){
	var conditions = '\'Absentee.name LIKE\' => \'%' + value + '%\'';
	store_absentees.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshAbsenteeData() {
	store_absentees.reload();
}


if(center_panel.find('id', 'absentee-tab') != "") {
	var p = center_panel.findById('absentee-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Absentees'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'absentee-tab',
		xtype: 'grid',
		store: store_absentees,
		columns: [
			{header: "<?php __('Student'); ?>", dataIndex: 'student', sortable: true},
			{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
			{header: "<?php __('Reason'); ?>", dataIndex: 'reason', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Absentees" : "Absentee"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewAbsentee(Ext.getCmp('absentee-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Absentees</b><br />Click here to create a new Absentee'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddAbsentee();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-absentee',
					tooltip:'<?php __('<b>Edit Absentees</b><br />Click here to modify the selected Absentee'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAbsentee(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-absentee',
					tooltip:'<?php __('<b>Delete Absentees(s)</b><br />Click here to remove the selected Absentee(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Absentee'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteAbsentee(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Absentee'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Absentees'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteAbsentee(sel_ids);
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
					text: '<?php __('View Absentee'); ?>',
					id: 'view-absentee',
					tooltip:'<?php __('<b>View Absentee</b><br />Click here to see details of the selected Absentee'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewAbsentee(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('AttendanceRecord'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($attendancerecords as $item){if($st) echo ",
							";?>['<?php echo $item['AttendanceRecord']['id']; ?>' ,'<?php echo $item['AttendanceRecord']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_absentees.reload({
								params: {
									start: 0,
									limit: list_size,
									attendancerecord_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'absentee_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAbsenteeName(Ext.getCmp('absentee_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'absentee_go_button',
					handler: function(){
						SearchByAbsenteeName(Ext.getCmp('absentee_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchAbsentee();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_absentees,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-absentee').enable();
		p.getTopToolbar().findById('delete-absentee').enable();
		p.getTopToolbar().findById('view-absentee').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-absentee').disable();
			p.getTopToolbar().findById('view-absentee').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-absentee').disable();
			p.getTopToolbar().findById('view-absentee').disable();
			p.getTopToolbar().findById('delete-absentee').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-absentee').enable();
			p.getTopToolbar().findById('view-absentee').enable();
			p.getTopToolbar().findById('delete-absentee').enable();
		}
		else{
			p.getTopToolbar().findById('edit-absentee').disable();
			p.getTopToolbar().findById('view-absentee').disable();
			p.getTopToolbar().findById('delete-absentee').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_absentees.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
