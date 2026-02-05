//<script>	
<?php $eduRegistrationEvaluation_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Registration', true) . ":</th><td><b>" . $eduRegistrationEvaluation['EduRegistration']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Evaluation', true) . ":</th><td><b>" . $eduRegistrationEvaluation['EduEvaluation']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Quarter', true) . ":</th><td><b>" . $eduRegistrationEvaluation['EduQuarter']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Evaluation Value', true) . ":</th><td><b>" . $eduRegistrationEvaluation['EduEvaluationValue']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduRegistrationEvaluation['EduRegistrationEvaluation']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduRegistrationEvaluation['EduRegistrationEvaluation']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduRegistrationEvaluation_view_panel_1 = {
			html : '<?php echo $eduRegistrationEvaluation_html; ?>',
			frame : true,
			height: 80
		}
		var eduRegistrationEvaluation_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			]
		});

		var EduRegistrationEvaluationViewWindow = new Ext.Window({
			title: '<?php __('View Registration Evaluation'); ?>',
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
				eduRegistrationEvaluation_view_panel_1,
				eduRegistrationEvaluation_view_panel_2
			],
			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduRegistrationEvaluationViewWindow.close();
				}
			}]
		});
