var store_parent_eduReceipts = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','invoice_number','invoice_date','crm_number','parent_name','parent_address','edu_student','student_name','student_number','student_class','student_section','student_academic_year','total_before_tax','total_after_tax','VAT','TOT','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceipts', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduReceipt() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceipts', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduReceipt_data = response.responseText;
			
			eval(parent_eduReceipt_data);
			
			EduReceiptAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduReceipt add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduReceipt(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceipts', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduReceipt_data = response.responseText;
			
			eval(parent_eduReceipt_data);
			
			EduReceiptEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduReceipt edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduReceipt(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceipts', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduReceipt_data = response.responseText;

			eval(eduReceipt_data);

			EduReceiptViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduReceipt view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduReceiptEduReceiptItems(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceiptItems', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_eduReceiptItems_data = response.responseText;

			eval(parent_eduReceiptItems_data);

			parentEduReceiptItemsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentEduReceipt(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceipts', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduReceipt(s) successfully deleted!'); ?>');
			RefreshParentEduReceiptData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduReceipt to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduReceiptName(value){
	var conditions = '\'EduReceipt.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduReceipts.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduReceiptData() {
	store_parent_eduReceipts.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('EduReceipts'); ?>',
	store: store_parent_eduReceipts,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduReceiptGrid',
	columns: [
		{header: "<?php __('Invoice Number'); ?>", dataIndex: 'invoice_number', sortable: true},
		{header: "<?php __('Invoice Date'); ?>", dataIndex: 'invoice_date', sortable: true},
		{header: "<?php __('Crm Number'); ?>", dataIndex: 'crm_number', sortable: true},
		{header: "<?php __('Parent Name'); ?>", dataIndex: 'parent_name', sortable: true},
		{header: "<?php __('Parent Address'); ?>", dataIndex: 'parent_address', sortable: true},
		{header:"<?php __('edu_student'); ?>", dataIndex: 'edu_student', sortable: true},
		{header: "<?php __('Student Name'); ?>", dataIndex: 'student_name', sortable: true},
		{header: "<?php __('Student Number'); ?>", dataIndex: 'student_number', sortable: true},
		{header: "<?php __('Student Class'); ?>", dataIndex: 'student_class', sortable: true},
		{header: "<?php __('Student Section'); ?>", dataIndex: 'student_section', sortable: true},
		{header: "<?php __('Student Academic Year'); ?>", dataIndex: 'student_academic_year', sortable: true},
		{header: "<?php __('Total Before Tax'); ?>", dataIndex: 'total_before_tax', sortable: true},
		{header: "<?php __('Total After Tax'); ?>", dataIndex: 'total_after_tax', sortable: true},
		{header: "<?php __('VAT'); ?>", dataIndex: 'VAT', sortable: true},
		{header: "<?php __('TOT'); ?>", dataIndex: 'TOT', sortable: true},
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
            ViewEduReceipt(Ext.getCmp('eduReceiptGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add EduReceipt</b><br />Click here to create a new EduReceipt'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduReceipt();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduReceipt',
				tooltip:'<?php __('<b>Edit EduReceipt</b><br />Click here to modify the selected EduReceipt'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduReceipt(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduReceipt',
				tooltip:'<?php __('<b>Delete EduReceipt(s)</b><br />Click here to remove the selected EduReceipt(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove EduReceipt'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentEduReceipt(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove EduReceipt'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected EduReceipt'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentEduReceipt(sel_ids);
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
				text: '<?php __('View EduReceipt'); ?>',
				id: 'view-eduReceipt2',
				tooltip:'<?php __('<b>View EduReceipt</b><br />Click here to see details of the selected EduReceipt'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduReceipt(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Edu Receipt Items'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewEduReceiptEduReceiptItems(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_eduReceipt_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentEduReceiptName(Ext.getCmp('parent_eduReceipt_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_eduReceipt_go_button',
				handler: function(){
					SearchByParentEduReceiptName(Ext.getCmp('parent_eduReceipt_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduReceipts,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduReceipt').enable();
	g.getTopToolbar().findById('delete-parent-eduReceipt').enable();
        g.getTopToolbar().findById('view-eduReceipt2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduReceipt').disable();
                g.getTopToolbar().findById('view-eduReceipt2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduReceipt').disable();
		g.getTopToolbar().findById('delete-parent-eduReceipt').enable();
                g.getTopToolbar().findById('view-eduReceipt2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduReceipt').enable();
		g.getTopToolbar().findById('delete-parent-eduReceipt').enable();
                g.getTopToolbar().findById('view-eduReceipt2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduReceipt').disable();
		g.getTopToolbar().findById('delete-parent-eduReceipt').disable();
                g.getTopToolbar().findById('view-eduReceipt2').disable();
	}
});



var parentEduReceiptsViewWindow = new Ext.Window({
	title: 'EduReceipt Under the selected Item',
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
			parentEduReceiptsViewWindow.close();
		}
	}]
});

store_parent_eduReceipts.load({
    params: {
        start: 0,    
        limit: list_size
    }
});