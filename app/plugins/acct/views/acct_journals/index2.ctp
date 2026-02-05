//<script>
var store_parent_acctJournals = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','acct_transaction','acct_account','dr','cr','bbf','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentAcctJournal() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_acctJournal_data = response.responseText;
			
			eval(parent_acctJournal_data);
			
			AcctJournalAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctJournal add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentAcctJournal(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_acctJournal_data = response.responseText;
			
			eval(parent_acctJournal_data);
			
			AcctJournalEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctJournal edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAcctJournal(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var acctJournal_data = response.responseText;

			eval(acctJournal_data);

			AcctJournalViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctJournal view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentAcctJournal(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('AcctJournal(s) successfully deleted!'); ?>');
			RefreshParentAcctJournalData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the acctJournal to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentAcctJournalName(value){
	var conditions = '\'AcctJournal.name LIKE\' => \'%' + value + '%\'';
	store_parent_acctJournals.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentAcctJournalData() {
	store_parent_acctJournals.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('AcctJournals'); ?>',
	store: store_parent_acctJournals,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'acctJournalGrid',
	columns: [
		{header:"<?php __('acct_transaction'); ?>", dataIndex: 'acct_transaction', sortable: true},
		{header:"<?php __('acct_account'); ?>", dataIndex: 'acct_account', sortable: true},
		{header: "<?php __('Dr'); ?>", dataIndex: 'dr', sortable: true},
		{header: "<?php __('Cr'); ?>", dataIndex: 'cr', sortable: true},
		{header: "<?php __('Bbf'); ?>", dataIndex: 'bbf', sortable: true},
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
            ViewAcctJournal(Ext.getCmp('acctJournalGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add AcctJournal</b><br />Click here to create a new AcctJournal'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentAcctJournal();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-acctJournal',
				tooltip:'<?php __('<b>Edit AcctJournal</b><br />Click here to modify the selected AcctJournal'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentAcctJournal(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-acctJournal',
				tooltip:'<?php __('<b>Delete AcctJournal(s)</b><br />Click here to remove the selected AcctJournal(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove AcctJournal'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentAcctJournal(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove AcctJournal'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected AcctJournal'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentAcctJournal(sel_ids);
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
				text: '<?php __('View AcctJournal'); ?>',
				id: 'view-acctJournal2',
				tooltip:'<?php __('<b>View AcctJournal</b><br />Click here to see details of the selected AcctJournal'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewAcctJournal(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_acctJournal_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentAcctJournalName(Ext.getCmp('parent_acctJournal_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_acctJournal_go_button',
				handler: function(){
					SearchByParentAcctJournalName(Ext.getCmp('parent_acctJournal_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_acctJournals,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-acctJournal').enable();
	g.getTopToolbar().findById('delete-parent-acctJournal').enable();
        g.getTopToolbar().findById('view-acctJournal2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-acctJournal').disable();
                g.getTopToolbar().findById('view-acctJournal2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-acctJournal').disable();
		g.getTopToolbar().findById('delete-parent-acctJournal').enable();
                g.getTopToolbar().findById('view-acctJournal2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-acctJournal').enable();
		g.getTopToolbar().findById('delete-parent-acctJournal').enable();
                g.getTopToolbar().findById('view-acctJournal2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-acctJournal').disable();
		g.getTopToolbar().findById('delete-parent-acctJournal').disable();
                g.getTopToolbar().findById('view-acctJournal2').disable();
	}
});



var parentAcctJournalsViewWindow = new Ext.Window({
	title: 'AcctJournal Under the selected Item',
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
			parentAcctJournalsViewWindow.close();
		}
	}]
});

store_parent_acctJournals.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
