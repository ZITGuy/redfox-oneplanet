
		
<?php $eduPayment_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Payment Schedule', true) . ":</th><td><b>" . $eduPayment['EduPaymentSchedule']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Student', true) . ":</th><td><b>" . $eduPayment['EduStudent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Is Paid', true) . ":</th><td><b>" . $eduPayment['EduPayment']['is_paid'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Date Paid', true) . ":</th><td><b>" . $eduPayment['EduPayment']['date_paid'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Paid Amount', true) . ":</th><td><b>" . $eduPayment['EduPayment']['paid_amount'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Cheque Number', true) . ":</th><td><b>" . $eduPayment['EduPayment']['cheque_number'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Invoice', true) . ":</th><td><b>" . $eduPayment['EduPayment']['invoice'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Transaction Ref', true) . ":</th><td><b>" . $eduPayment['EduPayment']['transaction_ref'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduPayment['EduPayment']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduPayment['EduPayment']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduPayment_view_panel_1 = {
			html : '<?php echo $eduPayment_html; ?>',
			frame : true,
			height: 80
		}
		var eduPayment_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduPaymentViewWindow = new Ext.Window({
			title: '<?php __('View EduPayment'); ?>: <?php echo $eduPayment['EduPayment']['id']; ?>',
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
				eduPayment_view_panel_1,
				eduPayment_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduPaymentViewWindow.close();
				}
			}]
		});
