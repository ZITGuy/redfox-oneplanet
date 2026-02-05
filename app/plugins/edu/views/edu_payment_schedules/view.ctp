//<script>
var store_eduPaymentSchedule_eduPayments = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_payment_schedule','edu_student','is_paid','date_paid',
			'paid_amount','cheque_number','invoice','transaction_ref','created','modified'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduPayments', 'action' => 'list_data', $eduPaymentSchedule['EduPaymentSchedule']['id'])); ?>'	})
});
		
<?php $eduPaymentSchedule_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Month', true) . ":</th><td><b>" . $eduPaymentSchedule['EduPaymentSchedule']['month'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Class', true) . ":</th><td><b>" . $eduPaymentSchedule['EduClass']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Amount', true) . ":</th><td><b>" . $eduPaymentSchedule['EduPaymentSchedule']['amount'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduPaymentSchedule_view_panel_1 = {
			html : '<?php echo $eduPaymentSchedule_html; ?>',
			frame : true,
			height: 80
		}
		var eduPaymentSchedule_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduPaymentSchedule_eduPayments,
				title: '<?php __('Payments'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduPaymentSchedule_eduPayments.getCount() == '')
							store_eduPaymentSchedule_eduPayments.reload();
					}
				},
				columns: [
					{header: "<?php __('Payment Schedule'); ?>", dataIndex: 'edu_payment_schedule', sortable: true},
					{header: "<?php __('Student'); ?>", dataIndex: 'edu_student', sortable: true},
					{header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true},
					{header: "<?php __('Date Paid'); ?>", dataIndex: 'date_paid', sortable: true},
					{header: "<?php __('Paid Amount'); ?>", dataIndex: 'paid_amount', sortable: true},
					{header: "<?php __('Cheque Number'); ?>", dataIndex: 'cheque_number', sortable: true},
					{header: "<?php __('Invoice'); ?>", dataIndex: 'invoice', sortable: true},
					{header: "<?php __('Transaction Ref'); ?>", dataIndex: 'transaction_ref', sortable: true},
					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduPaymentSchedule_eduPayments,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduPaymentScheduleViewWindow = new Ext.Window({
			title: '<?php __('View Payment Schedule'); ?>: <?php echo $eduPaymentSchedule['EduPaymentSchedule']['id']; ?>',
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
				eduPaymentSchedule_view_panel_1,
				eduPaymentSchedule_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduPaymentScheduleViewWindow.close();
				}
			}]
		});
