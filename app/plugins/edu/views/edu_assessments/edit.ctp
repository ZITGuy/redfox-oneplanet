//<script>
		<?php
			$this->ExtForm->create('EduAssessment');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EduAssessmentEditForm = new Ext.form.FormPanel({
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
			url:'<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $edu_assessment['EduAssessment']['id'])); ?>,
				<?php 
					$options = array('fieldLabel' => 'Assessment Type');
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $edu_assessment_types;
					$options['value'] = $edu_assessment['EduAssessment']['edu_assessment_type_id'];
					$this->ExtForm->input('edu_assessment_type_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $edu_assessment['EduAssessment']['max_value'];
					$this->ExtForm->input('max_value', $options);
				?>,
				<?php 
					$options = array('anchor' => '70%');
					$options['value'] = $edu_assessment['EduAssessment']['date'];
					$this->ExtForm->input('date', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $edu_assessment['EduAssessment']['detail'];
					$this->ExtForm->input('detail', $options);
				?>		]
		});
		
		var EduAssessmentEditWindow = new Ext.Window({
			title: '<?php __('Edit Assessment'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduAssessmentEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduAssessmentEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Assessment.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduAssessmentEditWindow.collapsed)
						EduAssessmentEditWindow.expand(true);
					else
						EduAssessmentEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					EduAssessmentEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduAssessmentEditWindow.close();

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
					EduAssessmentEditWindow.close();
				}
			}]
		});

		Ext.Msg.show({
			title: '<?php __('Warning'); ?>',
			buttons: Ext.MessageBox.YESNO,
			msg: 'Please be aware that changing the Max Value will result in changing all the records of students. Are you sure?',
            icon: Ext.MessageBox.ERROR,
            fn: function(btn){
                if (btn == 'yes'){
                    EduAssessmentEditWindow.show();
                }
            }
		});
