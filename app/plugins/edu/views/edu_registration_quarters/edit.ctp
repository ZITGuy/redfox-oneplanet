//<script>
	<?php
		$this->ExtForm->create('EduRegistrationQuarter');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduRegistrationQuarterEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduRegistrationQuarters', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_registration_quarter['EduRegistrationQuarter']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_registrations;
				}
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['edu_registration_id'];
				$this->ExtForm->input('edu_registration_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_quarters;
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['edu_quarter_id'];
				$this->ExtForm->input('edu_quarter_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['quarter_average'];
				$this->ExtForm->input('quarter_average', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_registration_quarter['EduRegistrationQuarter']['quarter_rank'];
				$this->ExtForm->input('quarter_rank', $options);
			?>
		]
	});
	
	var EduRegistrationQuarterEditWindow = new Ext.Window({
		title: '<?php __('Edit Registration Quarter'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationQuarterEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationQuarterEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Registration Quarter.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationQuarterEditWindow.collapsed)
					EduRegistrationQuarterEditWindow.expand(true);
				else
					EduRegistrationQuarterEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationQuarterEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationQuarterEditWindow.close();
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
				EduRegistrationQuarterEditWindow.close();
			}
		}]
	});
