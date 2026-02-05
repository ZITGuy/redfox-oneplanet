//<script>
var store_acctFiscalYears = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','start_date','end_date','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: "<?php echo $this->Html->url(array('controller' => 'acct_fiscal_years', 'action' => 'list_data', 'plugin'=>'acct')); ?>"
	})
        ,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'start_date'
});


function AddAcctFiscalYear() {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'acct_fiscal_years', 'action' => 'add', 'plugin'=>'acct')); ?>",
		success: function(response, opts) {
			var acctFiscalYear_data = response.responseText;
			
			eval(acctFiscalYear_data);
			
			AcctFiscalYearAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctFiscalYear add form. Error code'); ?>: " + response.status);
		}
	});
}

function EditAcctFiscalYear(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'acct_fiscal_years', 'action' => 'edit', 'plugin'=>'acct')); ?>/"+id,
		success: function(response, opts) {
			var acctFiscalYear_data = response.responseText;
			
			eval(acctFiscalYear_data);
			
			AcctFiscalYearEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctFiscalYear edit form. Error code'); ?>: " + response.status);
		}
	});
}

function ViewAcctFiscalYear(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'acct_fiscal_years', 'action' => 'view', 'plugin'=>'acct')); ?>/"+id,
        success: function(response, opts) {
            var acctFiscalYear_data = response.responseText;

            eval(acctFiscalYear_data);

            AcctFiscalYearViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctFiscalYear view form. Error code'); ?>: " + response.status);
        }
    });
}
function ViewParentAcctTransactions(id) {
    Ext.Ajax.request({
        url: "<?php echo $this->Html->url(array('controller' => 'acct_transactions', 'action' => 'index2', 'plugin'=>'acct')); ?>/"+id,
        success: function(response, opts) {
            var parent_acctTransactions_data = response.responseText;

            eval(parent_acctTransactions_data);

            parentAcctTransactionsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert("<?php __('Error'); ?><?php __('Cannot get the campus view form. Error code'); ?>: " + response.status);
        }
    });
}

function DeleteAcctFiscalYear(id) {
	Ext.Ajax.request({
		url: "<?php echo $this->Html->url(array('controller' => 'acct_fiscal_years', 'action' => 'delete', 'plugin'=>'acct')); ?>/"+id,
		success: function(response, opts) {
			Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Fiscal Year successfully deleted!'); ?>");
			RefreshAcctFiscalYearData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctFiscalYear add form. Error code'); ?>: " + response.status);
		}
	});
}

    function SearchAcctFiscalYear(){
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'acct_fiscal_years', 'action' => 'search', 'plugin'=>'acct')); ?>',
            success: function(response, opts){
                var acctFiscalYear_data = response.responseText;

                eval(acctFiscalYear_data);

                acctFiscalYearSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the acctFiscalYear search form. Error Code'); ?>: " + response.status);
            }
	});
    }

    function SearchByAcctFiscalYearName(value){
	var conditions = '\'AcctFiscalYear.name LIKE\' => \'%' + value + '%\'';
	store_acctFiscalYears.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
	    }
	});
    }

    function RefreshAcctFiscalYearData() {
	store_acctFiscalYears.reload();
    }


    if(center_panel.find('id', 'acctFiscalYear-tab') != "") {
	var p = center_panel.findById('acctFiscalYear-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add({
		title: '<?php __('Acct Fiscal Years'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'acctFiscalYear-tab',
		xtype: 'grid',
		store: store_acctFiscalYears,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
			{header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
                    forceFit:true,
                    groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "AcctFiscalYears" : "AcctFiscalYear"]})'
                }),
		listeners: {
                    celldblclick: function(){
                        ViewAcctFiscalYear(Ext.getCmp('acctFiscalYear-tab').getSelectionModel().getSelected().data.id);
                    }
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: "<?php __('Add'); ?>",
					tooltip: "<?php __('<b>Add AcctFiscalYears</b><br />Click here to create a new AcctFiscalYear'); ?>",
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddAcctFiscalYear();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Edit'); ?>",
					id: 'edit-acctFiscalYear',
					tooltip: "<?php __('<b>Edit AcctFiscalYears</b><br />Click here to modify the selected AcctFiscalYear'); ?>",
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAcctFiscalYear(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: "<?php __('Delete'); ?>",
					id: 'delete-acctFiscalYear',
					tooltip: "<?php __('<b>Delete Fiscal Years</b><br />Click here to remove the selected AcctFiscalYear(s)'); ?>",
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: "<?php __('Remove FiscalYear'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove'); ?> "+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteAcctFiscalYear(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: "<?php __('Remove AcctFiscalYear'); ?>",
									buttons: Ext.MessageBox.YESNO,
									msg: "<?php __('Remove the selected AcctFiscalYears'); ?>?",
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteAcctFiscalYear(sel_ids);
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
					text: "<?php __('View AcctFiscalYear'); ?>",
					id: 'view-acctFiscalYear',
					tooltip: "<?php __('<b>View AcctFiscalYear</b><br />Click here to see details of the selected AcctFiscalYear'); ?>",
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewAcctFiscalYear(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Acct Transactions'); ?>',
                                                        icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentAcctTransactions(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: "<?php __('[Search By Name]'); ?>",
					id: 'acctFiscalYear_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAcctFiscalYearName(Ext.getCmp('acctFiscalYear_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('GO'); ?>",
                                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
					id: "acctFiscalYear_go_button",
					handler: function(){
						SearchByAcctFiscalYearName(Ext.getCmp('acctFiscalYear_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: "<?php __('Advanced Search'); ?>",
                                        tooltip:"<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
					handler: function(){
						SearchAcctFiscalYear();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_acctFiscalYears,
			displayInfo: true,
			displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
			beforePageText: "<?php __('Page'); ?>",
			afterPageText: "<?php __('of {0}'); ?>",
			emptyMsg: "<?php __('No data to display'); ?>"
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-acctFiscalYear').enable();
		p.getTopToolbar().findById('delete-acctFiscalYear').enable();
		p.getTopToolbar().findById('view-acctFiscalYear').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-acctFiscalYear').disable();
			p.getTopToolbar().findById('view-acctFiscalYear').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-acctFiscalYear').disable();
			p.getTopToolbar().findById('view-acctFiscalYear').disable();
			p.getTopToolbar().findById('delete-acctFiscalYear').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-acctFiscalYear').enable();
			p.getTopToolbar().findById('view-acctFiscalYear').enable();
			p.getTopToolbar().findById('delete-acctFiscalYear').enable();
		}
		else{
			p.getTopToolbar().findById('edit-acctFiscalYear').disable();
			p.getTopToolbar().findById('view-acctFiscalYear').disable();
			p.getTopToolbar().findById('delete-acctFiscalYear').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_acctFiscalYears.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}