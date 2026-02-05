//<script>
	<?php
		$this->ExtForm->create('EduRegistration');
		$this->ExtForm->defineFieldFunctions();
	?>
	
	var popUpWin_1=0;
	var selected_academic_year_id = <?php echo $active_ay['EduAcademicYear']['id']; ?>;
	
	function popUpWindow(URLStr, left, top, width, height) {
		if(popUpWin_1){
			if(!popUpWin_1.closed) popUpWin_1.close();
		}
		popUpWin_1 = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
	}

	function viewRptEnrolledStudents() {
		var rtitle = Ext.getCmp('data[EduRegistration][report_title]');
		var report_title = rtitle.getValue();
		newstr = report_title.replace(/\s/gi, "_");
		url = "<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'rpt_view_enrolled_students')); ?>/" + selected_academic_year_id + '/' + newstr;
		popUpWindow(url, 0, 0, 1200, 1200);
	}
	
	var RptEnrolledStudentsForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'',
		defaultType: 'textfield',
		
		items: [
			<?php 
				$options = array('value' => 'Enrolled Students', 'id' => 'data[EduRegistration][report_title]');
				$this->ExtForm->input('report_title', $options);
			?>,
			<?php 
				$options = array();
				$options['value'] = $active_ay['EduAcademicYear']['id'];
				$options['items'] = $edu_academic_years;
				$options['fieldLabel'] = 'Academic Year';
				$options['listeners'] = "{
						scope: this,
						'select': function(combo, record, index){
							selected_academic_year_id = combo.getValue();
							alert(selected_academic_year_id);
						}
					}";
				$this->ExtForm->input('edu_academic_year_id', $options);
			?>,
			<?php 
				$options = array();
				$options['value'] = 1;
				$options['items'] = array(1 => 'Main');
				$options['fieldLabel'] = 'Campus';
				$this->ExtForm->input('campus_id', $options);
			?>
		]
	});
	
	
	var RptEnrolledStudentsWindow = new Ext.Window({
		title: '<?php __('Report: Enrolled Students'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: RptEnrolledStudentsForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				RptEnrolledStudentsForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to set parameters to the Enrolled students report.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(RptEnrolledStudentsWindow.collapsed)
					RptEnrolledStudentsWindow.expand(true);
				else
					RptEnrolledStudentsWindow.collapse(true);
			}
		}],
		buttons: [{
			text: '<?php __('Show report'); ?>',
			handler: function(btn){
				viewRptEnrolledStudents();
			}
		}, {
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				RptEnrolledStudentsWindow.close();
			}
		}]
	});
	
	RptEnrolledStudentsWindow.show();
		