
var store_eduRegistrationQuarters = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration','edu_quarter','quarter_average','quarter_rank','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_registration_id', direction: "ASC"},
	groupField: 'edu_quarter_id'
});


function AddEduRegistrationQuarter() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduRegistrationQuarter_data = response.responseText;
			
			eval(eduRegistrationQuarter_data);
			
			EduRegistrationQuarterAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduRegistrationQuarter(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduRegistrationQuarter_data = response.responseText;
			
			eval(eduRegistrationQuarter_data);
			
			EduRegistrationQuarterEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationQuarter(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduRegistrationQuarter_data = response.responseText;

            eval(eduRegistrationQuarter_data);

            EduRegistrationQuarterViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduRegistrationQuarterResults(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarterResults', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduRegistrationQuarterResults_data = response.responseText;

            eval(parent_eduRegistrationQuarterResults_data);

            parentEduRegistrationQuarterResultsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteEduRegistrationQuarter(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduRegistrationQuarter successfully deleted!'); ?>');
			RefreshEduRegistrationQuarterData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationQuarter add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduRegistrationQuarter(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduRegistrationQuarter_data = response.responseText;

			eval(eduRegistrationQuarter_data);

			eduRegistrationQuarterSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduRegistrationQuarter search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduRegistrationQuarterName(value){
	var conditions = '\'EduRegistrationQuarter.name LIKE\' => \'%' + value + '%\'';
	store_eduRegistrationQuarters.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduRegistrationQuarterData() {
	store_eduRegistrationQuarters.reload();
}


if(center_panel.find('id', 'eduRegistrationQuarter-tab') != "") {
	var p = center_panel.findById('eduRegistrationQuarter-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Registration Quarters'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduRegistrationQuarter-tab',
		xtype: 'grid',
		store: store_eduRegistrationQuarters,
		columns: [
			{header: "<?php __('EduRegistration'); ?>", dataIndex: 'edu_registration', sortable: true},
			{header: "<?php __('EduQuarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
			{header: "<?php __('Quarter Average'); ?>", dataIndex: 'quarter_average', sortable: true},
			{header: "<?php __('Quarter Rank'); ?>", dataIndex: 'quarter_rank', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduRegistrationQuarters" : "EduRegistrationQuarter"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduRegistrationQuarter(Ext.getCmp('eduRegistrationQuarter-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduRegistrationQuarters</b><br />Click here to create a new EduRegistrationQuarter'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduRegistrationQuarter();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduRegistrationQuarter',
					tooltip:'<?php __('<b>Edit EduRegistrationQuarters</b><br />Click here to modify the selected EduRegistrationQuarter'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduRegistrationQuarter(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduRegistrationQuarter',
					tooltip:'<?php __('<b>Delete EduRegistrationQuarters(s)</b><br />Click here to remove the selected EduRegistrationQuarter(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarter'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduRegistrationQuarter(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationQuarter'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduRegistrationQuarters'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduRegistrationQuarter(sel_ids);
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
					text: '<?php __('View EduRegistrationQuarter'); ?>',
					id: 'view-eduRegistrationQuarter',
					tooltip:'<?php __('<b>View EduRegistrationQuarter</b><br />Click here to see details of the selected EduRegistrationQuarter'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduRegistrationQuarter(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Edu Registration Quarter Results'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentEduRegistrationQuarterResults(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '<?php __('EduRegistration'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($eduregistrations as $item){if($st) echo ",
							";?>['<?php echo $item['EduRegistration']['id']; ?>' ,'<?php echo $item['EduRegistration']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduRegistrationQuarters.reload({
								params: {
									start: 0,
									limit: list_size,
									eduregistration_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduRegistrationQuarter_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduRegistrationQuarterName(Ext.getCmp('eduRegistrationQuarter_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduRegistrationQuarter_go_button',
					handler: function(){
						SearchByEduRegistrationQuarterName(Ext.getCmp('eduRegistrationQuarter_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduRegistrationQuarter();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduRegistrationQuarters,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduRegistrationQuarter').enable();
		p.getTopToolbar().findById('delete-eduRegistrationQuarter').enable();
		p.getTopToolbar().findById('view-eduRegistrationQuarter').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistrationQuarter').disable();
			p.getTopToolbar().findById('view-eduRegistrationQuarter').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistrationQuarter').disable();
			p.getTopToolbar().findById('view-eduRegistrationQuarter').disable();
			p.getTopToolbar().findById('delete-eduRegistrationQuarter').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduRegistrationQuarter').enable();
			p.getTopToolbar().findById('view-eduRegistrationQuarter').enable();
			p.getTopToolbar().findById('delete-eduRegistrationQuarter').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduRegistrationQuarter').disable();
			p.getTopToolbar().findById('view-eduRegistrationQuarter').disable();
			p.getTopToolbar().findById('delete-eduRegistrationQuarter').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduRegistrationQuarters.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
