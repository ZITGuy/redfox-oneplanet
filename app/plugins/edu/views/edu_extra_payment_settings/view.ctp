
var store_eduExtraPaymentSetting_eduExtraPayments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_extra_payment_setting','edu_student','is_paid','date_paid','paid_amount','cheque_number','cheque_amount','invoice','transaction_ref','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduExtraPayments', 'action' => 'list_data', $eduExtraPaymentSetting['EduExtraPaymentSetting']['id'])); ?>'	})
});
		
<?php $eduExtraPaymentSetting_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduExtraPaymentSetting['EduExtraPaymentSetting']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Class', true) . ":</th><td><b>" . $eduExtraPaymentSetting['EduClass']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Amount', true) . ":</th><td><b>" . $eduExtraPaymentSetting['EduExtraPaymentSetting']['amount'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Academic Year', true) . ":</th><td><b>" . $eduExtraPaymentSetting['EduAcademicYear']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduExtraPaymentSetting_view_panel_1 = {
			html : '<?php echo $eduExtraPaymentSetting_html; ?>',
			frame : true,
			height: 80
		}
		var eduExtraPaymentSetting_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduExtraPaymentSetting_eduExtraPayments,
				title: '<?php __('EduExtraPayments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduExtraPaymentSetting_eduExtraPayments.getCount() == '')
							store_eduExtraPaymentSetting_eduExtraPayments.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Extra Payment Setting'); ?>", dataIndex: 'edu_extra_payment_setting', sortable: true}
,					{header: "<?php __('Edu Student'); ?>", dataIndex: 'edu_student', sortable: true}
,					{header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true}
,					{header: "<?php __('Date Paid'); ?>", dataIndex: 'date_paid', sortable: true}
,					{header: "<?php __('Paid Amount'); ?>", dataIndex: 'paid_amount', sortable: true}
,					{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true}
,					{header: "<?php __('Cheque Amount'); ?>", dataIndex: 'cheque_amount', sortable: true}
,					{header: "<?php __('Invoice'); ?>", dataIndex: 'invoice', sortable: true}
,					{header: "<?php __('Transaction Ref'); ?>", dataIndex: 'transaction_ref', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduExtraPaymentSetting_eduExtraPayments,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduExtraPaymentSettingViewWindow = new Ext.Window({
			title: '<?php __('View EduExtraPaymentSetting'); ?>: <?php echo $eduExtraPaymentSetting['EduExtraPaymentSetting']['name']; ?>',
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
				eduExtraPaymentSetting_view_panel_1,
				eduExtraPaymentSetting_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduExtraPaymentSettingViewWindow.close();
				}
			}]
		});
