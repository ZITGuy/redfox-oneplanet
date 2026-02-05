//<script>		
<?php $edu_parent_detail_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Short Name', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['short_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('First Name', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['first_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Middle Name', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['middle_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Last Name', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['last_name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Residence Address', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['residence_address'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Nationality', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['nationality'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Relationship', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['relationship'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Occupation', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['occupation'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Academic Qualification', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['academic_qualification'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Employment Status', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['employment_status'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Employer', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['employer'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Work Address', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['work_address'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Work Telephone', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['work_telephone'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Mobile', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['mobile'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Email', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['email'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Photo File', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['photo_file'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Family Type', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['family_type'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Parent', true) . ":</th><td><b>" . $edu_parent_detail['EduParent']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $edu_parent_detail['EduParentDetail']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduParentDetail_view_panel_1 = {
			html : '<?php echo $edu_parent_detail_html; ?>',
			frame : true,
			height: 270
		}

		var EduParentDetailViewWindow = new Ext.Window({
			title: '<?php __('View EduParentDetail'); ?>: <?php echo $edu_parent_detail['EduParentDetail']['id']; ?>',
			width: 500,
			height: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				eduParentDetail_view_panel_1
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduParentDetailViewWindow.close();
				}
			}]
		});
