
		
<?php $eduRegistrationQuarterResult_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Registration Quarter', true) . ":</th><td><b>" . $eduRegistrationQuarterResult['EduRegistrationQuarter']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Course', true) . ":</th><td><b>" . $eduRegistrationQuarterResult['EduCourse']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Course Result', true) . ":</th><td><b>" . $eduRegistrationQuarterResult['EduRegistrationQuarterResult']['course_result'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Result Indicator', true) . ":</th><td><b>" . $eduRegistrationQuarterResult['EduRegistrationQuarterResult']['result_indicator'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduRegistrationQuarterResult['EduRegistrationQuarterResult']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduRegistrationQuarterResult['EduRegistrationQuarterResult']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduRegistrationQuarterResult_view_panel_1 = {
			html : '<?php echo $eduRegistrationQuarterResult_html; ?>',
			frame : true,
			height: 80
		}
		var eduRegistrationQuarterResult_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduRegistrationQuarterResultViewWindow = new Ext.Window({
			title: '<?php __('View EduRegistrationQuarterResult'); ?>: <?php echo $eduRegistrationQuarterResult['EduRegistrationQuarterResult']['id']; ?>',
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
				eduRegistrationQuarterResult_view_panel_1,
				eduRegistrationQuarterResult_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduRegistrationQuarterResultViewWindow.close();
				}
			}]
		});
