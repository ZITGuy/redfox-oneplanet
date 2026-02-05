
		
<?php $eduTeachersTraining_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Teacher', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeacher']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Training', true) . ":</th><td><b>" . $eduTeachersTraining['EduTraining']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('From Date', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['from_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('To Date', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['to_date'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Trainer', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['trainer'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Remark', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['remark'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Deleted', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['deleted'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduTeachersTraining['EduTeachersTraining']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduTeachersTraining_view_panel_1 = {
			html : '<?php echo $eduTeachersTraining_html; ?>',
			frame : true,
			height: 80
		}
		var eduTeachersTraining_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduTeachersTrainingViewWindow = new Ext.Window({
			title: '<?php __('View EduTeachersTraining'); ?>: <?php echo $eduTeachersTraining['EduTeachersTraining']['id']; ?>',
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
				eduTeachersTraining_view_panel_1,
				eduTeachersTraining_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduTeachersTrainingViewWindow.close();
				}
			}]
		});
