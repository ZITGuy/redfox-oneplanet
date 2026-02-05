//<script>
		
<?php $eduDay_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Date', true) . ":</th><td><b>" . $eduDay['EduDay']['date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Week Day', true) . ":</th><td><b>" . $eduDay['EduDay']['week_day'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Quarter', true) . ":</th><td><b>" . $eduDay['EduQuarter']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduDay['EduDay']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduDay['EduDay']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduDay_view_panel_1 = {
			html : '<?php echo $eduDay_html; ?>',
			frame : true,
			height: 80
		}
		var eduDay_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduDayViewWindow = new Ext.Window({
			title: '<?php __('View EduDay'); ?>: <?php echo $eduDay['EduDay']['id']; ?>',
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
				eduDay_view_panel_1,
				eduDay_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduDayViewWindow.close();
				}
			}]
		});
