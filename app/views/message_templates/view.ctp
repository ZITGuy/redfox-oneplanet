
		
<?php $messageTemplate_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $messageTemplate['MessageTemplate']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Body', true) . ":</th><td><b>" . $messageTemplate['MessageTemplate']['body'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Default Body', true) . ":</th><td><b>" . $messageTemplate['MessageTemplate']['default_body'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Placeholders', true) . ":</th><td><b>" . $messageTemplate['MessageTemplate']['placeholders'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $messageTemplate['MessageTemplate']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $messageTemplate['MessageTemplate']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var messageTemplate_view_panel_1 = {
			html : '<?php echo $messageTemplate_html; ?>',
			frame : true,
			height: 80
		}
		var messageTemplate_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var MessageTemplateViewWindow = new Ext.Window({
			title: '<?php __('View MessageTemplate'); ?>: <?php echo $messageTemplate['MessageTemplate']['name']; ?>',
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
				messageTemplate_view_panel_1,
				messageTemplate_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					MessageTemplateViewWindow.close();
				}
			}]
		});
