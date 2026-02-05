
		
<?php $authorization_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $authorization['Authorization']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Command Script', true) . ":</th><td><b>" . $authorization['Authorization']['command_script'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Maker', true) . ":</th><td><b>" . $authorization['Maker']['username'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Authorizer', true) . ":</th><td><b>" . $authorization['Authorizer']['username'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Status', true) . ":</th><td><b>" . $authorization['Authorization']['status'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $authorization['Authorization']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $authorization['Authorization']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var authorization_view_panel_1 = {
			html : '<?php echo $authorization_html; ?>',
			frame : true,
			height: 80
		}
		var authorization_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var AuthorizationViewWindow = new Ext.Window({
			title: '<?php __('View Authorization'); ?>: <?php echo $authorization['Authorization']['name']; ?>',
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
				authorization_view_panel_1,
				authorization_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AuthorizationViewWindow.close();
				}
			}]
		});
