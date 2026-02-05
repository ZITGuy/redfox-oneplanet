
		
<?php $eduClassPayment_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Class', true) . ":</th><td><b>" . $eduClassPayment['EduClass']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Academic Year', true) . ":</th><td><b>" . $eduClassPayment['EduAcademicYear']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Enrollment Fee', true) . ":</th><td><b>" . $eduClassPayment['EduClassPayment']['enrollment_fee'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Registration Fee', true) . ":</th><td><b>" . $eduClassPayment['EduClassPayment']['registration_fee'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduClassPayment['EduClassPayment']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduClassPayment['EduClassPayment']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduClassPayment_view_panel_1 = {
			html : '<?php echo $eduClassPayment_html; ?>',
			frame : true,
			height: 80
		}
		var eduClassPayment_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduClassPaymentViewWindow = new Ext.Window({
			title: '<?php __('View EduClassPayment'); ?>: <?php echo $eduClassPayment['EduClassPayment']['id']; ?>',
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
				eduClassPayment_view_panel_1,
				eduClassPayment_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduClassPaymentViewWindow.close();
				}
			}]
		});
