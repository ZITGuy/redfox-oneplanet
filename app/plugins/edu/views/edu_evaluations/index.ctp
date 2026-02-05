
var store_eduEvaluations = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_class','edu_evaluation_area','order_level','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_class_id', direction: "ASC"},
	groupField: 'edu_evaluation_area_id'
});


function AddEduEvaluation() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduEvaluation_data = response.responseText;
			
			eval(eduEvaluation_data);
			
			EduEvaluationAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluation add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduEvaluation(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduEvaluation_data = response.responseText;
			
			eval(eduEvaluation_data);
			
			EduEvaluationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluation edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduEvaluation(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduEvaluation_data = response.responseText;

            eval(eduEvaluation_data);

            EduEvaluationViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluation view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduRegistrationEvaluations(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduRegistrationEvaluations_data = response.responseText;

            eval(parent_eduRegistrationEvaluations_data);

            parentEduRegistrationEvaluationsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteEduEvaluation(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduEvaluation successfully deleted!'); ?>');
			RefreshEduEvaluationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluation add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduEvaluation(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduEvaluation_data = response.responseText;

			eval(eduEvaluation_data);

			eduEvaluationSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduEvaluation search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduEvaluationName(value){
	var conditions = '\'EduEvaluation.name LIKE\' => \'%' + value + '%\'';
	store_eduEvaluations.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduEvaluationData() {
	store_eduEvaluations.reload();
}


if(center_panel.find('id', 'eduEvaluation-tab') != "") {
	var p = center_panel.findById('eduEvaluation-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Evaluations'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduEvaluation-tab',
		xtype: 'grid',
		store: store_eduEvaluations,
		columns: [
			{header: "<?php __('EduClass'); ?>", dataIndex: 'edu_class', sortable: true},
			{header: "<?php __('EduEvaluationArea'); ?>", dataIndex: 'edu_evaluation_area', sortable: true},
			{header: "<?php __('Order Level'); ?>", dataIndex: 'order_level', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduEvaluations" : "EduEvaluation"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduEvaluation(Ext.getCmp('eduEvaluation-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduEvaluations</b><br />Click here to create a new EduEvaluation'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduEvaluation();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduEvaluation',
					tooltip:'<?php __('<b>Edit EduEvaluations</b><br />Click here to modify the selected EduEvaluation'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduEvaluation(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduEvaluation',
					tooltip:'<?php __('<b>Delete EduEvaluations(s)</b><br />Click here to remove the selected EduEvaluation(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduEvaluation'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduEvaluation(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduEvaluation'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduEvaluations'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduEvaluation(sel_ids);
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
					text: '<?php __('View EduEvaluation'); ?>',
					id: 'view-eduEvaluation',
					tooltip:'<?php __('<b>View EduEvaluation</b><br />Click here to see details of the selected EduEvaluation'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduEvaluation(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Edu Registration Evaluations'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentEduRegistrationEvaluations(sel.data.id);
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
							store_eduEvaluations.reload({
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
					id: 'eduEvaluation_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduEvaluationName(Ext.getCmp('eduEvaluation_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduEvaluation_go_button',
					handler: function(){
						SearchByEduEvaluationName(Ext.getCmp('eduEvaluation_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduEvaluation();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduEvaluations,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduEvaluation').enable();
		p.getTopToolbar().findById('delete-eduEvaluation').enable();
		p.getTopToolbar().findById('view-eduEvaluation').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluation').disable();
			p.getTopToolbar().findById('view-eduEvaluation').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluation').disable();
			p.getTopToolbar().findById('view-eduEvaluation').disable();
			p.getTopToolbar().findById('delete-eduEvaluation').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduEvaluation').enable();
			p.getTopToolbar().findById('view-eduEvaluation').enable();
			p.getTopToolbar().findById('delete-eduEvaluation').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduEvaluation').disable();
			p.getTopToolbar().findById('view-eduEvaluation').disable();
			p.getTopToolbar().findById('delete-eduEvaluation').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduEvaluations.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
