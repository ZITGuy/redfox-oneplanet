
		
<?php $status_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $status['Status']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Tables', true) . ":</th><td><b>" . $status['Status']['tables'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Remark', true) . ":</th><td><b>" . $status['Status']['remark'] . "</b></td></tr>" . 
"</table>"; 
?>
		var status_view_panel_1 = {
			html : '<?php echo $status_html; ?>',
			frame : true,
			height: 80
		}
		var status_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var StatusViewWindow = new Ext.Window({
			title: '<?php __('View Status'); ?>: <?php echo $status['Status']['name']; ?>',
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
				status_view_panel_1,
				status_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					StatusViewWindow.close();
				}
			}]
		});
