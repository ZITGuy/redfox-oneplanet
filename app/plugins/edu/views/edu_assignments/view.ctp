//<script>
		
<?php $eduAssignment_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Teacher', true) . ":</th><td><b>" . $eduAssignment['EduTeacher']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Course', true) . ":</th><td><b>" . $eduAssignment['EduCourse']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Section', true) . ":</th><td><b>" . $eduAssignment['EduSection']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Start Date', true) . ":</th><td><b>" . $eduAssignment['EduAssignment']['start_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('End Date', true) . ":</th><td><b>" . $eduAssignment['EduAssignment']['end_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduAssignment['EduAssignment']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduAssignment['EduAssignment']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduAssignment_view_panel_1 = {
			html : '<?php echo $eduAssignment_html; ?>',
			frame : true,
			height: 80
		}
		var eduAssignment_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduAssignmentViewWindow = new Ext.Window({
			title: '<?php __('View EduAssignment'); ?>: <?php echo $eduAssignment['EduAssignment']['id']; ?>',
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
				eduAssignment_view_panel_1,
				eduAssignment_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduAssignmentViewWindow.close();
				}
			}]
		});
