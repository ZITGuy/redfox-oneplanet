
var store_eduExtraPayments = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_extra_payment_setting','edu_student','is_paid','date_paid','paid_amount','cheque_number','cheque_amount','invoice','transaction_ref','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'edu_extra_payment_setting_id', direction: "ASC"},
	groupField: 'edu_student_id'
});


function AddEduExtraPayment() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var eduExtraPayment_data = response.responseText;
			
			eval(eduExtraPayment_data);
			
			EduExtraPaymentAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduExtraPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var eduExtraPayment_data = response.responseText;
			
			eval(eduExtraPayment_data);
			
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

function DeleteEduExtraPayment(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('EduExtraPayment successfully deleted!'); ?>');
			RefreshEduExtraPaymentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the eduExtraPayment add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduExtraPayment(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduExtraPayment_data = response.responseText;

			eval(eduExtraPayment_data);

			eduExtraPaymentSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduExtraPayment search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduExtraPaymentName(value){
	var conditions = '\'EduExtraPayment.name LIKE\' => \'%' + value + '%\'';
	store_eduExtraPayments.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduExtraPaymentData() {
	store_eduExtraPayments.reload();
}


if(center_panel.find('id', 'eduExtraPayment-tab') != "") {
	var p = center_panel.findById('eduExtraPayment-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Extra Payments'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduExtraPayment-tab',
		xtype: 'grid',
		store: store_eduExtraPayments,
		columns: [
			{header: "<?php __('EduExtraPaymentSetting'); ?>", dataIndex: 'edu_extra_payment_setting', sortable: true},
			{header: "<?php __('EduStudent'); ?>", dataIndex: 'edu_student', sortable: true},
			{header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true},
			{header: "<?php __('Date Paid'); ?>", dataIndex: 'date_paid', sortable: true},
			{header: "<?php __('Paid Amount'); ?>", dataIndex: 'paid_amount', sortable: true},
			{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true},
			{header: "<?php __('Cheque Amount'); ?>", dataIndex: 'cheque_amount', sortable: true},
			{header: "<?php __('Invoice'); ?>", dataIndex: 'invoice', sortable: true},
			{header: "<?php __('Transaction Ref'); ?>", dataIndex: 'transaction_ref', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "EduExtraPayments" : "EduExtraPayment"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduExtraPayment(Ext.getCmp('eduExtraPayment-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add EduExtraPayments</b><br />Click here to create a new EduExtraPayment'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduExtraPayment();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-eduExtraPayment',
					tooltip:'<?php __('<b>Edit EduExtraPayments</b><br />Click here to modify the selected EduExtraPayment'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduExtraPayment(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-eduExtraPayment',
					tooltip:'<?php __('<b>Delete EduExtraPayments(s)</b><br />Click here to remove the selected EduExtraPayment(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove EduExtraPayment'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduExtraPayment(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove EduExtraPayment'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected EduExtraPayments'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduExtraPayment(sel_ids);
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
					text: '<?php __('View EduExtraPayment'); ?>',
					id: 'view-eduExtraPayment',
					tooltip:'<?php __('<b>View EduExtraPayment</b><br />Click here to see details of the selected EduExtraPayment'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduExtraPayment(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduExtraPaymentSetting'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($eduextrapaymentsettings as $item){if($st) echo ",
							";?>['<?php echo $item['EduExtraPaymentSetting']['id']; ?>' ,'<?php echo $item['EduExtraPaymentSetting']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduExtraPayments.reload({
								params: {
									start: 0,
									limit: list_size,
									eduextrapaymentsetting_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eduExtraPayment_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduExtraPaymentName(Ext.getCmp('eduExtraPayment_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduExtraPayment_go_button',
					handler: function(){
						SearchByEduExtraPaymentName(Ext.getCmp('eduExtraPayment_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduExtraPayment();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduExtraPayments,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-eduExtraPayment').enable();
		p.getTopToolbar().findById('delete-eduExtraPayment').enable();
		p.getTopToolbar().findById('view-eduExtraPayment').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduExtraPayment').disable();
			p.getTopToolbar().findById('view-eduExtraPayment').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-eduExtraPayment').disable();
			p.getTopToolbar().findById('view-eduExtraPayment').disable();
			p.getTopToolbar().findById('delete-eduExtraPayment').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-eduExtraPayment').enable();
			p.getTopToolbar().findById('view-eduExtraPayment').enable();
			p.getTopToolbar().findById('delete-eduExtraPayment').enable();
		}
		else{
			p.getTopToolbar().findById('edit-eduExtraPayment').disable();
			p.getTopToolbar().findById('view-eduExtraPayment').disable();
			p.getTopToolbar().findById('delete-eduExtraPayment').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduExtraPayments.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
