//<script>
var store_parent_acctAccounts = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','code','acct_category','balance','user','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentAcctAccount() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_acctAccount_data = response.responseText;
			
			eval(parent_acctAccount_data);
			
			AcctAccountAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctAccount add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentAcctAccount(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_acctAccount_data = response.responseText;
			
			eval(parent_acctAccount_data);
			
			AcctAccountEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctAccount edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAcctAccount(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var acctAccount_data = response.responseText;

			eval(acctAccount_data);

			AcctAccountViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctAccount view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAcctAccountAcctJournals(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_acctJournals_data = response.responseText;

			eval(parent_acctJournals_data);

			parentAcctJournalsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentAcctAccount(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctAccounts', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('AcctAccount(s) successfully deleted!'); ?>');
			RefreshParentAcctAccountData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctAccount to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentAcctAccountName(value){
	var conditions = '\'AcctAccount.name LIKE\' => \'%' + value + '%\'';
	store_parent_acctAccounts.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentAcctAccountData() {
	store_parent_acctAccounts.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('AcctAccounts'); ?>',
	store: store_parent_acctAccounts,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'acctAccountGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
		{header:"<?php __('acct_category'); ?>", dataIndex: 'acct_category', sortable: true},
		{header: "<?php __('Balance'); ?>", dataIndex: 'balance', sortable: true},
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
            ViewAcctAccount(Ext.getCmp('acctAccountGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add AcctAccount</b><br />Click here to create a new AcctAccount'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentAcctAccount();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-acctAccount',
				tooltip:'<?php __('<b>Edit AcctAccount</b><br />Click here to modify the selected AcctAccount'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentAcctAccount(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-acctAccount',
				tooltip:'<?php __('<b>Delete AcctAccount(s)</b><br />Click here to remove the selected AcctAccount(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove AcctAccount'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentAcctAccount(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove AcctAccount'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected AcctAccount'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentAcctAccount(sel_ids);
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
				text: '<?php __('View AcctAccount'); ?>',
				id: 'view-acctAccount2',
				tooltip:'<?php __('<b>View AcctAccount</b><br />Click here to see details of the selected AcctAccount'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewAcctAccount(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Acct Journals'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewAcctAccountAcctJournals(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_acctAccount_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentAcctAccountName(Ext.getCmp('parent_acctAccount_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_acctAccount_go_button',
				handler: function(){
					SearchByParentAcctAccountName(Ext.getCmp('parent_acctAccount_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_acctAccounts,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-acctAccount').enable();
	g.getTopToolbar().findById('delete-parent-acctAccount').enable();
        g.getTopToolbar().findById('view-acctAccount2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-acctAccount').disable();
                g.getTopToolbar().findById('view-acctAccount2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-acctAccount').disable();
		g.getTopToolbar().findById('delete-parent-acctAccount').enable();
                g.getTopToolbar().findById('view-acctAccount2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-acctAccount').enable();
		g.getTopToolbar().findById('delete-parent-acctAccount').enable();
                g.getTopToolbar().findById('view-acctAccount2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-acctAccount').disable();
		g.getTopToolbar().findById('delete-parent-acctAccount').disable();
                g.getTopToolbar().findById('view-acctAccount2').disable();
	}
});



var parentAcctAccountsViewWindow = new Ext.Window({
	title: 'AcctAccount Under the selected Item',
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
			parentAcctAccountsViewWindow.close();
		}
	}]
});

store_parent_acctAccounts.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
