//<script>
    var store_acctTransactions = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','description','cheque_number','invoice_number',
                'transaction_date','acct_fiscal_year','user','created','modified'		
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'list_data')); ?>"
	}),	
        sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'transaction_date'
    });

    function AddAcctTransaction() {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'add')); ?>",
            success: function(response, opts) {
                var acctTransaction_data = response.responseText;

                eval(acctTransaction_data);

                AcctTransactionAddWindow.show();
            },
            failure: function(response, opts) {
                    Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctTransaction add form. Error code'); ?>: " + response.status);
            }
	});
    }
    
    function AddMultiTransaction() {
	Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acct_transactions', 'action' => 'add_multi')); ?>",
            success: function(response, opts) {
                var acctTransaction_data = response.responseText;

                eval(acctTransaction_data);

                AcctTransactionAddWindow.show();
            },
            failure: function(response, opts) {
                    Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acct Transaction add form. Error code'); ?>: " + response.status);
            }
	});
    }

    function ViewAcctTransaction(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'view')); ?>/"+id,
            success: function(response, opts) {
                var acctTransaction_data = response.responseText;

                eval(acctTransaction_data);

                AcctTransactionViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctTransaction view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchAcctTransaction(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'acctTransactions', 'action' => 'search')); ?>',
		success: function(response, opts){
			var acctTransaction_data = response.responseText;

			eval(acctTransaction_data);

			acctTransactionSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctTransaction search form. Error Code'); ?>: " + response.status);
		}
	});
    }

    function SearchByAcctTransactionName(value){
	var conditions = '\'AcctTransaction.name LIKE\' => \'%' + value + '%\'';
	store_acctTransactions.reload({
            params: {
               start: 0,
               limit: list_size,
               conditions: conditions
	    }
	});
    }

    function RefreshAcctTransactionData() {
	store_acctTransactions.reload();
    }


    if(center_panel.find('id', 'acctTransaction-tab') != "") {
	var p = center_panel.findById('acctTransaction-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add({
		title: '<?php __('Transactions'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'acctTransaction-tab',
		xtype: 'grid',
		store: store_acctTransactions,
		columns: [
			{header: "<?php __('Reference'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Narrative'); ?>", dataIndex: 'description', sortable: true},
			{header: "<?php __('Cheque #'); ?>", dataIndex: 'cheque_number', sortable: true},
			{header: "<?php __('Invoice #'); ?>", dataIndex: 'invoice_number', sortable: true},
			{header: "<?php __('Trans. Date'); ?>", dataIndex: 'transaction_date', sortable: true},
			{header: "<?php __('Fiscal Year'); ?>", dataIndex: 'acct_fiscal_year', sortable: true, hidden: true},
			{header: "<?php __('Passed By'); ?>", dataIndex: 'user', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		
		view: new Ext.grid.GroupingView({
                    forceFit:true,
                    groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "AcctTransactions" : "AcctTransaction"]})'
                }),
		listeners: {
			celldblclick: function(){
				ViewAcctTransaction(Ext.getCmp('acctTransaction-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbsplit',
					text: "<?php __('Add Entry'); ?>",
					tooltip: "<?php __('<b>Add Entry</b><br />Click here to create a new Transaction'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddAcctTransaction();
					},
                                        menu: {
                                            items: [{
                                                text: "<?php __('Add Single Entry'); ?>",
                                                icon: 'img/table_add.png',
                                                cls: 'x-btn-text-icon',
                                                handler: function(btn) {
                                                    AddAcctTransaction();
                                                }
                                            }, {
                                                text: "<?php __('Add Multi Entry'); ?>",
                                                icon: 'img/table_add.png',
                                                cls: 'x-btn-text-icon',
                                                handler: function(btn) {
                                                    AddMultiTransaction();
                                                }
                                            }
                                        ]
                                    }
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('View Transaction'); ?>",
					id: 'view-acctTransaction',
					tooltip: "<?php __('<b>View Transaction Detail</b><br />Click here to see details of the selected Transaction'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
                                            var sm = p.getSelectionModel();
                                            var sel = sm.getSelected();
                                            if (sm.hasSelection()){
                                                ViewAcctTransaction(sel.data.id);
                                            };
					}
				}, ' ', '-',  "<?php __('Fiscal Year'); ?>: ", {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($acct_fiscal_years as $item){if($st) echo ",
							";?>['<?php echo $item['AcctFiscalYear']['id']; ?>' ,'<?php echo $item['AcctFiscalYear']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_acctTransactions.reload({
								params: {
									start: 0,
									limit: list_size,
									acctfiscalyear_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Reference]'); ?>",
					id: 'acctTransaction_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAcctTransactionName(Ext.getCmp('acctTransaction_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "acctTransaction_go_button",
					handler: function(){
						SearchByAcctTransactionName(Ext.getCmp('acctTransaction_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchAcctTransaction();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_acctTransactions,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('view-acctTransaction').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('view-acctTransaction').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('view-acctTransaction').disable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('view-acctTransaction').enable();
		}
		else{
			p.getTopToolbar().findById('view-acctTransaction').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_acctTransactions.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}