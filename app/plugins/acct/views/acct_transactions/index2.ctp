//<script>
var store_parent_acctTransactions = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','description','cheque_number','invoice_number','transaction_date','acct_fiscal_year','user','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentAcctTransaction() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_acctTransaction_data = response.responseText;
			
			eval(parent_acctTransaction_data);
			
			AcctTransactionAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctTransaction add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentAcctTransaction(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_acctTransaction_data = response.responseText;
			
			eval(parent_acctTransaction_data);
			
			AcctTransactionEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctTransaction edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAcctTransaction(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var acctTransaction_data = response.responseText;

			eval(acctTransaction_data);

			AcctTransactionViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctTransaction view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAcctTransactionAcctJournals(id) {
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


function DeleteParentAcctTransaction(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('AcctTransaction(s) successfully deleted!'); ?>');
			RefreshParentAcctTransactionData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctTransaction to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentAcctTransactionName(value){
	var conditions = '\'AcctTransaction.name LIKE\' => \'%' + value + '%\'';
	store_parent_acctTransactions.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentAcctTransactionData() {
	store_parent_acctTransactions.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('AcctTransactions'); ?>',
	store: store_parent_acctTransactions,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'acctTransactionGrid',
	columns: [
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Description'); ?>", dataIndex: 'description', sortable: true},
		{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true},
		{header: "<?php __('Invoice Number'); ?>", dataIndex: 'invoice_number', sortable: true},
		{header: "<?php __('Transaction Date'); ?>", dataIndex: 'transaction_date', sortable: true},
		{header:"<?php __('acct_fiscal_year'); ?>", dataIndex: 'acct_fiscal_year', sortable: true},
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
            ViewAcctTransaction(Ext.getCmp('acctTransactionGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add AcctTransaction</b><br />Click here to create a new AcctTransaction'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentAcctTransaction();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-acctTransaction',
				tooltip:'<?php __('<b>Edit AcctTransaction</b><br />Click here to modify the selected AcctTransaction'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentAcctTransaction(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-acctTransaction',
				tooltip:'<?php __('<b>Delete AcctTransaction(s)</b><br />Click here to remove the selected AcctTransaction(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove AcctTransaction'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentAcctTransaction(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove AcctTransaction'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected AcctTransaction'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentAcctTransaction(sel_ids);
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
				text: '<?php __('View AcctTransaction'); ?>',
				id: 'view-acctTransaction2',
				tooltip:'<?php __('<b>View AcctTransaction</b><br />Click here to see details of the selected AcctTransaction'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewAcctTransaction(sel.data.id);
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
								ViewAcctTransactionAcctJournals(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_acctTransaction_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentAcctTransactionName(Ext.getCmp('parent_acctTransaction_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_acctTransaction_go_button',
				handler: function(){
					SearchByParentAcctTransactionName(Ext.getCmp('parent_acctTransaction_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_acctTransactions,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-acctTransaction').enable();
	g.getTopToolbar().findById('delete-parent-acctTransaction').enable();
        g.getTopToolbar().findById('view-acctTransaction2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-acctTransaction').disable();
                g.getTopToolbar().findById('view-acctTransaction2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-acctTransaction').disable();
		g.getTopToolbar().findById('delete-parent-acctTransaction').enable();
                g.getTopToolbar().findById('view-acctTransaction2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-acctTransaction').enable();
		g.getTopToolbar().findById('delete-parent-acctTransaction').enable();
                g.getTopToolbar().findById('view-acctTransaction2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-acctTransaction').disable();
		g.getTopToolbar().findById('delete-parent-acctTransaction').disable();
                g.getTopToolbar().findById('view-acctTransaction2').disable();
	}
});



var parentAcctTransactionsViewWindow = new Ext.Window({
	title: 'AcctTransaction Under the selected Item',
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
			parentAcctTransactionsViewWindow.close();
		}
	}]
});

store_parent_acctTransactions.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
