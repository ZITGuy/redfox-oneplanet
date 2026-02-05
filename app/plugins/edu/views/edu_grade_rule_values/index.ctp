
var store_gradeRuleValues = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','min','max','code','grade_rule'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'min', direction: "ASC"},
	groupField: 'max'
});


function AddGradeRuleValue() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var gradeRuleValue_data = response.responseText;
			
			eval(gradeRuleValue_data);
			
			GradeRuleValueAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRuleValue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditGradeRuleValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var gradeRuleValue_data = response.responseText;
			
			eval(gradeRuleValue_data);
			
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

function DeleteGradeRuleValue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('GradeRuleValue successfully deleted!'); ?>');
			RefreshGradeRuleValueData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRuleValue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchGradeRuleValue(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'search')); ?>',
		success: function(response, opts){
			var gradeRuleValue_data = response.responseText;

			eval(gradeRuleValue_data);

			gradeRuleValueSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the gradeRuleValue search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByGradeRuleValueName(value){
	var conditions = '\'GradeRuleValue.name LIKE\' => \'%' + value + '%\'';
	store_gradeRuleValues.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshGradeRuleValueData() {
	store_gradeRuleValues.reload();
}


if(center_panel.find('id', 'gradeRuleValue-tab') != "") {
	var p = center_panel.findById('gradeRuleValue-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Grade Rule Values'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'gradeRuleValue-tab',
		xtype: 'grid',
		store: store_gradeRuleValues,
		columns: [
			{header: "<?php __('Min'); ?>", dataIndex: 'min', sortable: true},
			{header: "<?php __('Max'); ?>", dataIndex: 'max', sortable: true},
			{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
			{header: "<?php __('GradeRule'); ?>", dataIndex: 'grade_rule', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "GradeRuleValues" : "GradeRuleValue"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewGradeRuleValue(Ext.getCmp('gradeRuleValue-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add GradeRuleValues</b><br />Click here to create a new GradeRuleValue'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddGradeRuleValue();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-gradeRuleValue',
					tooltip:'<?php __('<b>Edit GradeRuleValues</b><br />Click here to modify the selected GradeRuleValue'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditGradeRuleValue(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-gradeRuleValue',
					tooltip:'<?php __('<b>Delete GradeRuleValues(s)</b><br />Click here to remove the selected GradeRuleValue(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove GradeRuleValue'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteGradeRuleValue(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove GradeRuleValue'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected GradeRuleValues'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteGradeRuleValue(sel_ids);
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
					text: '<?php __('View GradeRuleValue'); ?>',
					id: 'view-gradeRuleValue',
					tooltip:'<?php __('<b>View GradeRuleValue</b><br />Click here to see details of the selected GradeRuleValue'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewGradeRuleValue(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('GradeRule'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($graderules as $item){if($st) echo ",
							";?>['<?php echo $item['GradeRule']['id']; ?>' ,'<?php echo $item['GradeRule']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_gradeRuleValues.reload({
								params: {
									start: 0,
									limit: list_size,
									graderule_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'gradeRuleValue_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByGradeRuleValueName(Ext.getCmp('gradeRuleValue_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'gradeRuleValue_go_button',
					handler: function(){
						SearchByGradeRuleValueName(Ext.getCmp('gradeRuleValue_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchGradeRuleValue();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_gradeRuleValues,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-gradeRuleValue').enable();
		p.getTopToolbar().findById('delete-gradeRuleValue').enable();
		p.getTopToolbar().findById('view-gradeRuleValue').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-gradeRuleValue').disable();
			p.getTopToolbar().findById('view-gradeRuleValue').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-gradeRuleValue').disable();
			p.getTopToolbar().findById('view-gradeRuleValue').disable();
			p.getTopToolbar().findById('delete-gradeRuleValue').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-gradeRuleValue').enable();
			p.getTopToolbar().findById('view-gradeRuleValue').enable();
			p.getTopToolbar().findById('delete-gradeRuleValue').enable();
		}
		else{
			p.getTopToolbar().findById('edit-gradeRuleValue').disable();
			p.getTopToolbar().findById('view-gradeRuleValue').disable();
			p.getTopToolbar().findById('delete-gradeRuleValue').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_gradeRuleValues.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
