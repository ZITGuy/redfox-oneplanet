
var store_eduSchedules = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','periods','days','status','created'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"}
});


function AddEduSchedule() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduSchedule_data = response.responseText;
			
			eval(eduSchedule_data);
			
			EduScheduleAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduSchedule add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduSchedule(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduSchedule_data = response.responseText;
			
			eval(eduSchedule_data);
			
			EduScheduleEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduSchedule edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduSchedule(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduSchedule_data = response.responseText;

            eval(eduSchedule_data);

            EduScheduleViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduSchedule view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduNonavailablePeriods(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduNonavailablePeriods', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduNonavailablePeriods_data = response.responseText;

            eval(parent_eduNonavailablePeriods_data);

            parentEduNonavailablePeriodsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewParentEduPeriods(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduPeriods_data = response.responseText;

            eval(parent_eduPeriods_data);

            parentEduPeriodsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewParentEduPeriodsAll(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduPeriods', 'action' => 'index3')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduPeriods_data = response.responseText;

            eval(parent_eduPeriods_data);

            parentEduPeriodsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}
function GenerateTimeTable(id){
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'generate')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduPeriods_data = response.responseText;

            eval(parent_eduPeriods_data);

            parentEduScheduleGenerateWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduSchedule(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduSchedule successfully deleted!'); ?>');
			RefreshEduScheduleData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduSchedule add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduSchedule(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduSchedule_data = response.responseText;

			eval(eduSchedule_data);

			eduScheduleSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduSchedule search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduScheduleName(value){
	var conditions = '\'EduSchedule.name LIKE\' => \'%' + value + '%\'';
	store_eduSchedules.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduScheduleData() {
	store_eduSchedules.reload();
}


if(center_panel.find('id', 'eduSchedule-tab') != "") {
	var p = center_panel.findById('eduSchedule-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Schedule Automation'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduSchedule-tab',
		xtype: 'grid',
		store: store_eduSchedules,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Periods'); ?>", dataIndex: 'periods', sortable: true},
			{header: "<?php __('Days'); ?>", dataIndex: 'days', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
		]
,
		listeners: {
			celldblclick: function(){
				ViewParentEduPeriodsAll(Ext.getCmp('eduSchedule-tab').getSelectionModel().getSelected().data.id);
			}
		},
		view: new Ext.grid.GroupingView({
                forceFit:true
         }),
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduSchedules</b><br />Click here to create a new EduSchedule'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduSchedule();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduSchedule',
					tooltip:'<?php __('<b>Edit EduSchedules</b><br />Click here to modify the selected EduSchedule'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduSchedule(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduSchedule',
					tooltip:'<?php __('<b>Delete EduSchedules(s)</b><br />Click here to remove the selected EduSchedule(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduSchedule'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduSchedule(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduSchedule'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduSchedules'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduSchedule(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				},' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Teachers Preference'); ?>',
					id: 'edit-eduSchedule2',
					tooltip:'<?php __('<b>Edit preference</b>'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewParentEduNonavailablePeriods(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Period preferences'); ?>',
					id: 'edit-eduSchedule3',
					tooltip:'<?php __('<b>Class preference</b>'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewParentEduPeriods(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Re-Generate Time Table'); ?>',
					id: 'edit-eduSchedule4',
					tooltip:'<?php __('<b>Generate Time Table</b>'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							GenerateTimeTable(sel.data.id);
						};
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduSchedules,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduSchedule').enable();
		p.getTopToolbar().findById('edit-eduSchedule2').enable();
		p.getTopToolbar().findById('edit-eduSchedule3').enable();
		p.getTopToolbar().findById('edit-eduSchedule4').enable();
		p.getTopToolbar().findById('delete-eduSchedule').enable();
		p.getTopToolbar().findById('view-eduSchedule').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduSchedule').disable();
			p.getTopToolbar().findById('edit-eduSchedule2').disable();
			p.getTopToolbar().findById('edit-eduSchedule3').disable();
			p.getTopToolbar().findById('edit-eduSchedule4').disable();
			p.getTopToolbar().findById('view-eduSchedule').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduSchedule').disable();
			p.getTopToolbar().findById('edit-eduSchedule2').disable();
			p.getTopToolbar().findById('edit-eduSchedule3').disable();
			p.getTopToolbar().findById('edit-eduSchedule4').disable();
			p.getTopToolbar().findById('view-eduSchedule').disable();
			p.getTopToolbar().findById('delete-eduSchedule').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduSchedule').enable();
			p.getTopToolbar().findById('edit-eduSchedule2').enable();
			p.getTopToolbar().findById('edit-eduSchedule3').enable();
			p.getTopToolbar().findById('edit-eduSchedule4').enable();
			p.getTopToolbar().findById('view-eduSchedule').enable();
			p.getTopToolbar().findById('delete-eduSchedule').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduSchedule').disable();
			p.getTopToolbar().findById('edit-eduSchedule2').disable();
			p.getTopToolbar().findById('edit-eduSchedule3').disable();
			p.getTopToolbar().findById('edit-eduSchedule4').disable();
			p.getTopToolbar().findById('view-eduSchedule').disable();
			p.getTopToolbar().findById('delete-eduSchedule').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduSchedules.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
