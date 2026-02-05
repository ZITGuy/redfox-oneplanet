
var store_eduReceipt_eduReceiptItems = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','amount','edu_receipt'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduReceiptItems', 'action' => 'list_data', $eduReceipt['EduReceipt']['id'])); ?>'	})
});
		
<?php $eduReceipt_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Invoice Number', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['invoice_number'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Invoice Date', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['invoice_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Crm Number', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['crm_number'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Parent Name', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['parent_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Parent Address', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['parent_address'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Student', true) . ":</th><td><b>" . $eduReceipt['EduStudent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Student Name', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['student_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Student Number', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['student_number'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Student Class', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['student_class'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Student Section', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['student_section'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Student Academic Year', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['student_academic_year'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Total Before Tax', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['total_before_tax'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Total After Tax', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['total_after_tax'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('VAT', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['VAT'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('TOT', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['TOT'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduReceipt['EduReceipt']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduReceipt_view_panel_1 = {
			html : '<?php echo $eduReceipt_html; ?>',
			frame : true,
			height: 80
		}
		var eduReceipt_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_eduReceipt_eduReceiptItems,
				title: '<?php __('EduReceiptItems'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduReceipt_eduReceiptItems.getCount() == '')
							store_eduReceipt_eduReceiptItems.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true}
,					{header: "<?php __('Edu Receipt'); ?>", dataIndex: 'edu_receipt', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduReceipt_eduReceiptItems,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduReceiptViewWindow = new Ext.Window({
			title: '<?php __('View EduReceipt'); ?>: <?php echo $eduReceipt['EduReceipt']['id']; ?>',
			width: 500,
			height:345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				eduReceipt_view_panel_1,
				eduReceipt_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduReceiptViewWindow.close();
				}
			}]
		});
