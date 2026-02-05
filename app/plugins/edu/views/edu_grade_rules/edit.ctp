//<script>
	<?php
		$this->ExtForm->create('GradeRule');
		$this->ExtForm->defineFieldFunctions();
	?>
	var GradeRuleEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array(
			'controller' => 'gradeRules', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $grade_rule['GradeRule']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $grade_rule['GradeRule']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $grade_rule['GradeRule']['type'];
				$this->ExtForm->input('type', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $grade_rule['GradeRule']['created_date'];
				$this->ExtForm->input('created_date', $options);
			?>
		]
	});
	
	var GradeRuleEditWindow = new Ext.Window({
		title: '<?php __('Edit Grade Rule'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: GradeRuleEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				GradeRuleEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Grade Rule.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(GradeRuleEditWindow.collapsed)
					GradeRuleEditWindow.expand(true);
				else
					GradeRuleEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				GradeRuleEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						GradeRuleEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentGradeRuleData();
<?php } else { ?>
						RefreshGradeRuleData();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				GradeRuleEditWindow.close();
			}
		}]
	});
