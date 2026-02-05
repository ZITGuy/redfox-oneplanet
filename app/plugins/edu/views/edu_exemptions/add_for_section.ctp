//<script>
	<?php
		$this->ExtForm->create('EduExemption');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduExemptionAddForSectionForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 120,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'add_for_section')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_sections;
				}
				$this->ExtForm->input('edu_section_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Course');
				$options['items'] = $edu_courses;
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Academic Year');
				$options['hidden'] = $current_ay_id;
				$this->ExtForm->input('edu_academic_year_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Term of this AY');
				$options['items'] = $edu_quarters;
				$this->ExtForm->input('edu_quarter_id', $options);
			?>
		]
	});
	
	var EduExemptionAddForSectionWindow = new Ext.Window({
		title: '<?php __('Add For Section Exemption'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduExemptionAddForSectionForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduExemptionAddForSectionForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Exemption.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduExemptionAddForSectionWindow.collapsed)
					EduExemptionAddForSectionWindow.expand(true);
				else
					EduExemptionAddForSectionWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduExemptionAddForSectionForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduExemptionAddForSectionForm.getForm().reset();
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
				EduExemptionAddForSectionForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduExemptionAddForSectionWindow.close();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduExemptionAddForSectionWindow.close();
			}
		}]
	});
