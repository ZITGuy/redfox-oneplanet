//<script>
var store_eduReceipts = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','reference_number','invoice_date','crm_number','parent_name','parent_address',
			'edu_student','student_name','student_number','student_class','student_section',
			'student_academic_year','total_before_tax','total_after_tax',
			'VAT','TOT','created','modified'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_receipts', 'action' => 'list_data')); ?>'
	}),	
	sortInfo:{field: 'reference_number', direction: "ASC"},
	groupField: 'reference_number'
});

var popUpWin_reg=0;

function popUpWindow(URLStr, left, top, width, height) {
	if(popUpWin_reg){
		if(!popUpWin_reg.closed) popUpWin_reg.close();
	}
	popUpWin_reg = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

function printReceipt(id) {
	url = "<?php echo $this->Html->url(array('controller' => 'edu_receipts', 'action' => 'print_receipt', 'plugin' => 'edu')); ?>/"+id;
	popUpWindow(url, 200, 200, 700, 1000);
}

function SearchEduReceipt(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceipts', 'action' => 'search')); ?>',
		success: function(response, opts){
			var eduReceipt_data = response.responseText;

			eval(eduReceipt_data);

			eduReceiptSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the eduReceipt search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchReceipt(){
	var d1 = Ext.getCmp('receipt_search_date_field1').getValue();
	var d2 = Ext.getCmp('receipt_search_date_field2').getValue();
	
	var conditions = '\'EduReceipt.invoice_date >=\' => \'' + d1.format('Y-m-d') + '\', \'EduReceipt.invoice_date <=\' => \'' + d2.format('Y-m-d') + '\'';
    
	store_eduReceipts.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduReceiptData() {
	store_eduReceipts.reload();
}


if(center_panel.find('id', 'eduReceipt-tab') != "") {
	var p = center_panel.findById('eduReceipt-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Receipts'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eduReceipt-tab',
		xtype: 'grid',
		store: store_eduReceipts,
		columns: [
			{header: "<?php __('Ref. Number'); ?>", dataIndex: 'reference_number', sortable: true},
			{header: "<?php __('Invoice Date'); ?>", dataIndex: 'invoice_date', sortable: true},
			{header: "<?php __('CRM Number'); ?>", dataIndex: 'crm_number', sortable: true, hidden: true},
			{header: "<?php __('Parent Name'); ?>", dataIndex: 'parent_name', sortable: true, hidden: true},
			{header: "<?php __('Parent Address'); ?>", dataIndex: 'parent_address', sortable: true, hidden: true},
			{header: "<?php __('Student'); ?>", dataIndex: 'edu_student', sortable: true, hidden: true},
			{header: "<?php __('Student Name'); ?>", dataIndex: 'student_name', sortable: true},
			{header: "<?php __('Student ID Number'); ?>", dataIndex: 'student_number', sortable: true},
			{header: "<?php __('Student Class'); ?>", dataIndex: 'student_class', sortable: true, hidden: true},
			{header: "<?php __('Student Section'); ?>", dataIndex: 'student_section', sortable: true, hidden: true},
			{header: "<?php __('Student Academic Year'); ?>", dataIndex: 'student_academic_year', sortable: true},
			{header: "<?php __('Total Before Tax'); ?>", dataIndex: 'total_before_tax', sortable: true, hidden: true},
			{header: "<?php __('Total After Tax'); ?>", dataIndex: 'total_after_tax', sortable: true},
			{header: "<?php __('VAT'); ?>", dataIndex: 'VAT', sortable: true, hidden: true},
			{header: "<?php __('TOT'); ?>", dataIndex: 'TOT', sortable: true, hidden: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Receipts" : "Receipt"]})'
        }),
		listeners: {
			celldblclick: function(){
				ViewEduReceipt(Ext.getCmp('eduReceipt-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Print Receipt'); ?>',
					id: 'print-eduReceipt',
					tooltip:'<?php __('<b>Print Receipt</b><br />Click here to print the selected Receipt'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							printReceipt(sel.data.id);
						};
					}
				}, ' ', '-',  '<?php __('Student'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($edu_students as $item){if($st) echo ",
							";?>['<?php echo $item['EduStudent']['id']; ?>' ,'<?php echo $item['EduStudent']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_eduReceipts.reload({
								params: {
									start: 0,
									limit: list_size,
									edu_student_id : combo.getValue()
								}
							});
						}
					}
				}, ' ', '-',  '<?php __('Invoice Date'); ?>: <?php __('From'); ?>: ', {
					xtype: 'datefield',
					fieldLabel: 'Search by Date',
					format: 'Y-m-d',
					value: '<?php echo date('Y-m-d'); ?>',
					id: 'receipt_search_date_field1'
				}, ' ', '-',  ' <?php __('To'); ?>: ', {
					xtype: 'datefield',
					fieldLabel: 'Search by Date',
					format: 'Y-m-d',
					value: '<?php echo date('Y-m-d'); ?>',
					id: 'receipt_search_date_field2'
				}, '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Receipt Number]'); ?>',
					id: 'eduReceipt_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduReceiptName(Ext.getCmp('eduReceipt_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eduReceipt_go_button',
					handler: function(){
						SearchReceipt();
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduReceipt();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eduReceipts,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('print-eduReceipt').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('print-eduReceipt').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length == 1){
			p.getTopToolbar().findById('print-eduReceipt').enable();
		}
		else{
			p.getTopToolbar().findById('print-eduReceipt').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_eduReceipts.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
