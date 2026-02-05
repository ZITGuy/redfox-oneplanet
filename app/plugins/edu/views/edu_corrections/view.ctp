
		
<?php $absentee_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Attendance Record', true) . ":</th><td><b>" . $absentee['AttendanceRecord']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Student', true) . ":</th><td><b>" . $absentee['Student']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $absentee['Absentee']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Reason', true) . ":</th><td><b>" . $absentee['Absentee']['reason'] . "</b></td></tr>" . 
"</table>"; 
?>
		var absentee_view_panel_1 = {
			html : '<?php echo $absentee_html; ?>',
			frame : true,
			height: 80
		}
		var absentee_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var AbsenteeViewWindow = new Ext.Window({
			title: '<?php __('View Absentee'); ?>: <?php echo $absentee['Absentee']['id']; ?>',
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
				absentee_view_panel_1,
				absentee_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AbsenteeViewWindow.close();
				}
			}]
		});
