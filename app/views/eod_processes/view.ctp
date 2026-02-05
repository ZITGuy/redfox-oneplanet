
		
<?php $eodProcess_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eodProcess['EodProcess']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Process Date', true) . ":</th><td><b>" . $eodProcess['EodProcess']['process_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('User', true) . ":</th><td><b>" . $eodProcess['User']['username'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Task1 Backup Taken', true) . ":</th><td><b>" . $eodProcess['EodProcess']['task1_backup_taken'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Task2 Portal Updated', true) . ":</th><td><b>" . $eodProcess['EodProcess']['task2_portal_updated'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Task3 Ftp Sent', true) . ":</th><td><b>" . $eodProcess['EodProcess']['task3_ftp_sent'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Backup Type', true) . ":</th><td><b>" . $eodProcess['EodProcess']['backup_type'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Incremental Count', true) . ":</th><td><b>" . $eodProcess['EodProcess']['incremental_count'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Backup Incremental File', true) . ":</th><td><b>" . $eodProcess['EodProcess']['backup_incremental_file'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Backup Full File', true) . ":</th><td><b>" . $eodProcess['EodProcess']['backup_full_file'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eodProcess['EodProcess']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eodProcess['EodProcess']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eodProcess_view_panel_1 = {
			html : '<?php echo $eodProcess_html; ?>',
			frame : true,
			height: 80
		}
		var eodProcess_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EodProcessViewWindow = new Ext.Window({
			title: '<?php __('View EodProcess'); ?>: <?php echo $eodProcess['EodProcess']['name']; ?>',
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
				eodProcess_view_panel_1,
				eodProcess_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EodProcessViewWindow.close();
				}
			}]
		});
