
var store_eduRegistrationQuarterResults = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration_quarter','edu_course','course_result','result_indicator','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_registration_quarter_id', direction: "ASC"},
	groupField: 'edu_course_id'
});


function AddEduRegistrationQuarterResult() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduRegistrationQuarterResult_data = response.responseText;
			
			eval(eduRegistrationQuarterResult_data);
			
			EduRegistrationQuarterResultAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduRegistrationQuarterResult(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduRegistrationQuarterResult_data = response.responseText;
			
			eval(eduRegistrationQuarterResult_data);
			
			EduRegistrationQuarterResultEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationQuarterResult(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduRegistrationQuarterResult_data = response.responseText;

            eval(eduRegistrationQuarterResult_data);

            EduRegistrationQuarterResultViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduRegistrationQuarterResult(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduRegistrationQuarterResult successfully deleted!'); ?>');
			RefreshEduRegistrationQuarterResultData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarterResult add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduRegistrationQuarterResult(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduRegistrationQuarterResult_data = response.responseText;

			eval(eduRegistrationQuarterResult_data);

			eduRegistrationQuarterResultSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduRegistrationQuarterResult search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduRegistrationQuarterResultName(value){
	var conditions = '\'EduRegistrationQuarterResult.name LIKE\' => \'%' + value + '%\'';
	store_eduRegistrationQuarterResults.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduRegistrationQuarterResultData() {
	store_eduRegistrationQuarterResults.reload();
}


if(center_panel.find('id', 'eduRegistrationQuarterResult-tab') != "") {
	var p = center_panel.findById('eduRegistrationQuarterResult-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Registration Quarter Results'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduRegistrationQuarterResult-tab',
		xtype: 'grid',
		store: store_eduRegistrationQuarterResults,
		columns: [
			{header: "<?php __('EduRegistrationQuarter'); ?>", dataIndex: 'edu_registration_quarter', sortable: true},
			{header: "<?php __('EduCourse'); ?>", dataIndex: 'edu_course', sortable: true},
			{header: "<?php __('Course Result'); ?>", dataIndex: 'course_result', sortable: true},
			{header: "<?php __('Result Indicator'); ?>", dataIndex: 'result_indicator', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduRegistrationQuarterResults" : "EduRegistrationQuarterResult"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduRegistrationQuarterResult(Ext.getCmp('eduRegistrationQuarterResult-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduRegistrationQuarterResults</b><br />Click here to create a new EduRegistrationQuarterResult'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduRegistrationQuarterResult();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduRegistrationQuarterResult',
					tooltip:'<?php __('<b>Edit EduRegistrationQuarterResults</b><br />Click here to modify the selected EduRegistrationQuarterResult'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduRegistrationQuarterResult(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduRegistrationQuarterResult',
					tooltip:'<?php __('<b>Delete EduRegistrationQuarterResults(s)</b><br />Click here to remove the selected EduRegistrationQuarterResult(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarterResult'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduRegistrationQuarterResult(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarterResult'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduRegistrationQuarterResults'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduRegistrationQuarterResult(sel_ids);
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
					text: '<?php __('View EduRegistrationQuarterResult'); ?>',
					id: 'view-eduRegistrationQuarterResult',
					tooltip:'<?php __('<b>View EduRegistrationQuarterResult</b><br />Click here to see details of the selected EduRegistrationQuarterResult'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduRegistrationQuarterResult(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduRegistrationQuarter'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($eduregistrationquarters as $item){if($st) echo ",
							";?>['<?php echo $item['EduRegistrationQuarter']['id']; ?>' ,'<?php echo $item['EduRegistrationQuarter']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduRegistrationQuarterResults.reload({
								params: {
									start: 0,
									limit: list_size,
									eduregistrationquarter_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduRegistrationQuarterResult_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduRegistrationQuarterResultName(Ext.getCmp('eduRegistrationQuarterResult_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduRegistrationQuarterResult_go_button',
					handler: function(){
						SearchByEduRegistrationQuarterResultName(Ext.getCmp('eduRegistrationQuarterResult_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduRegistrationQuarterResult();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduRegistrationQuarterResults,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduRegistrationQuarterResult').enable();
		p.getTopToolbar().findById('delete-eduRegistrationQuarterResult').enable();
		p.getTopToolbar().findById('view-eduRegistrationQuarterResult').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistrationQuarterResult').disable();
			p.getTopToolbar().findById('view-eduRegistrationQuarterResult').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistrationQuarterResult').disable();
			p.getTopToolbar().findById('view-eduRegistrationQuarterResult').disable();
			p.getTopToolbar().findById('delete-eduRegistrationQuarterResult').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduRegistrationQuarterResult').enable();
			p.getTopToolbar().findById('view-eduRegistrationQuarterResult').enable();
			p.getTopToolbar().findById('delete-eduRegistrationQuarterResult').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduRegistrationQuarterResult').disable();
			p.getTopToolbar().findById('view-eduRegistrationQuarterResult').disable();
			p.getTopToolbar().findById('delete-eduRegistrationQuarterResult').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduRegistrationQuarterResults.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
