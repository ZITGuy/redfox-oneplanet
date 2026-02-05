
var store_gradeRules = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','type','created_date'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRules', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'type'
});


function AddGradeRule() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRules', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var gradeRule_data = response.responseText;
			
			eval(gradeRule_data);
			
			GradeRuleAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRule add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditGradeRule(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRules', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var gradeRule_data = response.responseText;
			
			eval(gradeRule_data);
			
			GradeRuleEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRule edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewGradeRule(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'gradeRules', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var gradeRule_data = response.responseText;

            eval(gradeRule_data);

            GradeRuleViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRule view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentAssessments(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'assessments', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_assessments_data = response.responseText;

            eval(parent_assessments_data);

            parentAssessmentsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewParentGradeRuleValues(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_gradeRuleValues_data = response.responseText;

            eval(parent_gradeRuleValues_data);

            parentGradeRuleValuesViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteGradeRule(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRules', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('GradeRule successfully deleted!'); ?>');
			RefreshGradeRuleData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the gradeRule add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchGradeRule(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'gradeRules', 'action' => 'search')); ?>',
		success: function(response, opts){
			var gradeRule_data = response.responseText;

			eval(gradeRule_data);

			gradeRuleSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the gradeRule search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByGradeRuleName(value){
	var conditions = '\'GradeRule.name LIKE\' => \'%' + value + '%\'';
	store_gradeRules.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshGradeRuleData() {
	store_gradeRules.reload();
}


if(center_panel.find('id', 'gradeRule-tab') != "") {
	var p = center_panel.findById('gradeRule-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Grade Rules'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'gradeRule-tab',
		xtype: 'grid',
		store: store_gradeRules,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Type'); ?>", dataIndex: 'type', sortable: true},
			{header: "<?php __('Created Date'); ?>", dataIndex: 'created_date', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "GradeRules" : "GradeRule"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewGradeRule(Ext.getCmp('gradeRule-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add GradeRules</b><br />Click here to create a new GradeRule'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddGradeRule();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-gradeRule',
					tooltip:'<?php __('<b>Edit GradeRules</b><br />Click here to modify the selected GradeRule'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditGradeRule(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-gradeRule',
					tooltip:'<?php __('<b>Delete GradeRules(s)</b><br />Click here to remove the selected GradeRule(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove GradeRule'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteGradeRule(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove GradeRule'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected GradeRules'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteGradeRule(sel_ids);
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
					text: '<?php __('View GradeRule'); ?>',
					id: 'view-gradeRule',
					tooltip:'<?php __('<b>View GradeRule</b><br />Click here to see details of the selected GradeRule'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewGradeRule(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Assessments'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentAssessments(sel.data.id);
								};
							}
						}
,{
							text: '<?php __('View Grade Rule Values'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentGradeRuleValues(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'gradeRule_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByGradeRuleName(Ext.getCmp('gradeRule_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'gradeRule_go_button',
					handler: function(){
						SearchByGradeRuleName(Ext.getCmp('gradeRule_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchGradeRule();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_gradeRules,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-gradeRule').enable();
		p.getTopToolbar().findById('delete-gradeRule').enable();
		p.getTopToolbar().findById('view-gradeRule').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-gradeRule').disable();
			p.getTopToolbar().findById('view-gradeRule').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-gradeRule').disable();
			p.getTopToolbar().findById('view-gradeRule').disable();
			p.getTopToolbar().findById('delete-gradeRule').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-gradeRule').enable();
			p.getTopToolbar().findById('view-gradeRule').enable();
			p.getTopToolbar().findById('delete-gradeRule').enable();
		}
		else{
			p.getTopToolbar().findById('edit-gradeRule').disable();
			p.getTopToolbar().findById('view-gradeRule').disable();
			p.getTopToolbar().findById('delete-gradeRule').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_gradeRules.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
