
		
<?php $eduCourseItem_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduCourseItem['EduCourseItem']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Description', true) . ":</th><td><b>" . $eduCourseItem['EduCourseItem']['description'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Course', true) . ":</th><td><b>" . $eduCourseItem['EduCourse']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Max Mark', true) . ":</th><td><b>" . $eduCourseItem['EduCourseItem']['max_mark'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduCourseItem['EduCourseItem']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduCourseItem['EduCourseItem']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduCourseItem_view_panel_1 = {
			html : '<?php echo $eduCourseItem_html; ?>',
			frame : true,
			height: 80
		}
		var eduCourseItem_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduCourseItemViewWindow = new Ext.Window({
			title: '<?php __('View EduCourseItem'); ?>: <?php echo $eduCourseItem['EduCourseItem']['name']; ?>',
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
				eduCourseItem_view_panel_1,
				eduCourseItem_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduCourseItemViewWindow.close();
				}
			}]
		});
