//<script>
		
<?php $eduLessonPlanItem_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Lesson Plan', true) . ":</th><td><b>" . $eduLessonPlanItem['EduLessonPlan']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Period', true) . ":</th><td><b>" . $eduLessonPlanItem['EduPeriod']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Day', true) . ":</th><td><b>" . $eduLessonPlanItem['EduDay']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Outline', true) . ":</th><td><b>" . $eduLessonPlanItem['EduOutline']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduLessonPlanItem['EduLessonPlanItem']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduLessonPlanItem['EduLessonPlanItem']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduLessonPlanItem_view_panel_1 = {
			html : '<?php echo $eduLessonPlanItem_html; ?>',
			frame : true,
			height: 80
		}
		var eduLessonPlanItem_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var EduLessonPlanItemViewWindow = new Ext.Window({
			title: '<?php __('View EduLessonPlanItem'); ?>: <?php echo $eduLessonPlanItem['EduLessonPlanItem']['id']; ?>',
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
				eduLessonPlanItem_view_panel_1,
				eduLessonPlanItem_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduLessonPlanItemViewWindow.close();
				}
			}]
		});
