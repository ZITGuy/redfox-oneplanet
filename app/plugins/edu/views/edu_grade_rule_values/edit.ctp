//<script>
	<?php
		$this->ExtForm->create('GradeRuleValue');
		$this->ExtForm->defineFieldFunctions();
	?>
	var GradeRuleValueEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'gradeRuleValues', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $grade_rule_value['GradeRuleValue']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $grade_rule_value['GradeRuleValue']['min'];
				$this->ExtForm->input('min', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $grade_rule_value['GradeRuleValue']['max'];
				$this->ExtForm->input('max', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $grade_rule_value['GradeRuleValue']['code'];
				$this->ExtForm->input('code', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $grade_rules;
				}
				$options['value'] = $grade_rule_value['GradeRuleValue']['grade_rule_id'];
				$this->ExtForm->input('grade_rule_id', $options);
			?>
		]
	});
	
	var GradeRuleValueEditWindow = new Ext.Window({
		title: '<?php __('Edit Grade Rule Value'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: GradeRuleValueEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				GradeRuleValueEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Grade Rule Value.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(GradeRuleValueEditWindow.collapsed)
					GradeRuleValueEditWindow.expand(true);
				else
					GradeRuleValueEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				GradeRuleValueEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						GradeRuleValueEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentGradeRuleValueData();
<?php } else { ?>
						RefreshGradeRuleValueData();
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
				GradeRuleValueEditWindow.close();
			}
		}]
	});
