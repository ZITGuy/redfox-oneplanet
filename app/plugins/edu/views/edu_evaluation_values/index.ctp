//<script>
var store_eduEvaluationValues = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description', 'evaluation_value_group'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationValues', 'action' => 'list_data')); ?>'
	}),	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'evaluation_value_group'
});


function AddEduEvaluationValue() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationValues', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduEvaluationValue_data = response.responseText;
			
			eval(eduEvaluationValue_data);
			
			EduEvaluationValueAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationValue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduEvaluationValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationValues', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduEvaluationValue_data = response.responseText;
			
			eval(eduEvaluationValue_data);
			
			EduEvaluationValueEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationValue edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteEduEvaluationValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationValues', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduEvaluationValue successfully deleted!'); ?>');
			RefreshEduEvaluationValueData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationValue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduEvaluationValue(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluation_values', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduEvaluationValue_data = response.responseText;

			eval(eduEvaluationValue_data);

			eduEvaluationValueSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Evaluation Value search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduEvaluationValueName(value){
	var conditions = '\'EduEvaluationValue.name LIKE\' => \'%' + value + '%\'';
	store_eduEvaluationValues.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduEvaluationValueData() {
	store_eduEvaluationValues.reload();
}


if(center_panel.find('id', 'eduEvaluationValue-tab') != "") {
	var p = center_panel.findById('eduEvaluationValue-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Evaluation Values'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduEvaluationValue-tab',
		xtype: 'grid',
		store: store_eduEvaluationValues,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
			{header: "<?php __('Value Group'); ?>", dataIndex: 'evaluation_value_group', sortable: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Evaluation Values" : "Evaluation Value"]})'
        }),
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Evaluation Values</b><br />Click here to create a new Evaluation Value'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduEvaluationValue();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduEvaluationValue',
					tooltip:'<?php __('<b>Edit Evaluation Value</b><br />Click here to modify the selected Evaluation Value'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduEvaluationValue(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduEvaluationValue',
					tooltip:'<?php __('<b>Delete Evaluation Value(s)</b><br />Click here to remove the selected Evaluation Value(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Evaluation Value'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduEvaluationValue(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Evaluation Value'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Evaluation Values'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduEvaluationValue(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduEvaluationValue_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduEvaluationValueName(Ext.getCmp('eduEvaluationValue_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduEvaluationValue_go_button',
					handler: function(){
						SearchByEduEvaluationValueName(Ext.getCmp('eduEvaluationValue_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduEvaluationValue();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduEvaluationValues,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduEvaluationValue').enable();
		p.getTopToolbar().findById('delete-eduEvaluationValue').enable();
		p.getTopToolbar().findById('view-eduEvaluationValue').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluationValue').disable();
			p.getTopToolbar().findById('view-eduEvaluationValue').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluationValue').disable();
			p.getTopToolbar().findById('view-eduEvaluationValue').disable();
			p.getTopToolbar().findById('delete-eduEvaluationValue').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduEvaluationValue').enable();
			p.getTopToolbar().findById('view-eduEvaluationValue').enable();
			p.getTopToolbar().findById('delete-eduEvaluationValue').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduEvaluationValue').disable();
			p.getTopToolbar().findById('view-eduEvaluationValue').disable();
			p.getTopToolbar().findById('delete-eduEvaluationValue').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduEvaluationValues.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
