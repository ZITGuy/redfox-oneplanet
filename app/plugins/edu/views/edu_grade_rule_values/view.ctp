
		
<?php $gradeRuleValue_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Min', true) . ":</th><td><b>" . $gradeRuleValue['GradeRuleValue']['min'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Max', true) . ":</th><td><b>" . $gradeRuleValue['GradeRuleValue']['max'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $gradeRuleValue['GradeRuleValue']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Grade Rule', true) . ":</th><td><b>" . $gradeRuleValue['GradeRule']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var gradeRuleValue_view_panel_1 = {
			html : '<?php echo $gradeRuleValue_html; ?>',
			frame : true,
			height: 80
		}
		var gradeRuleValue_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var GradeRuleValueViewWindow = new Ext.Window({
			title: '<?php __('View GradeRuleValue'); ?>: <?php echo $gradeRuleValue['GradeRuleValue']['id']; ?>',
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
				gradeRuleValue_view_panel_1,
				gradeRuleValue_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					GradeRuleValueViewWindow.close();
				}
			}]
		});
