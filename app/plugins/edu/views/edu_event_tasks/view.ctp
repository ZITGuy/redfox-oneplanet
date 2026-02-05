
		
<?php $eduEventTask_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Calendar Event', true) . ":</th><td><b>" . $eduEventTask['EduCalendarEvent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Task', true) . ":</th><td><b>" . $eduEventTask['EduEventTask']['task'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Permissions', true) . ":</th><td><b>" . $eduEventTask['EduEventTask']['permissions'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduEventTask['EduEventTask']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduEventTask['EduEventTask']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduEventTask_view_panel_1 = {
			html : '<?php echo $eduEventTask_html; ?>',
			frame : true,
			height: 80
		}
		var eduEventTask_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduEventTaskViewWindow = new Ext.Window({
			title: '<?php __('View EduEventTask'); ?>: <?php echo $eduEventTask['EduEventTask']['id']; ?>',
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
				eduEventTask_view_panel_1,
				eduEventTask_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduEventTaskViewWindow.close();
				}
			}]
		});
