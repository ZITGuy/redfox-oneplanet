//<script>
var store_parent_eduEvaluationAreas = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_evaluation_category','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduEvaluationArea() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduEvaluationArea_data = response.responseText;
			
			eval(parent_eduEvaluationArea_data);
			
			EduEvaluationAreaAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduEvaluationArea(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduEvaluationArea_data = response.responseText;
			
			eval(parent_eduEvaluationArea_data);
			
			EduEvaluationAreaEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduEvaluationArea(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduEvaluationArea_data = response.responseText;

			eval(eduEvaluationArea_data);

			EduEvaluationAreaViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area view form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteParentEduEvaluationArea(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduEvaluationArea(s) successfully deleted!'); ?>');
			RefreshParentEduEvaluationAreaData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation Area to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduEvaluationAreaName(value){
	var conditions = '\'EduEvaluationArea.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduEvaluationAreas.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduEvaluationAreaData() {
	store_parent_eduEvaluationAreas.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Evaluation Areas'); ?>',
	store: store_parent_eduEvaluationAreas,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduEvaluationAreaGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Category'); ?>", dataIndex: 'edu_evaluation_category', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduEvaluationArea(Ext.getCmp('eduEvaluationAreaGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Evaluation Area</b><br />Click here to create a new Evaluation Area'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduEvaluationArea();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduEvaluationArea',
				tooltip:'<?php __('<b>Edit Evaluation Area</b><br />Click here to modify the selected Evaluation Area'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduEvaluationArea(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduEvaluationArea',
				tooltip:'<?php __('<b>Delete Evaluation Area(s)</b><br />Click here to remove the selected Evaluation Area(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Evaluation Area'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduEvaluationArea(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Evaluation Area'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Evaluation Area'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteParentEduEvaluationArea(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduEvaluationArea_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduEvaluationAreaName(Ext.getCmp('parent_eduEvaluationArea_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduEvaluationArea_go_button',
				handler: function(){
					SearchByParentEduEvaluationAreaName(Ext.getCmp('parent_eduEvaluationArea_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduEvaluationAreas,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduEvaluationArea').enable();
	g.getTopToolbar().findById('delete-parent-eduEvaluationArea').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduEvaluationArea').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduEvaluationArea').disable();
		g.getTopToolbar().findById('delete-parent-eduEvaluationArea').enable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduEvaluationArea').enable();
		g.getTopToolbar().findById('delete-parent-eduEvaluationArea').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduEvaluationArea').disable();
		g.getTopToolbar().findById('delete-parent-eduEvaluationArea').disable();
	}
});



var parentEduEvaluationAreasViewWindow = new Ext.Window({
	title: 'Evaluation Areas of <i><?php echo $category['EduEvaluationCategory']['name']; ?> Category</i>',
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
			parentEduEvaluationAreasViewWindow.close();
		}
	}]
});

store_parent_eduEvaluationAreas.load({
    params: {
        start: 0,    
        limit: list_size
    }
});