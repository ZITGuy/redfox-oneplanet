//<script>
	<?php
		$this->ExtForm->create('EduSection');
		$this->ExtForm->defineFieldFunctions();
	?>
	<?php
		$sections = array();
		foreach($edu_sections as $edu_section) {
			$sections[$edu_section['EduSection']['id']] = $edu_section['EduClass']['name'] . ' - ' . $edu_section['EduSection']['name'];
		}
	?>
	
	var popUpWin_1=0;
	var selected_section_id = 0;
	
	function popUpWindow(URLStr, left, top, width, height) {
		if(popUpWin_1){
			if(!popUpWin_1.closed) popUpWin_1.close();
		}
		popUpWin_1 = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
	}

	function viewRptStudentsPerSection() {
		var rtitle = Ext.getCmp('data[EduSection][report_title]');
		var report_title = rtitle.getValue();
		newstr = report_title.replace(/\s/gi, "_");
		url = "<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'rpt_view_students_per_section')); ?>/" + selected_section_id + '/' + newstr;
		popUpWindow(url, 0, 0, 1200, 1200);
	}
	
	var RptStudentsPerSectionForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url: '',
		defaultType: 'textfield',
		
		items: [
			<?php 
				$options = array('value' => 'Students Per Section', 'id' => 'data[EduSection][report_title]');
				$this->ExtForm->input('report_title', $options);
			?>,
			<?php 
				$options = array();
				$options['items'] = $sections;
				$options['listeners'] = "{
						scope: this,
						'select': function(combo, record, index){
							selected_section_id = combo.getValue();
						}
					}";
				$options['fieldLabel'] = 'Section';
				$this->ExtForm->input('edu_section_id', $options);
			?>
		]
	});
	
	
	var RptStudentsPerSectionWindow = new Ext.Window({
		title: '<?php __('Report: Students Per Section'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: RptStudentsPerSectionForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				RptStudentsPerSectionForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to set parameters to the Students Per Section report.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(RptStudentsPerSectionWindow.collapsed)
					RptStudentsPerSectionWindow.expand(true);
				else
					RptStudentsPerSectionWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Show report'); ?>',
			handler: function(btn){
				viewRptStudentsPerSection();
			}
		}, {
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				RptStudentsPerSectionWindow.close();
			}
		}]
	});
	
	RptStudentsPerSectionWindow.show();
		
