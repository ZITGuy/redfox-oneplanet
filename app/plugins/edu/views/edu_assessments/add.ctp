//<script>
		<?php
			$this->ExtForm->create('EduAssessment');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EduAssessmentAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			autoHeight: true,
			width: 500,
			resizable: false,
			plain:true,
			modal: true,
			y: 100,
			autoScroll: true,
			closeAction: 'hide',
			url:'<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('edu_teacher_id', array('hidden' => $edu_teacher_id)); ?>,
				<?php $this->ExtForm->input('edu_course_id', array('hidden' => $edu_course_id)); ?>,
				<?php $this->ExtForm->input('edu_section_id', array('hidden' => $edu_section_id)); ?>,
				<?php
					$options = array('fieldLabel'=>'Assessment Type');
					$options['items'] = $assessment_types;
					$this->ExtForm->input('edu_assessment_type_id', $options);
				?>,
				<?php
					$options = array('anchor' => '70%');
					$this->ExtForm->input('max_value', $options);
				?>,
				<?php
					$options = array('anchor' => '70%');
					$options['minValue'] = "'" . $edu_quarter['EduQuarter']['start_date'] . "'";
					$options['maxValue'] = "'" . $edu_quarter['EduQuarter']['end_date'] . "'";
					//$options['disabledDays'] = "'[0, 6]'";
					//$options['disabledDaysText'] = 'This is weekend';
					$options['value'] = $edu_quarter['EduQuarter']['start_date'];
					$this->ExtForm->input('date', $options);
				?>,
				<?php
					$options = array();
					$options['fieldLabel'] = 'Remark';
					$this->ExtForm->input('detail', $options);
				?>
			]
		});
		
		var EduAssessmentAddWindow = new Ext.Window({
			title: '<?php __('Add Assessment'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduAssessmentAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduAssessmentAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Assessment.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduAssessmentAddWindow.collapsed)
						EduAssessmentAddWindow.expand(true);
					else
						EduAssessmentAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EduAssessmentAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduAssessmentAddForm.getForm().reset();
							RefreshParentAssessmentData();
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					EduAssessmentAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduAssessmentAddWindow.close();
							RefreshParentAssessmentData();
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			},{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					EduAssessmentAddWindow.close();
				}
			}]
		});
