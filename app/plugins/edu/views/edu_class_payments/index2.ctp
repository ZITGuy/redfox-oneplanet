var store_parent_eduClassPayments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_class','edu_academic_year','enrollment_fee','registration_fee','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduClassPayments', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduClassPayment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduClassPayments', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduClassPayment_data = response.responseText;
			
			eval(parent_eduClassPayment_data);
			
			EduClassPaymentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduClassPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduClassPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduClassPayments', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduClassPayment_data = response.responseText;
			
			eval(parent_eduClassPayment_data);
			
			EduClassPaymentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduClassPayment edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduClassPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduClassPayments', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduClassPayment_data = response.responseText;

			eval(eduClassPayment_data);

			EduClassPaymentViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduClassPayment view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduClassPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduClassPayments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduClassPayment(s) successfully deleted!'); ?>');
			RefreshParentEduClassPaymentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduClassPayment to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduClassPaymentName(value){
	var conditions = '\'EduClassPayment.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduClassPayments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduClassPaymentData() {
	store_parent_eduClassPayments.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduClassPayments'); ?>',
	store: store_parent_eduClassPayments,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduClassPaymentGrid',
	columns: [
		{header:"<?php __('edu_class'); ?>", dataIndex: 'edu_class', sortable: true},
		{header:"<?php __('edu_academic_year'); ?>", dataIndex: 'edu_academic_year', sortable: true},
		{header: "<?php __('Enrollment Fee'); ?>", dataIndex: 'enrollment_fee', sortable: true},
		{header: "<?php __('Registration Fee'); ?>", dataIndex: 'registration_fee', sortable: true},
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
            ViewEduClassPayment(Ext.getCmp('eduClassPaymentGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduClassPayment</b><br />Click here to create a new EduClassPayment'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduClassPayment();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduClassPayment',
				tooltip:'<?php __('<b>Edit EduClassPayment</b><br />Click here to modify the selected EduClassPayment'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduClassPayment(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduClassPayment',
				tooltip:'<?php __('<b>Delete EduClassPayment(s)</b><br />Click here to remove the selected EduClassPayment(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduClassPayment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduClassPayment(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduClassPayment'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduClassPayment'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduClassPayment(sel_ids);
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
				text: '<?php __('View EduClassPayment'); ?>',
				id: 'view-eduClassPayment2',
				tooltip:'<?php __('<b>View EduClassPayment</b><br />Click here to see details of the selected EduClassPayment'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduClassPayment(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduClassPayment_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduClassPaymentName(Ext.getCmp('parent_eduClassPayment_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduClassPayment_go_button',
				handler: function(){
					SearchByParentEduClassPaymentName(Ext.getCmp('parent_eduClassPayment_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduClassPayments,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduClassPayment').enable();
	g.getTopToolbar().findById('delete-parent-eduClassPayment').enable();
        g.getTopToolbar().findById('view-eduClassPayment2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduClassPayment').disable();
                g.getTopToolbar().findById('view-eduClassPayment2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduClassPayment').disable();
		g.getTopToolbar().findById('delete-parent-eduClassPayment').enable();
                g.getTopToolbar().findById('view-eduClassPayment2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduClassPayment').enable();
		g.getTopToolbar().findById('delete-parent-eduClassPayment').enable();
                g.getTopToolbar().findById('view-eduClassPayment2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduClassPayment').disable();
		g.getTopToolbar().findById('delete-parent-eduClassPayment').disable();
                g.getTopToolbar().findById('view-eduClassPayment2').disable();
	}
});



var parentEduClassPaymentsViewWindow = new Ext.Window({
	title: 'EduClassPayment Under the selected Item',
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
			parentEduClassPaymentsViewWindow.close();
		}
	}]
});

store_parent_eduClassPayments.load({
    params: {
        start: 0,    
        limit: list_size
    }
});