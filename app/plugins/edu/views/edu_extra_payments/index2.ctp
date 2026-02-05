var store_parent_eduExtraPayments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_extra_payment_setting','edu_student','is_paid','date_paid','paid_amount','cheque_number','cheque_amount','invoice','transaction_ref','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduExtraPayment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduExtraPayment_data = response.responseText;
			
			eval(parent_eduExtraPayment_data);
			
			EduExtraPaymentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduExtraPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduExtraPayment_data = response.responseText;
			
			eval(parent_eduExtraPayment_data);
			
			EduExtraPaymentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPayment edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduExtraPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduExtraPayment_data = response.responseText;

			eval(eduExtraPayment_data);

			EduExtraPaymentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPayment view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduExtraPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduExtraPayment(s) successfully deleted!'); ?>');
			RefreshParentEduExtraPaymentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPayment to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduExtraPaymentName(value){
	var conditions = '\'EduExtraPayment.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduExtraPayments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduExtraPaymentData() {
	store_parent_eduExtraPayments.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduExtraPayments'); ?>',
	store: store_parent_eduExtraPayments,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduExtraPaymentGrid',
	columns: [
		{header:"<?php __('edu_extra_payment_setting'); ?>", dataIndex: 'edu_extra_payment_setting', sortable: true},
		{header:"<?php __('edu_student'); ?>", dataIndex: 'edu_student', sortable: true},
		{header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true},
		{header: "<?php __('Date Paid'); ?>", dataIndex: 'date_paid', sortable: true},
		{header: "<?php __('Paid Amount'); ?>", dataIndex: 'paid_amount', sortable: true},
		{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true},
		{header: "<?php __('Cheque Amount'); ?>", dataIndex: 'cheque_amount', sortable: true},
		{header: "<?php __('Invoice'); ?>", dataIndex: 'invoice', sortable: true},
		{header: "<?php __('Transaction Ref'); ?>", dataIndex: 'transaction_ref', sortable: true},
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
            ViewEduExtraPayment(Ext.getCmp('eduExtraPaymentGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduExtraPayment</b><br />Click here to create a new EduExtraPayment'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduExtraPayment();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduExtraPayment',
				tooltip:'<?php __('<b>Edit EduExtraPayment</b><br />Click here to modify the selected EduExtraPayment'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduExtraPayment(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduExtraPayment',
				tooltip:'<?php __('<b>Delete EduExtraPayment(s)</b><br />Click here to remove the selected EduExtraPayment(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduExtraPayment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduExtraPayment(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduExtraPayment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduExtraPayment'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduExtraPayment(sel_ids);
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
				text: '<?php __('View EduExtraPayment'); ?>',
				id: 'view-eduExtraPayment2',
				tooltip:'<?php __('<b>View EduExtraPayment</b><br />Click here to see details of the selected EduExtraPayment'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduExtraPayment(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduExtraPayment_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduExtraPaymentName(Ext.getCmp('parent_eduExtraPayment_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduExtraPayment_go_button',
				handler: function(){
					SearchByParentEduExtraPaymentName(Ext.getCmp('parent_eduExtraPayment_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduExtraPayments,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduExtraPayment').enable();
	g.getTopToolbar().findById('delete-parent-eduExtraPayment').enable();
        g.getTopToolbar().findById('view-eduExtraPayment2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduExtraPayment').disable();
                g.getTopToolbar().findById('view-eduExtraPayment2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduExtraPayment').disable();
		g.getTopToolbar().findById('delete-parent-eduExtraPayment').enable();
                g.getTopToolbar().findById('view-eduExtraPayment2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduExtraPayment').enable();
		g.getTopToolbar().findById('delete-parent-eduExtraPayment').enable();
                g.getTopToolbar().findById('view-eduExtraPayment2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduExtraPayment').disable();
		g.getTopToolbar().findById('delete-parent-eduExtraPayment').disable();
                g.getTopToolbar().findById('view-eduExtraPayment2').disable();
	}
});



var parentEduExtraPaymentsViewWindow = new Ext.Window({
	title: 'EduExtraPayment Under the selected Item',
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
			parentEduExtraPaymentsViewWindow.close();
		}
	}]
});

store_parent_eduExtraPayments.load({
    params: {
        start: 0,    
        limit: list_size
    }
});