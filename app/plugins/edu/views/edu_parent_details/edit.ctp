//<script>
	<?php
		$this->ExtForm->create('EduParentDetail');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduParentDetailEditForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 150,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_parent_detail['EduParentDetail']['id'])); ?>,
			<?php
				$options = array('anchor' => '50%');
				$options['value'] = $edu_parent_detail['EduParentDetail']['short_name'];
				$this->ExtForm->input('short_name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['first_name'];
				$this->ExtForm->input('first_name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['middle_name'];
				$this->ExtForm->input('middle_name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['last_name'];
				$this->ExtForm->input('last_name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['residence_address'];
				$this->ExtForm->input('residence_address', $options);
			?>,
			<?php
				$options = array('anchor' => '70%', 'value' => 'ETHIOPIAN');
				$options['value'] = $edu_parent_detail['EduParentDetail']['nationality'];
				$this->ExtForm->input('nationality', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['relationship'];
				$this->ExtForm->input('relationship', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['occupation'];
				$this->ExtForm->input('occupation', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['academic_qualification'];
				$this->ExtForm->input('academic_qualification', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['employment_status'];
				$this->ExtForm->input('employment_status', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['employer'];
				$this->ExtForm->input('employer', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_parent_detail['EduParentDetail']['work_address'];
				$this->ExtForm->input('work_address', $options);
			?>,
			<?php
				$options = array('anchor' => '70%');
				$options['value'] = $edu_parent_detail['EduParentDetail']['work_telephone'];
				$this->ExtForm->input('work_telephone', $options);
			?>,
			<?php
				$options = array('anchor' => '70%');
				$options['value'] = $edu_parent_detail['EduParentDetail']['mobile'];
				$this->ExtForm->input('mobile', $options);
			?>,
			<?php
				$options = array('anchor' => '90%');
				$options['value'] = $edu_parent_detail['EduParentDetail']['email'];
				$this->ExtForm->input('email', $options);
			?>,
			<?php
				$options = array(
					'anchor' => '80%',
					'fieldLabel' => 'Parental Role',
					'xtype' => 'combo');
				$options['items'] = array('M' => 'MOTHER', 'F' => 'FATHER', 'G' => 'GUARDIAN');
				$options['value'] = $edu_parent_detail['EduParentDetail']['family_type'];
				$this->ExtForm->input('family_type', $options);
			?>,
			<?php
				$options = array();
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $edu_parents;
				$options['value'] = $edu_parent_detail['EduParentDetail']['edu_parent_id'];
				$this->ExtForm->input('edu_parent_id', $options);
			?>
		]
	});
	
	var EduParentDetailEditWindow = new Ext.Window({
		title: '<?php __('Edit Parent Detail'); ?>',
		width: 500,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduParentDetailEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduParentDetailEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Parent Detail.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduParentDetailEditWindow.collapsed)
					EduParentDetailEditWindow.expand(true);
				else
					EduParentDetailEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduParentDetailEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduParentDetailEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduParentDetailData();
<?php } else { ?>
						RefreshEduParentDetailData();
<?php } ?>
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
				EduParentDetailEditWindow.close();
			}
		}]
	});
