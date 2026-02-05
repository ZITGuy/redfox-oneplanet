
var store_eduPayments = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_payment_schedule','edu_student','is_paid','date_paid','paid_amount','cheque_number','invoice','transaction_ref','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_payment_schedule_id', direction: "ASC"},
	groupField: 'edu_student_id'
});


function AddEduPayment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduPayment_data = response.responseText;
			
			eval(eduPayment_data);
			
			EduPaymentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduPayment_data = response.responseText;
			
			eval(eduPayment_data);
			
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

function DeleteEduPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduPayment successfully deleted!'); ?>');
			RefreshEduPaymentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduPayment(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduPayment_data = response.responseText;

			eval(eduPayment_data);

			eduPaymentSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduPayment search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduPaymentName(value){
	var conditions = '\'EduPayment.name LIKE\' => \'%' + value + '%\'';
	store_eduPayments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduPaymentData() {
	store_eduPayments.reload();
}


if(center_panel.find('id', 'eduPayment-tab') != "") {
	var p = center_panel.findById('eduPayment-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Payments'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduPayment-tab',
		xtype: 'grid',
		store: store_eduPayments,
		columns: [
			{header: "<?php __('EduPaymentSchedule'); ?>", dataIndex: 'edu_payment_schedule', sortable: true},
			{header: "<?php __('EduStudent'); ?>", dataIndex: 'edu_student', sortable: true},
			{header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true},
			{header: "<?php __('Date Paid'); ?>", dataIndex: 'date_paid', sortable: true},
			{header: "<?php __('Paid Amount'); ?>", dataIndex: 'paid_amount', sortable: true},
			{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true},
			{header: "<?php __('Invoice'); ?>", dataIndex: 'invoice', sortable: true},
			{header: "<?php __('Transaction Ref'); ?>", dataIndex: 'transaction_ref', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduPayments" : "EduPayment"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduPayment(Ext.getCmp('eduPayment-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduPayments</b><br />Click here to create a new EduPayment'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduPayment();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduPayment',
					tooltip:'<?php __('<b>Edit EduPayments</b><br />Click here to modify the selected EduPayment'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduPayment(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduPayment',
					tooltip:'<?php __('<b>Delete EduPayments(s)</b><br />Click here to remove the selected EduPayment(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduPayment'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduPayment(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduPayment'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduPayments'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduPayment(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: '<?php __('View EduPayment'); ?>',
					id: 'view-eduPayment',
					tooltip:'<?php __('<b>View EduPayment</b><br />Click here to see details of the selected EduPayment'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduPayment(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduPaymentSchedule'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edupaymentschedules as $item){if($st) echo ",
							";?>['<?php echo $item['EduPaymentSchedule']['id']; ?>' ,'<?php echo $item['EduPaymentSchedule']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduPayments.reload({
								params: {
									start: 0,
									limit: list_size,
									edupaymentschedule_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduPayment_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduPaymentName(Ext.getCmp('eduPayment_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduPayment_go_button',
					handler: function(){
						SearchByEduPaymentName(Ext.getCmp('eduPayment_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduPayment();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduPayments,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduPayment').enable();
		p.getTopToolbar().findById('delete-eduPayment').enable();
		p.getTopToolbar().findById('view-eduPayment').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduPayment').disable();
			p.getTopToolbar().findById('view-eduPayment').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduPayment').disable();
			p.getTopToolbar().findById('view-eduPayment').disable();
			p.getTopToolbar().findById('delete-eduPayment').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduPayment').enable();
			p.getTopToolbar().findById('view-eduPayment').enable();
			p.getTopToolbar().findById('delete-eduPayment').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduPayment').disable();
			p.getTopToolbar().findById('view-eduPayment').disable();
			p.getTopToolbar().findById('delete-eduPayment').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduPayments.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
