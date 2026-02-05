
		
<?php $eduPeriod_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Section', true) . ":</th><td><b>" . $eduPeriod['EduSection']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Course Id', true) . ":</th><td><b>" . $eduPeriod['EduPeriod']['edu_course_Id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Schedule', true) . ":</th><td><b>" . $eduPeriod['EduSchedule']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Day', true) . ":</th><td><b>" . $eduPeriod['EduPeriod']['day'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Period', true) . ":</th><td><b>" . $eduPeriod['EduPeriod']['period'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduPeriod_view_panel_1 = {
			html : '<?php echo $eduPeriod_html; ?>',
			frame : true,
			height: 80
		}
		var eduPeriod_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduPeriodViewWindow = new Ext.Window({
			title: '<?php __('View EduPeriod'); ?>: <?php echo $eduPeriod['EduPeriod']['id']; ?>',
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
				eduPeriod_view_panel_1,
				eduPeriod_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduPeriodViewWindow.close();
				}
			}]
		});
