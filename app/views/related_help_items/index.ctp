//<script>
var store_related_help_items = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','help_item','related_help_item'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: '', direction: "ASC"},
	groupField: 'help_item'

});


function AddRelatedHelpItem() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var related_help_item_data = response.responseText;
			
			eval(related_help_item_data);
			
			RelatedHelpItemAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Related Help Item add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditRelatedHelpItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var related_help_item_data = response.responseText;
			
			eval(related_help_item_data);
			
			RelatedHelpItemEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Related Help Item edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewRelatedHelpItem(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var related_help_item_data = response.responseText;

            eval(related_help_item_data);

            RelatedHelpItemViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Related Help Item view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentRelatedHelpItems(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_related_help_items_data = response.responseText;

            eval(parent_related_help_items_data);

            parentRelatedHelpItemsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the relatedHelpItems view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteRelatedHelpItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Related Help Item successfully deleted!'); ?>');
			RefreshRelatedHelpItemData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Related Help Item add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchRelatedHelpItem(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'related_help_items', 'action' => 'search')); ?>',
		success: function(response, opts){
			var related_help_item_data = response.responseText;

			eval(related_help_item_data);

			relatedHelpItemSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Related Help Item search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByRelatedHelpItemName(value){
	var conditions = '\'RelatedHelpItem.name LIKE\' => \'%' + value + '%\'';
	store_related_help_items.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshRelatedHelpItemData() {
	store_related_help_items.reload();
}


if(center_panel.find('id', 'related_help_item_tab') != "") {
	var p = center_panel.findById('related_help_item_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Related Help Items'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'related_help_item_tab',
		xtype: 'grid',
		store: store_related_help_items,
		columns: [
			{header: "<?php __('HelpItem'); ?>", dataIndex: 'help_item', sortable: true},
			{header: "<?php __('RelatedHelpItem'); ?>", dataIndex: 'related_help_item', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Related Help Items" : "Related Help Item"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewRelatedHelpItem(Ext.getCmp('related_help_item_tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Related Help Items</b><br />Click here to create a new Related Help Item'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddRelatedHelpItem();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_related_help_item',
					tooltip:'<?php __('<b>Edit Related Help Items</b><br />Click here to modify the selected Related Help Item'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditRelatedHelpItem(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_related_help_item',
					tooltip:'<?php __('<b>Delete Related Help Item(s)</b><br />Click here to remove the selected Related Help Item(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Related Help Item'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteRelatedHelpItem(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Related Help Item'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Related Help Items'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteRelatedHelpItem(sel_ids);
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
					text: '<?php __('View Related Help Item'); ?>',
					id: 'view_related_help_item',
					tooltip:'<?php __('<b>View Related Help Item</b><br />Click here to see details of the selected Related Help Item'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
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
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentRelatedHelpItems(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '<?php __('HelpItem'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($helpitems as $item){if($st) echo ",
							";?>['<?php echo $item['HelpItem']['id']; ?>' ,'<?php echo $item['HelpItem']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_related_help_items.reload({
								params: {
									start: 0,
									limit: list_size,
									helpitem_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'related_help_item_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByRelatedHelpItemName(Ext.getCmp('related_help_item_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'related_help_item_go_button',
					handler: function(){
						SearchByRelatedHelpItemName(Ext.getCmp('related_help_item_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchRelatedHelpItem();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_related_help_items,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_related_help_item').enable();
		p.getTopToolbar().findById('delete_related_help_item').enable();
		p.getTopToolbar().findById('view_related_help_item').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_related_help_item').disable();
			p.getTopToolbar().findById('view_related_help_item').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_related_help_item').disable();
			p.getTopToolbar().findById('view_related_help_item').disable();
			p.getTopToolbar().findById('delete_related_help_item').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_related_help_item').enable();
			p.getTopToolbar().findById('view_related_help_item').enable();
			p.getTopToolbar().findById('delete_related_help_item').enable();
		}
		else{
			p.getTopToolbar().findById('edit_related_help_item').disable();
			p.getTopToolbar().findById('view_related_help_item').disable();
			p.getTopToolbar().findById('delete_related_help_item').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_related_help_items.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
