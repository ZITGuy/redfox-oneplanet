//<script>
		
<?php $eduCommunication_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Student', true) . ":</th><td><b>" . $eduCommunication['EduStudent']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Section', true) . ":</th><td><b>" . $eduCommunication['EduSection']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Post Date', true) . ":</th><td><b>" . $eduCommunication['EduCommunication']['post_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Teacher Comment', true) . ":</th><td><b>" . $eduCommunication['EduCommunication']['teacher_comment'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Parent Comment', true) . ":</th><td><b>" . $eduCommunication['EduCommunication']['parent_comment'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('User', true) . ":</th><td><b>" . $eduCommunication['User']['username'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduCommunication['EduCommunication']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduCommunication['EduCommunication']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduCommunication_view_panel_1 = {
			html : '<?php echo $eduCommunication_html; ?>',
			frame : true,
			height: 80
		}
		var eduCommunication_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduCommunicationViewWindow = new Ext.Window({
			title: '<?php __('View EduCommunication'); ?>: <?php echo $eduCommunication['EduCommunication']['id']; ?>',
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
				eduCommunication_view_panel_1,
				eduCommunication_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduCommunicationViewWindow.close();
				}
			}]
		});
