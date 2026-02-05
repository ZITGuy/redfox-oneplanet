var store_parent_gradeRuleValues = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','min','max','code','grade_rule'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentGradeRuleValue() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_gradeRuleValue_data = response.responseText;
			
			eval(parent_gradeRuleValue_data);
			
			GradeRuleValueAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRuleValue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentGradeRuleValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_gradeRuleValue_data = response.responseText;
			
			eval(parent_gradeRuleValue_data);
			
			GradeRuleValueEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRuleValue edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewGradeRuleValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var gradeRuleValue_data = response.responseText;

			eval(gradeRuleValue_data);

			GradeRuleValueViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRuleValue view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentGradeRuleValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('GradeRuleValue(s) successfully deleted!'); ?>');
			RefreshParentGradeRuleValueData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRuleValue to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentGradeRuleValueName(value){
	var conditions = '\'GradeRuleValue.name LIKE\' => \'%' + value + '%\'';
	store_parent_gradeRuleValues.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentGradeRuleValueData() {
	store_parent_gradeRuleValues.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('GradeRuleValues'); ?>',
	store: store_parent_gradeRuleValues,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'gradeRuleValueGrid',
	columns: [
		{header: "<?php __('Min'); ?>", dataIndex: 'min', sortable: true},
		{header: "<?php __('Max'); ?>", dataIndex: 'max', sortable: true},
		{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
		{header:"<?php __('grade_rule'); ?>", dataIndex: 'grade_rule', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewGradeRuleValue(Ext.getCmp('gradeRuleValueGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add GradeRuleValue</b><br />Click here to create a new GradeRuleValue'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentGradeRuleValue();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-gradeRuleValue',
				tooltip:'<?php __('<b>Edit GradeRuleValue</b><br />Click here to modify the selected GradeRuleValue'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentGradeRuleValue(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-gradeRuleValue',
				tooltip:'<?php __('<b>Delete GradeRuleValue(s)</b><br />Click here to remove the selected GradeRuleValue(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove GradeRuleValue'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentGradeRuleValue(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove GradeRuleValue'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected GradeRuleValue'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentGradeRuleValue(sel_ids);
											}
									}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', {
				xtype: 'tbsplit',
				text: '<?php __('View GradeRuleValue'); ?>',
				id: 'view-gradeRuleValue2',
				tooltip:'<?php __('<b>View GradeRuleValue</b><br />Click here to see details of the selected GradeRuleValue'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewGradeRuleValue(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_gradeRuleValue_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentGradeRuleValueName(Ext.getCmp('parent_gradeRuleValue_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_gradeRuleValue_go_button',
				handler: function(){
					SearchByParentGradeRuleValueName(Ext.getCmp('parent_gradeRuleValue_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_gradeRuleValues,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-gradeRuleValue').enable();
	g.getTopToolbar().findById('delete-parent-gradeRuleValue').enable();
        g.getTopToolbar().findById('view-gradeRuleValue2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-gradeRuleValue').disable();
                g.getTopToolbar().findById('view-gradeRuleValue2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-gradeRuleValue').disable();
		g.getTopToolbar().findById('delete-parent-gradeRuleValue').enable();
                g.getTopToolbar().findById('view-gradeRuleValue2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-gradeRuleValue').enable();
		g.getTopToolbar().findById('delete-parent-gradeRuleValue').enable();
                g.getTopToolbar().findById('view-gradeRuleValue2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-gradeRuleValue').disable();
		g.getTopToolbar().findById('delete-parent-gradeRuleValue').disable();
                g.getTopToolbar().findById('view-gradeRuleValue2').disable();
	}
});



var parentGradeRuleValuesViewWindow = new Ext.Window({
	title: 'GradeRuleValue Under the selected Item',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
		g
	],

	buttons: [{
		text: 'Close',
		handler: function(btn){
			parentGradeRuleValuesViewWindow.close();
		}
	}]
});

store_parent_gradeRuleValues.load({
    params: {
        start: 0,    
        limit: list_size
    }
});