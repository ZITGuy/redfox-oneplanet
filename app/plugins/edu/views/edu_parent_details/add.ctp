//<script>
	<?php
		$this->ExtForm->create('EduParentDetail');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduParentDetailAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('anchor' => '50%');
				$this->ExtForm->input('short_name', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('first_name', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('middle_name', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('last_name', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('residence_address', $options);
			?>,
			<?php
				$options = array('anchor' => '70%', 'value' => 'ETHIOPIAN');
				$this->ExtForm->input('nationality', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('occupation', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('academic_qualification', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('employment_status', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('employer', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('work_address', $options);
			?>,
			<?php
				$options = array('anchor' => '70%');
				$this->ExtForm->input('work_telephone', $options);
			?>,
			<?php
				$options = array('anchor' => '70%');
				$this->ExtForm->input('mobile', $options);
			?>,
			<?php
				$options = array('anchor' => '90%');
				$this->ExtForm->input('email', $options);
			?>,
			<?php
				$options = array(
					'anchor' => '80%',
					'fieldLabel' => 'Parental Role',
					'xtype' => 'combo', 'value' => 'M');
				$options['items'] = array('M' => 'MOTHER', 'F' => 'FATHER', 'G' => 'GUARDIAN');
				$this->ExtForm->input('family_type', $options);
			?>,
			<?php
				$options = array();
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $edu_parents;
				$this->ExtForm->input('edu_parent_id', $options);
			?>
		]
	});
	
	var EduParentDetailAddWindow = new Ext.Window({
		title: '<?php __('Add Parent Detail'); ?>',
		width: 500,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduParentDetailAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduParentDetailAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Parent Detail.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduParentDetailAddWindow.collapsed)
					EduParentDetailAddWindow.expand(true);
				else
					EduParentDetailAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduParentDetailAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduParentDetailAddForm.getForm().reset();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				EduParentDetailAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduParentDetailAddWindow.close();
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
				EduParentDetailAddWindow.close();
			}
		}]
	});
