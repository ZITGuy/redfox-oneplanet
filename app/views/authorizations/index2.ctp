var store_parent_authorizations = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','command_script','maker','authorizer','status','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentAuthorization() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_authorization_data = response.responseText;
			
			eval(parent_authorization_data);
			
			AuthorizationAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the authorization add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentAuthorization(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_authorization_data = response.responseText;
			
			eval(parent_authorization_data);
			
			AuthorizationEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the authorization edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAuthorization(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var authorization_data = response.responseText;

			eval(authorization_data);

			AuthorizationViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the authorization view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentAuthorization(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'authorizations', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Authorization(s) successfully deleted!'); ?>');
			RefreshParentAuthorizationData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the authorization to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentAuthorizationName(value){
	var conditions = '\'Authorization.name LIKE\' => \'%' + value + '%\'';
	store_parent_authorizations.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentAuthorizationData() {
	store_parent_authorizations.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Authorizations'); ?>',
	store: store_parent_authorizations,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'authorizationGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Command Script'); ?>", dataIndex: 'command_script', sortable: true},
		{header:"<?php __('maker'); ?>", dataIndex: 'maker', sortable: true},
		{header:"<?php __('authorizer'); ?>", dataIndex: 'authorizer', sortable: true},
		{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
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
            ViewAuthorization(Ext.getCmp('authorizationGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Authorization</b><br />Click here to create a new Authorization'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentAuthorization();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-authorization',
				tooltip:'<?php __('<b>Edit Authorization</b><br />Click here to modify the selected Authorization'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentAuthorization(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-authorization',
				tooltip:'<?php __('<b>Delete Authorization(s)</b><br />Click here to remove the selected Authorization(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Authorization'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentAuthorization(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Authorization'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Authorization'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentAuthorization(sel_ids);
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
				text: '<?php __('View Authorization'); ?>',
				id: 'view-authorization2',
				tooltip:'<?php __('<b>View Authorization</b><br />Click here to see details of the selected Authorization'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewAuthorization(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_authorization_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentAuthorizationName(Ext.getCmp('parent_authorization_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_authorization_go_button',
				handler: function(){
					SearchByParentAuthorizationName(Ext.getCmp('parent_authorization_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_authorizations,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-authorization').enable();
	g.getTopToolbar().findById('delete-parent-authorization').enable();
        g.getTopToolbar().findById('view-authorization2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-authorization').disable();
                g.getTopToolbar().findById('view-authorization2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-authorization').disable();
		g.getTopToolbar().findById('delete-parent-authorization').enable();
                g.getTopToolbar().findById('view-authorization2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-authorization').enable();
		g.getTopToolbar().findById('delete-parent-authorization').enable();
                g.getTopToolbar().findById('view-authorization2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-authorization').disable();
		g.getTopToolbar().findById('delete-parent-authorization').disable();
                g.getTopToolbar().findById('view-authorization2').disable();
	}
});



var parentAuthorizationsViewWindow = new Ext.Window({
	title: 'Authorization Under the selected Item',
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
			parentAuthorizationsViewWindow.close();
		}
	}]
});

store_parent_authorizations.load({
    params: {
        start: 0,    
        limit: list_size
    }
});