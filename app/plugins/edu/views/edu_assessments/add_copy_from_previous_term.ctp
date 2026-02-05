//<script>
		<?php
			$this->ExtForm->create('EduAssessment');
			$this->ExtForm->defineFieldFunctions();
		?>
		var EduAssessmentAddCopyForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 150,
			labelAlign: 'right',
			width: 500,
			resizable: false,
			plain:true,
			modal: true,
			y: 100,
			autoScroll: true,
			closeAction: 'hide',
			url:'<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'add_copy_from_previous_term')); ?>',
			defaultType: 'textfield',

			items: [
                <?php $this->ExtForm->input('edu_class_id', array('hidden' => $edu_class_id)); ?>
			]
		});
		
		var EduAssessmentAddCopyWindow = new Ext.Window({
			title: '<?php __('Copy Assessments from Previous Term'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: EduAssessmentAddCopyForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					EduAssessmentAddCopyForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to import/copy all assessments from a prev section to all current sections.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(EduAssessmentAddCopyWindow.collapsed)
						EduAssessmentAddCopyWindow.expand(true);
					else
						EduAssessmentAddCopyWindow.collapse(true);
				}
			}],
			buttons: [{
				text: '<?php __('Copy & Close'); ?>',
				handler: function(btn){
					EduAssessmentAddCopyForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							EduAssessmentAddCopyWindow.close();
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
					EduAssessmentAddCopyWindow.close();
				}
			}]
		});
