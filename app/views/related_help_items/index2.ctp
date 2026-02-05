var store_parent_relatedHelpItems = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','help_item','related_help_item'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentRelatedHelpItem() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_relatedHelpItem_data = response.responseText;
			
			eval(parent_relatedHelpItem_data);
			
			RelatedHelpItemAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the relatedHelpItem add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentRelatedHelpItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_relatedHelpItem_data = response.responseText;
			
			eval(parent_relatedHelpItem_data);
			
			RelatedHelpItemEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the relatedHelpItem edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewRelatedHelpItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var relatedHelpItem_data = response.responseText;

			eval(relatedHelpItem_data);

			RelatedHelpItemViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the relatedHelpItem view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewRelatedHelpItemRelatedHelpItems(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_relatedHelpItems_data = response.responseText;

			eval(parent_relatedHelpItems_data);

			parentRelatedHelpItemsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentRelatedHelpItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('RelatedHelpItem(s) successfully deleted!'); ?>');
			RefreshParentRelatedHelpItemData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the relatedHelpItem to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentRelatedHelpItemName(value){
	var conditions = '\'RelatedHelpItem.name LIKE\' => \'%' + value + '%\'';
	store_parent_relatedHelpItems.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentRelatedHelpItemData() {
	store_parent_relatedHelpItems.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('RelatedHelpItems'); ?>',
	store: store_parent_relatedHelpItems,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'relatedHelpItemGrid',
	columns: [
		{header:"<?php __('help_item'); ?>", dataIndex: 'help_item', sortable: true},
		{header:"<?php __('related_help_item'); ?>", dataIndex: 'related_help_item', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewRelatedHelpItem(Ext.getCmp('relatedHelpItemGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add RelatedHelpItem</b><br />Click here to create a new RelatedHelpItem'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentRelatedHelpItem();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-relatedHelpItem',
				tooltip:'<?php __('<b>Edit RelatedHelpItem</b><br />Click here to modify the selected RelatedHelpItem'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentRelatedHelpItem(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-relatedHelpItem',
				tooltip:'<?php __('<b>Delete RelatedHelpItem(s)</b><br />Click here to remove the selected RelatedHelpItem(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove RelatedHelpItem'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentRelatedHelpItem(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove RelatedHelpItem'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected RelatedHelpItem'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentRelatedHelpItem(sel_ids);
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
				text: '<?php __('View RelatedHelpItem'); ?>',
				id: 'view-relatedHelpItem2',
				tooltip:'<?php __('<b>View RelatedHelpItem</b><br />Click here to see details of the selected RelatedHelpItem'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewRelatedHelpItem(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Related Help Items'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewRelatedHelpItemRelatedHelpItems(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_relatedHelpItem_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentRelatedHelpItemName(Ext.getCmp('parent_relatedHelpItem_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_relatedHelpItem_go_button',
				handler: function(){
					SearchByParentRelatedHelpItemName(Ext.getCmp('parent_relatedHelpItem_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_relatedHelpItems,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-relatedHelpItem').enable();
	g.getTopToolbar().findById('delete-parent-relatedHelpItem').enable();
        g.getTopToolbar().findById('view-relatedHelpItem2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-relatedHelpItem').disable();
                g.getTopToolbar().findById('view-relatedHelpItem2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-relatedHelpItem').disable();
		g.getTopToolbar().findById('delete-parent-relatedHelpItem').enable();
                g.getTopToolbar().findById('view-relatedHelpItem2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-relatedHelpItem').enable();
		g.getTopToolbar().findById('delete-parent-relatedHelpItem').enable();
                g.getTopToolbar().findById('view-relatedHelpItem2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-relatedHelpItem').disable();
		g.getTopToolbar().findById('delete-parent-relatedHelpItem').disable();
                g.getTopToolbar().findById('view-relatedHelpItem2').disable();
	}
});



var parentRelatedHelpItemsViewWindow = new Ext.Window({
	title: 'RelatedHelpItem Under the selected Item',
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
			parentRelatedHelpItemsViewWindow.close();
		}
	}]
});

store_parent_relatedHelpItems.load({
    params: {
        start: 0,    
        limit: list_size
    }
});