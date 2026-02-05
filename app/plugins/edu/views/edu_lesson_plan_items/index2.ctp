//<script>
var store_parent_eduLessonPlanItems = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_lesson_plan','edu_period','edu_day','edu_outline','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduLessonPlanItem() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduLessonPlanItem_data = response.responseText;
			
			eval(parent_eduLessonPlanItem_data);
			
			EduLessonPlanItemAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlanItem add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduLessonPlanItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduLessonPlanItem_data = response.responseText;
			
			eval(parent_eduLessonPlanItem_data);
			
			EduLessonPlanItemEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlanItem edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduLessonPlanItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduLessonPlanItem_data = response.responseText;

			eval(eduLessonPlanItem_data);

			EduLessonPlanItemViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlanItem view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduLessonPlanItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduLessonPlanItems', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduLessonPlanItem(s) successfully deleted!'); ?>');
			RefreshParentEduLessonPlanItemData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduLessonPlanItem to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduLessonPlanItemName(value){
	var conditions = '\'EduLessonPlanItem.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduLessonPlanItems.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduLessonPlanItemData() {
	store_parent_eduLessonPlanItems.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduLessonPlanItems'); ?>',
	store: store_parent_eduLessonPlanItems,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduLessonPlanItemGrid',
	columns: [
		{header:"<?php __('edu_lesson_plan'); ?>", dataIndex: 'edu_lesson_plan', sortable: true},
		{header:"<?php __('edu_period'); ?>", dataIndex: 'edu_period', sortable: true},
		{header:"<?php __('edu_day'); ?>", dataIndex: 'edu_day', sortable: true},
		{header:"<?php __('edu_outline'); ?>", dataIndex: 'edu_outline', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduLessonPlanItem(Ext.getCmp('eduLessonPlanItemGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduLessonPlanItem</b><br />Click here to create a new EduLessonPlanItem'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduLessonPlanItem();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduLessonPlanItem',
				tooltip:'<?php __('<b>Edit EduLessonPlanItem</b><br />Click here to modify the selected EduLessonPlanItem'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduLessonPlanItem(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduLessonPlanItem',
				tooltip:'<?php __('<b>Delete EduLessonPlanItem(s)</b><br />Click here to remove the selected EduLessonPlanItem(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduLessonPlanItem'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduLessonPlanItem(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduLessonPlanItem'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduLessonPlanItem'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduLessonPlanItem(sel_ids);
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
				text: '<?php __('View EduLessonPlanItem'); ?>',
				id: 'view-eduLessonPlanItem2',
				tooltip:'<?php __('<b>View EduLessonPlanItem</b><br />Click here to see details of the selected EduLessonPlanItem'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduLessonPlanItem(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduLessonPlanItem_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduLessonPlanItemName(Ext.getCmp('parent_eduLessonPlanItem_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: '
Strict Standards: Non-static method I18n::translate() should not be called statically in C:\wamp\www\smmp_new\cake\basics.php on line 655

Strict Standards: Non-static method I18n::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 130

Strict Standards: Non-static method Configure::read() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 142

Strict Standards: Non-static method Configure::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\configure.php on line 154
GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduLessonPlanItem_go_button',
				handler: function(){
					SearchByParentEduLessonPlanItemName(Ext.getCmp('parent_eduLessonPlanItem_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduLessonPlanItems,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduLessonPlanItem').enable();
	g.getTopToolbar().findById('delete-parent-eduLessonPlanItem').enable();
        g.getTopToolbar().findById('view-eduLessonPlanItem2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduLessonPlanItem').disable();
                g.getTopToolbar().findById('view-eduLessonPlanItem2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduLessonPlanItem').disable();
		g.getTopToolbar().findById('delete-parent-eduLessonPlanItem').enable();
                g.getTopToolbar().findById('view-eduLessonPlanItem2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduLessonPlanItem').enable();
		g.getTopToolbar().findById('delete-parent-eduLessonPlanItem').enable();
                g.getTopToolbar().findById('view-eduLessonPlanItem2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduLessonPlanItem').disable();
		g.getTopToolbar().findById('delete-parent-eduLessonPlanItem').disable();
                g.getTopToolbar().findById('view-eduLessonPlanItem2').disable();
	}
});



var parentEduLessonPlanItemsViewWindow = new Ext.Window({
	title: 'EduLessonPlanItem Under the selected Item',
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
		text: '
Strict Standards: Non-static method I18n::translate() should not be called statically in C:\wamp\www\smmp_new\cake\basics.php on line 655

Strict Standards: Non-static method I18n::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 130

Strict Standards: Non-static method Configure::read() should not be called statically in C:\wamp\www\smmp_new\cake\libs\i18n.php on line 142

Strict Standards: Non-static method Configure::getInstance() should not be called statically in C:\wamp\www\smmp_new\cake\libs\configure.php on line 154
Close',
		handler: function(btn){
			parentEduLessonPlanItemsViewWindow.close();
		}
	}]
});

store_parent_eduLessonPlanItems.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
