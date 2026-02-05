//<script>		
	<?php $text_message_html = "<table cellspacing=3>" . 		
		"<tr><th align=right>" . __('Receiver', true) . ":</th><td><b>" . $text_message['TextMessage']['receiver'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Message', true) . ":</th><td><b>" . $text_message['TextMessage']['message'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Status', true) . ":</th><td><b>" . $text_message['TextMessage']['status'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $text_message['TextMessage']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $text_message['TextMessage']['modified'] . "</b></td></tr>" . 
	"</table>"; 
	?>
	var textMessage_view_panel_1 = {
		html : '<?php echo $text_message_html; ?>',
		frame : true,
		height: 80
	}
	var textMessage_view_panel_2 = new Ext.TabPanel({
		activeTab: 0,
		anchor: '100%',
		height:190,
		plain:true,
		defaults:{autoScroll: true},
		items:[
					]
	});

	var TextMessageViewWindow = new Ext.Window({
		title: '<?php __('View Text Message'); ?>: <?php echo $text_message['TextMessage']['id']; ?>',
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
			textMessage_view_panel_1,
			textMessage_view_panel_2
		],

		buttons: [{
			text: '<?php __('Close'); ?>',
			handler: function(btn){
				TextMessageViewWindow.close();
			}
		}]
	});
