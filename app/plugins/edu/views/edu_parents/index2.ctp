//<script>
var store_parent_eduParents = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','user','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduParent() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduParent_data = response.responseText;
			
			eval(parent_eduParent_data);
			
			EduParentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduParent add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduParent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduParent_data = response.responseText;
			
			eval(parent_eduParent_data);
			
			EduParentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduParent edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduParent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduParent_data = response.responseText;

			eval(eduParent_data);

			EduParentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduParent view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduParentEduStudents(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduStudents', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduStudents_data = response.responseText;

			eval(parent_eduStudents_data);

			parentEduStudentsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteParentEduParent(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduParent(s) successfully deleted!'); ?>');
			RefreshParentEduParentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduParent to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduParentName(value){
	var conditions = '\'EduParent.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduParents.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduParentData() {
	store_parent_eduParents.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('EduParents'); ?>',
	store: store_parent_eduParents,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header:"<?php __('user'); ?>", dataIndex: 'user', sortable: true},
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
            ViewEduParent(Ext.getCmp('eduParentGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduParent</b><br />Click here to create a new EduParent'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduParent();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduParent',
				tooltip:'<?php __('<b>Edit EduParent</b><br />Click here to modify the selected EduParent'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduParent(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduParent',
				tooltip:'<?php __('<b>Delete EduParent(s)</b><br />Click here to remove the selected EduParent(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduParent'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduParent(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduParent'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduParent'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduParent(sel_ids);
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
				text: '<?php __('View EduParent'); ?>',
				id: 'view-eduParent2',
				tooltip:'<?php __('<b>View EduParent</b><br />Click here to see details of the selected EduParent'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduParent(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Edu Students'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewEduParentEduStudents(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduParent_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduParentName(Ext.getCmp('parent_eduParent_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduParent_go_button',
				handler: function(){
					SearchByParentEduParentName(Ext.getCmp('parent_eduParent_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduParents,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduParent').enable();
	g.getTopToolbar().findById('delete-parent-eduParent').enable();
        g.getTopToolbar().findById('view-eduParent2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduParent').disable();
                g.getTopToolbar().findById('view-eduParent2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduParent').disable();
		g.getTopToolbar().findById('delete-parent-eduParent').enable();
                g.getTopToolbar().findById('view-eduParent2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduParent').enable();
		g.getTopToolbar().findById('delete-parent-eduParent').enable();
                g.getTopToolbar().findById('view-eduParent2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduParent').disable();
		g.getTopToolbar().findById('delete-parent-eduParent').disable();
                g.getTopToolbar().findById('view-eduParent2').disable();
	}
});



var parentEduParentsViewWindow = new Ext.Window({
	title: 'EduParent Under the selected Item',
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
			parentEduParentsViewWindow.close();
		}
	}]
});

store_parent_eduParents.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
