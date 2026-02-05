var store_parent_eduPayments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_payment_schedule','edu_student','is_paid','date_paid','paid_amount','cheque_number','invoice','transaction_ref','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduPayment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduPayment_data = response.responseText;
			
			eval(parent_eduPayment_data);
			
			EduPaymentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduPayment_data = response.responseText;
			
			eval(parent_eduPayment_data);
			
			EduPaymentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPayment edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduPayment_data = response.responseText;

			eval(eduPayment_data);

			EduPaymentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPayment view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduPayment(s) successfully deleted!'); ?>');
			RefreshParentEduPaymentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPayment to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduPaymentName(value){
	var conditions = '\'EduPayment.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduPayments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduPaymentData() {
	store_parent_eduPayments.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduPayments'); ?>',
	store: store_parent_eduPayments,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduPaymentGrid',
	columns: [
		{header:"<?php __('edu_payment_schedule'); ?>", dataIndex: 'edu_payment_schedule', sortable: true},
		{header:"<?php __('edu_student'); ?>", dataIndex: 'edu_student', sortable: true},
		{header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true},
		{header: "<?php __('Date Paid'); ?>", dataIndex: 'date_paid', sortable: true},
		{header: "<?php __('Paid Amount'); ?>", dataIndex: 'paid_amount', sortable: true},
		{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true},
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
            ViewEduPayment(Ext.getCmp('eduPaymentGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduPayment</b><br />Click here to create a new EduPayment'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduPayment();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduPayment',
				tooltip:'<?php __('<b>Edit EduPayment</b><br />Click here to modify the selected EduPayment'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduPayment(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduPayment',
				tooltip:'<?php __('<b>Delete EduPayment(s)</b><br />Click here to remove the selected EduPayment(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduPayment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduPayment(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduPayment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduPayment'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduPayment(sel_ids);
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
				text: '<?php __('View EduPayment'); ?>',
				id: 'view-eduPayment2',
				tooltip:'<?php __('<b>View EduPayment</b><br />Click here to see details of the selected EduPayment'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduPayment(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduPayment_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduPaymentName(Ext.getCmp('parent_eduPayment_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduPayment_go_button',
				handler: function(){
					SearchByParentEduPaymentName(Ext.getCmp('parent_eduPayment_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduPayments,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduPayment').enable();
	g.getTopToolbar().findById('delete-parent-eduPayment').enable();
        g.getTopToolbar().findById('view-eduPayment2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduPayment').disable();
                g.getTopToolbar().findById('view-eduPayment2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduPayment').disable();
		g.getTopToolbar().findById('delete-parent-eduPayment').enable();
                g.getTopToolbar().findById('view-eduPayment2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduPayment').enable();
		g.getTopToolbar().findById('delete-parent-eduPayment').enable();
                g.getTopToolbar().findById('view-eduPayment2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduPayment').disable();
		g.getTopToolbar().findById('delete-parent-eduPayment').disable();
                g.getTopToolbar().findById('view-eduPayment2').disable();
	}
});



var parentEduPaymentsViewWindow = new Ext.Window({
	title: 'EduPayment Under the selected Item',
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
			parentEduPaymentsViewWindow.close();
		}
	}]
});

store_parent_eduPayments.load({
    params: {
        start: 0,    
        limit: list_size
    }
});