
		
<?php $country_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $country['Country']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Code', true) . ":</th><td><b>" . $country['Country']['code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Currency', true) . ":</th><td><b>" . $country['Country']['currency'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Nationality', true) . ":</th><td><b>" . $country['Country']['nationality'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Language', true) . ":</th><td><b>" . $country['Country']['language'] . "</b></td></tr>" . 
"</table>"; 
?>
		var country_view_panel_1 = {
			html : '<?php echo $country_html; ?>',
			frame : true,
			height: 80
		}
		var country_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var CountryViewWindow = new Ext.Window({
			title: '<?php __('View Country'); ?>: <?php echo $country['Country']['name']; ?>',
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
				country_view_panel_1,
				country_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					CountryViewWindow.close();
				}
			}]
		});
