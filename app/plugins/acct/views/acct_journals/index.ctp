//<script>
var store_acctJournals = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','acct_transaction','acct_account','dr','cr','bbf','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'list_data')); ?>"
	})
        ,	sortInfo:{field: 'acct_transaction_id', direction: "ASC"},
	groupField: 'acct_account_id'
});


function AddAcctJournal() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'add')); ?>",
		success: function(response, opts) {
			var acctJournal_data = response.responseText;
			
			eval(acctJournal_data);
			
			AcctJournalAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctJournal add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditAcctJournal(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'edit')); ?>/"+id,
		success: function(response, opts) {
			var acctJournal_data = response.responseText;
			
			eval(acctJournal_data);
			
			AcctJournalEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctJournal edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewAcctJournal(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'view')); ?>/"+id,
        success: function(response, opts) {
            var acctJournal_data = response.responseText;

            eval(acctJournal_data);

            AcctJournalViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctJournal view form. Error code'); ?>: " + response.status);
        }
    });
}

function DeleteAcctJournal(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'delete')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('AcctJournal successfully deleted!'); ?>");
			RefreshAcctJournalData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctJournal add form. Error code'); ?>: " + response.status);
		}
	});
}

function SearchAcctJournal(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'search')); ?>',
		success: function(response, opts){
			var acctJournal_data = response.responseText;

			eval(acctJournal_data);

			acctJournalSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctJournal search form. Error Code'); ?>: " + response.status);
		}
	});
}

function SearchByAcctJournalName(value){
	var conditions = '\'AcctJournal.name LIKE\' => \'%' + value + '%\'';
	store_acctJournals.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshAcctJournalData() {
	store_acctJournals.reload();
}


if(center_panel.find('id', 'acctJournal-tab') != "") {
	var p = center_panel.findById('acctJournal-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Acct Journals'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'acctJournal-tab',
		xtype: 'grid',
		store: store_acctJournals,
		columns: [
			{header: "<?php __('AcctTransaction'); ?>", dataIndex: 'acct_transaction', sortable: true},
			{header: "<?php __('AcctAccount'); ?>", dataIndex: 'acct_account', sortable: true},
			{header: "<?php __('Dr'); ?>", dataIndex: 'dr', sortable: true},
			{header: "<?php __('Cr'); ?>", dataIndex: 'cr', sortable: true},
			{header: "<?php __('Bbf'); ?>", dataIndex: 'bbf', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "AcctJournals" : "AcctJournal"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewAcctJournal(Ext.getCmp('acctJournal-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add AcctJournals</b><br />Click here to create a new AcctJournal'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddAcctJournal();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-acctJournal',
					tooltip: "<?php __('<b>Edit AcctJournals</b><br />Click here to modify the selected AcctJournal'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAcctJournal(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-acctJournal',
					tooltip: "<?php __('<b>Delete AcctJournals(s)</b><br />Click here to remove the selected AcctJournal(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove AcctJournal'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteAcctJournal(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove AcctJournal'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected AcctJournals'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteAcctJournal(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert("<?php __('Warning'); ?>", "<?php __('Please select a record first'); ?>");
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: "<?php __('View AcctJournal'); ?>",
					id: 'view-acctJournal',
					tooltip: "<?php __('<b>View AcctJournal</b><br />Click here to see details of the selected AcctJournal'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewAcctJournal(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  "<?php __('AcctTransaction'); ?>: ", {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($accttransactions as $item){if($st) echo ",
							";?>['<?php echo $item['AcctTransaction']['id']; ?>' ,'<?php echo $item['AcctTransaction']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_acctJournals.reload({
								params: {
									start: 0,
									limit: list_size,
									accttransaction_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'acctJournal_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAcctJournalName(Ext.getCmp('acctJournal_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "acctJournal_go_button",
					handler: function(){
						SearchByAcctJournalName(Ext.getCmp('acctJournal_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchAcctJournal();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_acctJournals,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-acctJournal').enable();
		p.getTopToolbar().findById('delete-acctJournal').enable();
		p.getTopToolbar().findById('view-acctJournal').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-acctJournal').disable();
			p.getTopToolbar().findById('view-acctJournal').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-acctJournal').disable();
			p.getTopToolbar().findById('view-acctJournal').disable();
			p.getTopToolbar().findById('delete-acctJournal').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-acctJournal').enable();
			p.getTopToolbar().findById('view-acctJournal').enable();
			p.getTopToolbar().findById('delete-acctJournal').enable();
		}
		else{
			p.getTopToolbar().findById('edit-acctJournal').disable();
			p.getTopToolbar().findById('view-acctJournal').disable();
			p.getTopToolbar().findById('delete-acctJournal').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_acctJournals.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}