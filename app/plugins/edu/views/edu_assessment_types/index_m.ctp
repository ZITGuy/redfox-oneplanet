//<script>
var store_assessmentTypes = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_types', 'action' => 'list_data')); ?>'
	})
});


function AddAssessmentType() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_types', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var assessmentType_data = response.responseText;
			
			eval(assessmentType_data);
			
			AssessmentTypeAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessment Type add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditAssessmentType(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_types', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var assessmentType_data = response.responseText;
			
			eval(assessmentType_data);
			
			AssessmentTypeEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessment Type edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteAssessmentType(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_types', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('AssessmentType successfully deleted!'); ?>');
			RefreshAssessmentTypeData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessmentType add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchAssessmentType(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_types', 'action' => 'search')); ?>',
		success: function(response, opts){
			var assessmentType_data = response.responseText;

			eval(assessmentType_data);

			assessmentTypeSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the assessment Type search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByAssessmentTypeName(value){
	var conditions = '\'EduAssessmentType.name LIKE\' => \'%' + value + '%\'';
	store_assessmentTypes.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshAssessmentTypeData() {
	store_assessmentTypes.reload();
}


if(center_panel.find('id', 'assessmentType-tab') != "") {
	var p = center_panel.findById('assessmentType-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Assessment Types'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'assessmentType-tab',
		xtype: 'grid',
		store: store_assessmentTypes,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
		],
		viewConfig: {
			forceFit: true
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Assessment Types</b><br />Click here to create a new Assessment Type'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddAssessmentType();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-assessmentType',
					tooltip:'<?php __('<b>Edit Assessment Types</b><br />Click here to modify the selected Assessment Type'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAssessmentType(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-assessmentType',
					tooltip:'<?php __('<b>Delete Assessment Types(s)</b><br />Click here to remove the selected Assessment Type(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Assessment Type'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteAssessmentType(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Assessment Type'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Assessment Types'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteAssessmentType(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'assessmentType_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAssessmentTypeName(Ext.getCmp('assessmentType_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'assessmentType_go_button',
					handler: function(){
						SearchByAssessmentTypeName(Ext.getCmp('assessmentType_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchAssessmentType();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_assessmentTypes,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-assessmentType').enable();
		p.getTopToolbar().findById('delete-assessmentType').enable();
		p.getTopToolbar().findById('view-assessmentType').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-assessmentType').disable();
			p.getTopToolbar().findById('view-assessmentType').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-assessmentType').disable();
			p.getTopToolbar().findById('view-assessmentType').disable();
			p.getTopToolbar().findById('delete-assessmentType').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-assessmentType').enable();
			p.getTopToolbar().findById('view-assessmentType').enable();
			p.getTopToolbar().findById('delete-assessmentType').enable();
		}
		else{
			p.getTopToolbar().findById('edit-assessmentType').disable();
			p.getTopToolbar().findById('view-assessmentType').disable();
			p.getTopToolbar().findById('delete-assessmentType').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_assessmentTypes.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
