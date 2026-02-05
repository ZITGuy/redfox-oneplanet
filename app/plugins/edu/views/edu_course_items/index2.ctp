//<script>
var store_parent_eduCourseItems = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description','edu_course','max_mark','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_course_items', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduCourseItem() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_course_items', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduCourseItem_data = response.responseText;
			
			eval(parent_eduCourseItem_data);
			
			EduCourseItemAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Course Item add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduCourseItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_course_items', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduCourseItem_data = response.responseText;
			
			eval(parent_eduCourseItem_data);
			
			EduCourseItemEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Course Item edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduCourseItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_course_items', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduCourseItem_data = response.responseText;

			eval(eduCourseItem_data);

			EduCourseItemViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Course Item view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduCourseItem(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_course_items', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Course Item(s) successfully deleted!'); ?>');
			RefreshParentEduCourseItemData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Course Item to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduCourseItemName(value){
	var conditions = '\'EduCourseItem.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduCourseItems.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduCourseItemData() {
	store_parent_eduCourseItems.reload();
}


var g = new Ext.grid.GridPanel({
	title: '<?php __('Course Items'); ?>',
	store: store_parent_eduCourseItems,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduCourseItemGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
		{header: "<?php __('Course'); ?>", dataIndex: 'edu_course', sortable: true},
		{header: "<?php __('Max Mark'); ?>", dataIndex: 'max_mark', sortable: true},
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
            ViewEduCourseItem(Ext.getCmp('eduCourseItemGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Course Item</b><br />Click here to create a new Course Item'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduCourseItem();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduCourseItem',
				tooltip:'<?php __('<b>Edit Course Item</b><br />Click here to modify the selected Course Item'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduCourseItem(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduCourseItem',
				tooltip:'<?php __('<b>Delete Course Item(s)</b><br />Click here to remove the selected Course Item(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Course Item'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeleteParentEduCourseItem(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Course Item'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Course Item'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteParentEduCourseItem(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					}
				}
			}, ' ','-',' ', {
				xtype: 'tbsplit',
				text: '<?php __('View Course Item'); ?>',
				id: 'view-eduCourseItem2',
				tooltip:'<?php __('<b>View Course Item</b><br />Click here to see details of the selected Course Item'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduCourseItem(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduCourseItem_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduCourseItemName(Ext.getCmp('parent_eduCourseItem_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduCourseItem_go_button',
				handler: function(){
					SearchByParentEduCourseItemName(Ext.getCmp('parent_eduCourseItem_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduCourseItems,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduCourseItem').enable();
	g.getTopToolbar().findById('delete-parent-eduCourseItem').enable();
    g.getTopToolbar().findById('view-eduCourseItem2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduCourseItem').disable();
        g.getTopToolbar().findById('view-eduCourseItem2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduCourseItem').disable();
		g.getTopToolbar().findById('delete-parent-eduCourseItem').enable();
        g.getTopToolbar().findById('view-eduCourseItem2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduCourseItem').enable();
		g.getTopToolbar().findById('delete-parent-eduCourseItem').enable();
        g.getTopToolbar().findById('view-eduCourseItem2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduCourseItem').disable();
		g.getTopToolbar().findById('delete-parent-eduCourseItem').disable();
        g.getTopToolbar().findById('view-eduCourseItem2').disable();
	}
});



var parentEduCourseItemsViewWindow = new Ext.Window({
	title: 'Course Item Under the selected Course',
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
			parentEduCourseItemsViewWindow.close();
		}
	}]
});

store_parent_eduCourseItems.load({
    params: {
        start: 0,    
        limit: list_size
    }
});