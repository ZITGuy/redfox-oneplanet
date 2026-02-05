//<script>
var store_eduRegistrationEvaluations = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration','edu_evaluation','edu_quarter','edu_evaluation_value','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_registration_evaluations', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_registration_id', direction: "ASC"},
	groupField: 'edu_evaluation_id'
});


function AddEduRegistrationEvaluation() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduRegistrationEvaluation_data = response.responseText;
			
			eval(eduRegistrationEvaluation_data);
			
			EduRegistrationEvaluationAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationEvaluation add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduRegistrationEvaluation(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduRegistrationEvaluation_data = response.responseText;
			
			eval(eduRegistrationEvaluation_data);
			
			EduRegistrationEvaluationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationEvaluation edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduRegistrationEvaluation(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduRegistrationEvaluation_data = response.responseText;

            eval(eduRegistrationEvaluation_data);

            EduRegistrationEvaluationViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationEvaluation view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduRegistrationEvaluation(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduRegistrationEvaluation successfully deleted!'); ?>');
			RefreshEduRegistrationEvaluationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduRegistrationEvaluation add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduRegistrationEvaluation(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduRegistrationEvaluation_data = response.responseText;

			eval(eduRegistrationEvaluation_data);

			eduRegistrationEvaluationSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduRegistrationEvaluation search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduRegistrationEvaluationName(value){
	var conditions = '\'EduRegistrationEvaluation.name LIKE\' => \'%' + value + '%\'';
	store_eduRegistrationEvaluations.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduRegistrationEvaluationData() {
	store_eduRegistrationEvaluations.reload();
}


if(center_panel.find('id', 'eduRegistrationEvaluation-tab') != "") {
	var p = center_panel.findById('eduRegistrationEvaluation-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Registration Evaluations'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduRegistrationEvaluation-tab',
		xtype: 'grid',
		store: store_eduRegistrationEvaluations,
		columns: [
			{header: "<?php __('Registration'); ?>", dataIndex: 'edu_registration', sortable: true},
			{header: "<?php __('Evaluation'); ?>", dataIndex: 'edu_evaluation', sortable: true},
			{header: "<?php __('Quarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
			{header: "<?php __('EvaluationValue'); ?>", dataIndex: 'edu_evaluation_value', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduRegistrationEvaluations" : "EduRegistrationEvaluation"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduRegistrationEvaluation(Ext.getCmp('eduRegistrationEvaluation-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduRegistrationEvaluations</b><br />Click here to create a new EduRegistrationEvaluation'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduRegistrationEvaluation();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduRegistrationEvaluation',
					tooltip:'<?php __('<b>Edit EduRegistrationEvaluations</b><br />Click here to modify the selected EduRegistrationEvaluation'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduRegistrationEvaluation(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduRegistrationEvaluation',
					tooltip:'<?php __('<b>Delete EduRegistrationEvaluations(s)</b><br />Click here to remove the selected EduRegistrationEvaluation(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationEvaluation'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduRegistrationEvaluation(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduRegistrationEvaluation'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduRegistrationEvaluations'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduRegistrationEvaluation(sel_ids);
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
					text: '<?php __('View EduRegistrationEvaluation'); ?>',
					id: 'view-eduRegistrationEvaluation',
					tooltip:'<?php __('<b>View EduRegistrationEvaluation</b><br />Click here to see details of the selected EduRegistrationEvaluation'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduRegistrationEvaluation(sel.data.id);
						};
					},
					menu : {
						items: [
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
							store_eduRegistrationEvaluations.reload({
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
					id: 'eduRegistrationEvaluation_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduRegistrationEvaluationName(Ext.getCmp('eduRegistrationEvaluation_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduRegistrationEvaluation_go_button',
					handler: function(){
						SearchByEduRegistrationEvaluationName(Ext.getCmp('eduRegistrationEvaluation_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduRegistrationEvaluation();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduRegistrationEvaluations,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduRegistrationEvaluation').enable();
		p.getTopToolbar().findById('delete-eduRegistrationEvaluation').enable();
		p.getTopToolbar().findById('view-eduRegistrationEvaluation').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistrationEvaluation').disable();
			p.getTopToolbar().findById('view-eduRegistrationEvaluation').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduRegistrationEvaluation').disable();
			p.getTopToolbar().findById('view-eduRegistrationEvaluation').disable();
			p.getTopToolbar().findById('delete-eduRegistrationEvaluation').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduRegistrationEvaluation').enable();
			p.getTopToolbar().findById('view-eduRegistrationEvaluation').enable();
			p.getTopToolbar().findById('delete-eduRegistrationEvaluation').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduRegistrationEvaluation').disable();
			p.getTopToolbar().findById('view-eduRegistrationEvaluation').disable();
			p.getTopToolbar().findById('delete-eduRegistrationEvaluation').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduRegistrationEvaluations.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
