//<script>
var store_edu_class_payments = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id',{name: 'class_order', type: 'int'},'edu_class','edu_academic_year',
			'enrollment_fee','registration_fee','tuition_fee','created','modified'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_class_payments', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: 'class_order', direction: "ASC"},
	groupField: 'edu_academic_year'
});


function AddEduClassPayment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_class_payments', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var edu_class_payment_data = response.responseText;
			
			eval(edu_class_payment_data);
			
			EduClassPaymentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Payment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduClassPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_class_payments', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var edu_class_payment_data = response.responseText;
			
			eval(edu_class_payment_data);
			
			EduClassPaymentEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Payment edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteEduClassPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_class_payments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Class Payment successfully deleted!'); ?>');
			RefreshEduClassPaymentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Class Payment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function RefreshEduClassPaymentData() {
	store_edu_class_payments.reload();
}


if(center_panel.find('id', 'edu_class_payment_tab') != "") {
	var p = center_panel.findById('edu_class_payment_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Class Payments'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'edu_class_payment_tab',
		xtype: 'grid',
		store: store_edu_class_payments,
		columns: [
			{header: "<?php __('Order'); ?>", dataIndex: 'class_order', sortable: true},
			{header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
			{header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true},
			{header: "<?php __('Enrollment Fee'); ?>", dataIndex: 'enrollment_fee', sortable: true},
			{header: "<?php __('Registration Fee'); ?>", dataIndex: 'registration_fee', sortable: true},
			{header: "<?php __('Tuition Fee'); ?>", dataIndex: 'tuition_fee', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Class Payments" : "Class Payment"]})'
        }),
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({	
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Class Payments</b><br />Click here to create a new Class Payment'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduClassPayment();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_edu_class_payment',
					tooltip:'<?php __('<b>Edit Class Payments</b><br />Click here to modify the selected Class Payment'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduClassPayment(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_edu_class_payment',
					tooltip:'<?php __('<b>Delete Class Payment(s)</b><br />Click here to remove the selected Class Payment(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Class Payment'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduClassPayment(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Class Payment'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Class Payments'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduClassPayment(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-',  '<?php __('Class'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edu_classes as $item){if($st) echo ",
							";?>['<?php echo $item['EduClass']['id']; ?>' ,'<?php echo $item['EduClass']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_edu_class_payments.reload({
								params: {
									start: 0,
									limit: list_size,
									edu_class_id : combo.getValue()
								}
							});
						}
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_edu_class_payments,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_edu_class_payment').enable();
		p.getTopToolbar().findById('delete_edu_class_payment').enable();
		p.getTopToolbar().findById('view_edu_class_payment').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_class_payment').disable();
			p.getTopToolbar().findById('view_edu_class_payment').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_class_payment').disable();
			p.getTopToolbar().findById('view_edu_class_payment').disable();
			p.getTopToolbar().findById('delete_edu_class_payment').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_edu_class_payment').enable();
			p.getTopToolbar().findById('view_edu_class_payment').enable();
			p.getTopToolbar().findById('delete_edu_class_payment').enable();
		}
		else{
			p.getTopToolbar().findById('edit_edu_class_payment').disable();
			p.getTopToolbar().findById('view_edu_class_payment').disable();
			p.getTopToolbar().findById('delete_edu_class_payment').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_edu_class_payments.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
