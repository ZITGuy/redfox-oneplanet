
		
<?php $helpContent_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $helpContent['HelpContent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $helpContent['HelpContent']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Content', true) . ":</th><td><b>" . $helpContent['HelpContent']['content'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $helpContent['HelpContent']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $helpContent['HelpContent']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var helpContent_view_panel_1 = {
			html : '<?php echo $helpContent_html; ?>',
			frame : true,
			height: 80
		}
		var helpContent_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var HelpContentViewWindow = new Ext.Window({
			title: '<?php __('View HelpContent'); ?>: <?php echo $helpContent['HelpContent']['name']; ?>',
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
				helpContent_view_panel_1,
				helpContent_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					HelpContentViewWindow.close();
				}
			}]
		});
