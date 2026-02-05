//<script>
		
<?php $acctJournal_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Acct Transaction', true) . ":</th><td><b>" . $acctJournal['AcctTransaction']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Acct Account', true) . ":</th><td><b>" . $acctJournal['AcctAccount']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Dr', true) . ":</th><td><b>" . $acctJournal['AcctJournal']['dr'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Cr', true) . ":</th><td><b>" . $acctJournal['AcctJournal']['cr'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Bbf', true) . ":</th><td><b>" . $acctJournal['AcctJournal']['bbf'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $acctJournal['AcctJournal']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $acctJournal['AcctJournal']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var acctJournal_view_panel_1 = {
			html : '<?php echo $acctJournal_html; ?>',
			frame : true,
			height: 80
		}
		var acctJournal_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var AcctJournalViewWindow = new Ext.Window({
			title: '<?php __('View AcctJournal'); ?>: <?php echo $acctJournal['AcctJournal']['id']; ?>',
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
				acctJournal_view_panel_1,
				acctJournal_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AcctJournalViewWindow.close();
				}
			}]
		});
