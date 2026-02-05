//<script>
		
<?php $helpItem_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Title', true) . ":</th><td><b>" . $helpItem['HelpItem']['title'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $helpItem['HelpItem']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Content', true) . ":</th><td><b>" . $helpItem['HelpItem']['content'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Help Item Type', true) . ":</th><td><b>" . $helpItem['HelpItemType']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('List Order', true) . ":</th><td><b>" . $helpItem['HelpItem']['list_order'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Lft', true) . ":</th><td><b>" . $helpItem['HelpItem']['lft'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Rght', true) . ":</th><td><b>" . $helpItem['HelpItem']['rght'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Version', true) . ":</th><td><b>" . $helpItem['HelpItem']['version'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $helpItem['HelpItem']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $helpItem['HelpItem']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var helpItem_view_panel_1 = {
			html : '<?php echo $helpItem_html; ?>',
			frame : true,
			height: 270
		}

		var HelpItemViewWindow = new Ext.Window({
			title: '<?php __('View HelpItem'); ?>: <?php echo $helpItem['HelpItem']['title']; ?>',
			width: 500,
			height: 345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
			modal: true,
			items: [ 
				helpItem_view_panel_1
			],
			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					HelpItemViewWindow.close();
				}
			}]
		});
