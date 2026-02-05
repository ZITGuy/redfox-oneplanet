//<script>
var store_eduEvaluationCategories = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'name', 'evaluation_value_group', {name: 'list_order', type: 'int'}
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationCategories', 'action' => 'list_data')); ?>'
	}),	sortInfo:{field: 'list_order', direction: "ASC"},
});


function AddEduEvaluationCategory() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationCategories', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduEvaluationCategory_data = response.responseText;
			
			eval(eduEvaluationCategory_data);
			
			EduEvaluationCategoryAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationCategory add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduEvaluationCategory(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationCategories', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduEvaluationCategory_data = response.responseText;
			
			eval(eduEvaluationCategory_data);
			
			EduEvaluationCategoryEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationCategory edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduEvaluationCategory(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationCategories', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var eduEvaluationCategory_data = response.responseText;

            eval(eduEvaluationCategory_data);

            EduEvaluationCategoryViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationCategory view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentEduEvaluationAreas(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_eduEvaluationAreas_data = response.responseText;

            eval(parent_eduEvaluationAreas_data);

            parentEduEvaluationAreasViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteEduEvaluationCategory(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationCategories', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduEvaluationCategory successfully deleted!'); ?>');
			RefreshEduEvaluationCategoryData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduEvaluationCategory add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduEvaluationCategory(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationCategories', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduEvaluationCategory_data = response.responseText;

			eval(eduEvaluationCategory_data);

			eduEvaluationCategorySearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduEvaluationCategory search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduEvaluationCategoryName(value){
	var conditions = '\'EduEvaluationCategory.name LIKE\' => \'%' + value + '%\'';
	store_eduEvaluationCategories.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduEvaluationCategoryData() {
	store_eduEvaluationCategories.reload();
}


if(center_panel.find('id', 'eduEvaluationCategory-tab') != "") {
	var p = center_panel.findById('eduEvaluationCategory-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Eval. Categories'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduEvaluationCategory-tab',
		xtype: 'grid',
		store: store_eduEvaluationCategories,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Value Group'); ?>", dataIndex: 'evaluation_value_group', sortable: true},
			{header: "<?php __('List Order'); ?>", dataIndex: 'list_order', sortable: true}
		],
		viewConfig: {
            forceFit:true
        },
		listeners: {
			celldblclick: function(){
				ViewEduEvaluationCategory(Ext.getCmp('eduEvaluationCategory-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduEvaluationCategories</b><br />Click here to create a new EduEvaluationCategory'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduEvaluationCategory();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduEvaluationCategory',
					tooltip:'<?php __('<b>Edit EduEvaluationCategories</b><br />Click here to modify the selected EduEvaluationCategory'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduEvaluationCategory(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduEvaluationCategory',
					tooltip:'<?php __('<b>Delete EduEvaluationCategories(s)</b><br />Click here to remove the selected EduEvaluationCategory(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduEvaluationCategory'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduEvaluationCategory(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduEvaluationCategory'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduEvaluationCategories'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduEvaluationCategory(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Manage Evaluation Areas'); ?>',
					id: 'view-eduEvaluationCategory',
					tooltip:'<?php __('<b>Evaluation Areas Management</b><br />Click here to manage areas of the selected Evaluation Category'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewParentEduEvaluationAreas(sel.data.id);
						}
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduEvaluationCategory_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduEvaluationCategoryName(Ext.getCmp('eduEvaluationCategory_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduEvaluationCategory_go_button',
					handler: function(){
						SearchByEduEvaluationCategoryName(Ext.getCmp('eduEvaluationCategory_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduEvaluationCategory();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduEvaluationCategories,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduEvaluationCategory').enable();
		p.getTopToolbar().findById('delete-eduEvaluationCategory').enable();
		p.getTopToolbar().findById('view-eduEvaluationCategory').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluationCategory').disable();
			p.getTopToolbar().findById('view-eduEvaluationCategory').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduEvaluationCategory').disable();
			p.getTopToolbar().findById('view-eduEvaluationCategory').disable();
			p.getTopToolbar().findById('delete-eduEvaluationCategory').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduEvaluationCategory').enable();
			p.getTopToolbar().findById('view-eduEvaluationCategory').enable();
			p.getTopToolbar().findById('delete-eduEvaluationCategory').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduEvaluationCategory').disable();
			p.getTopToolbar().findById('view-eduEvaluationCategory').disable();
			p.getTopToolbar().findById('delete-eduEvaluationCategory').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduEvaluationCategories.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
