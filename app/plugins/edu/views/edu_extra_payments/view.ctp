
		
<?php $eduExtraPayment_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Extra Payment Setting', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPaymentSetting']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Student', true) . ":</th><td><b>" . $eduExtraPayment['EduStudent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Is Paid', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['is_paid'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Date Paid', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['date_paid'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Paid Amount', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['paid_amount'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Cheque Number', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['cheque_number'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Cheque Amount', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['cheque_amount'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Invoice', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['invoice'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Transaction Ref', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['transaction_ref'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduExtraPayment['EduExtraPayment']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduExtraPayment_view_panel_1 = {
			html : '<?php echo $eduExtraPayment_html; ?>',
			frame : true,
			height: 80
		}
		var eduExtraPayment_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduExtraPaymentViewWindow = new Ext.Window({
			title: '<?php __('View EduExtraPayment'); ?>: <?php echo $eduExtraPayment['EduExtraPayment']['id']; ?>',
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
				eduExtraPayment_view_panel_1,
				eduExtraPayment_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduExtraPaymentViewWindow.close();
				}
			}]
		});
