//<script>
	<?php
		$this->ExtForm->create('EduRegistrationQuarter');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationQuarterAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_registrations;
				}
				$this->ExtForm->input('edu_registration_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_quarters;
				$this->ExtForm->input('edu_quarter_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('quarter_average', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('quarter_rank', $options);
			?>
		]
	});
	
	var EduRegistrationQuarterAddWindow = new Ext.Window({
		title: '<?php __('Add Edu Registration Quarter'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationQuarterAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationQuarterAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Registration Quarter.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationQuarterAddWindow.collapsed)
					EduRegistrationQuarterAddWindow.expand(true);
				else
					EduRegistrationQuarterAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationQuarterAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationQuarterAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationQuarterData();
<?php } else { ?>
						RefreshEduRegistrationQuarterData();
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
				EduRegistrationQuarterAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationQuarterAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationQuarterData();
<?php } else { ?>
						RefreshEduRegistrationQuarterData();
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
				EduRegistrationQuarterAddWindow.close();
			}
		}]
	});
