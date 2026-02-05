
		
<?php $assessmentRecord_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Student', true) . ":</th><td><b>" . $assessmentRecord['Student']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Assessment', true) . ":</th><td><b>" . $assessmentRecord['Assessment']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Rank', true) . ":</th><td><b>" . $assessmentRecord['AssessmentRecord']['rank'] . "</b></td></tr>" . 
"</table>"; 
?>
		var assessmentRecord_view_panel_1 = {
			html : '<?php echo $assessmentRecord_html; ?>',
			frame : true,
			height: 80
		}
		var assessmentRecord_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var AssessmentRecordViewWindow = new Ext.Window({
			title: '<?php __('View AssessmentRecord'); ?>: <?php echo $assessmentRecord['AssessmentRecord']['id']; ?>',
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
				assessmentRecord_view_panel_1,
				assessmentRecord_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					AssessmentRecordViewWindow.close();
				}
			}]
		});
