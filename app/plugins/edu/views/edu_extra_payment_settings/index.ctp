
var store_eduExtraPaymentSettings = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_class','amount','edu_academic_year'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'edu_class_id'
});


function AddEduExtraPaymentSetting() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduExtraPaymentSetting_data = response.responseText;
			
			eval(eduExtraPaymentSetting_data);
			
			EduExtraPaymentSettingAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPaymentSetting add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduExtraPaymentSetting(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduExtraPaymentSetting_data = response.responseText;
			
			eval(eduExtraPaymentSetting_data);
			
			EduExtraPaymentSettingEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPaymentSetting edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduExtraPaymentSetting(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduExtraPaymentSetting_data = response.responseText;

            eval(eduExtraPaymentSetting_data);

            EduExtraPaymentSettingViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPaymentSetting view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduExtraPayments(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduExtraPayments_data = response.responseText;

            eval(parent_eduExtraPayments_data);

            parentEduExtraPaymentsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteEduExtraPaymentSetting(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduExtraPaymentSetting successfully deleted!'); ?>');
			RefreshEduExtraPaymentSettingData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPaymentSetting add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduExtraPaymentSetting(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPaymentSettings', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduExtraPaymentSetting_data = response.responseText;

			eval(eduExtraPaymentSetting_data);

			eduExtraPaymentSettingSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduExtraPaymentSetting search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduExtraPaymentSettingName(value){
	var conditions = '\'EduExtraPaymentSetting.name LIKE\' => \'%' + value + '%\'';
	store_eduExtraPaymentSettings.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduExtraPaymentSettingData() {
	store_eduExtraPaymentSettings.reload();
}


if(center_panel.find('id', 'eduExtraPaymentSetting-tab') != "") {
	var p = center_panel.findById('eduExtraPaymentSetting-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Extra Payment Settings'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduExtraPaymentSetting-tab',
		xtype: 'grid',
		store: store_eduExtraPaymentSettings,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('EduClass'); ?>", dataIndex: 'edu_class', sortable: true},
			{header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true},
			{header: "<?php __('EduAcademicYear'); ?>", dataIndex: 'edu_academic_year', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduExtraPaymentSettings" : "EduExtraPaymentSetting"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduExtraPaymentSetting(Ext.getCmp('eduExtraPaymentSetting-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduExtraPaymentSettings</b><br />Click here to create a new EduExtraPaymentSetting'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduExtraPaymentSetting();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduExtraPaymentSetting',
					tooltip:'<?php __('<b>Edit EduExtraPaymentSettings</b><br />Click here to modify the selected EduExtraPaymentSetting'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduExtraPaymentSetting(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduExtraPaymentSetting',
					tooltip:'<?php __('<b>Delete EduExtraPaymentSettings(s)</b><br />Click here to remove the selected EduExtraPaymentSetting(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduExtraPaymentSetting'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduExtraPaymentSetting(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduExtraPaymentSetting'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduExtraPaymentSettings'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduExtraPaymentSetting(sel_ids);
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
					text: '<?php __('View EduExtraPaymentSetting'); ?>',
					id: 'view-eduExtraPaymentSetting',
					tooltip:'<?php __('<b>View EduExtraPaymentSetting</b><br />Click here to see details of the selected EduExtraPaymentSetting'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduExtraPaymentSetting(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Edu Extra Payments'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentEduExtraPayments(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '<?php __('EduClass'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($educlasses as $item){if($st) echo ",
							";?>['<?php echo $item['EduClass']['id']; ?>' ,'<?php echo $item['EduClass']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduExtraPaymentSettings.reload({
								params: {
									start: 0,
									limit: list_size,
									educlass_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduExtraPaymentSetting_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduExtraPaymentSettingName(Ext.getCmp('eduExtraPaymentSetting_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduExtraPaymentSetting_go_button',
					handler: function(){
						SearchByEduExtraPaymentSettingName(Ext.getCmp('eduExtraPaymentSetting_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduExtraPaymentSetting();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduExtraPaymentSettings,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduExtraPaymentSetting').enable();
		p.getTopToolbar().findById('delete-eduExtraPaymentSetting').enable();
		p.getTopToolbar().findById('view-eduExtraPaymentSetting').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduExtraPaymentSetting').disable();
			p.getTopToolbar().findById('view-eduExtraPaymentSetting').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduExtraPaymentSetting').disable();
			p.getTopToolbar().findById('view-eduExtraPaymentSetting').disable();
			p.getTopToolbar().findById('delete-eduExtraPaymentSetting').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduExtraPaymentSetting').enable();
			p.getTopToolbar().findById('view-eduExtraPaymentSetting').enable();
			p.getTopToolbar().findById('delete-eduExtraPaymentSetting').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduExtraPaymentSetting').disable();
			p.getTopToolbar().findById('view-eduExtraPaymentSetting').disable();
			p.getTopToolbar().findById('delete-eduExtraPaymentSetting').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduExtraPaymentSettings.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
