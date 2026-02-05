
		
<?php $restorePoint_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $restorePoint['RestorePoint']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $restorePoint['RestorePoint']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $restorePoint['RestorePoint']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var restorePoint_view_panel_1 = {
			html : '<?php echo $restorePoint_html; ?>',
			frame : true,
			height: 80
		}
		var restorePoint_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var RestorePointViewWindow = new Ext.Window({
			title: '<?php __('View RestorePoint'); ?>: <?php echo $restorePoint['RestorePoint']['name']; ?>',
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
				restorePoint_view_panel_1,
				restorePoint_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					RestorePointViewWindow.close();
				}
			}]
		});
