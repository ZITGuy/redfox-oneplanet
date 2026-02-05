//<script>
		
<?php $eduOutline_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduOutline['EduOutline']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Course', true) . ":</th><td><b>" . $eduOutline['EduCourse']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('List Order', true) . ":</th><td><b>" . $eduOutline['EduOutline']['list_order'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduOutline['EduOutline']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduOutline['EduOutline']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduOutline_view_panel_1 = {
			html : '<?php echo $eduOutline_html; ?>',
			frame : true,
			height: 80
		}
		var eduOutline_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduOutlineViewWindow = new Ext.Window({
			title: '<?php __('View EduOutline'); ?>: <?php echo $eduOutline['EduOutline']['name']; ?>',
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
				eduOutline_view_panel_1,
				eduOutline_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduOutlineViewWindow.close();
				}
			}]
		});
