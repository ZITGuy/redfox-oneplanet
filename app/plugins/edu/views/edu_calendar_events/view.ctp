//<script>
		
<?php $eduCalendarEvent_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduCalendarEvent['EduCalendarEvent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Calendar Event Type', true) . ":</th><td><b>" . $eduCalendarEvent['EduCalendarEventType']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Start Date', true) . ":</th><td><b>" . $eduCalendarEvent['EduCalendarEvent']['start_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('End Date', true) . ":</th><td><b>" . $eduCalendarEvent['EduCalendarEvent']['end_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Quarter', true) . ":</th><td><b>" . $eduCalendarEvent['EduQuarter']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduCalendarEvent['EduCalendarEvent']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduCalendarEvent['EduCalendarEvent']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduCalendarEvent_view_panel_1 = {
			html : '<?php echo $eduCalendarEvent_html; ?>',
			frame : true,
			height: 80
		}
		var eduCalendarEvent_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduCalendarEventViewWindow = new Ext.Window({
			title: '<?php __('View EduCalendarEvent'); ?>: <?php echo $eduCalendarEvent['EduCalendarEvent']['name']; ?>',
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
				eduCalendarEvent_view_panel_1,
				eduCalendarEvent_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduCalendarEventViewWindow.close();
				}
			}]
		});
