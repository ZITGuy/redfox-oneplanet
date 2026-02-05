//<script>
	<?php
		$this->ExtForm->create('GradeRuleValue');
		$this->ExtForm->defineFieldFunctions();
	?>
	var GradeRuleValueAddForm = new Ext.form.FormPanel({
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
			'controller' => 'gradeRuleValues', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('min', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('max', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('code', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $grade_rules;
				}
				$this->ExtForm->input('grade_rule_id', $options);
			?>
		]
	});
	
	var GradeRuleValueAddWindow = new Ext.Window({
		title: '<?php __('Add Grade Rule Value'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: GradeRuleValueAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				GradeRuleValueAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Grade Rule Value.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(GradeRuleValueAddWindow.collapsed)
					GradeRuleValueAddWindow.expand(true);
				else
					GradeRuleValueAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				GradeRuleValueAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						GradeRuleValueAddForm.getForm().reset();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				GradeRuleValueAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						GradeRuleValueAddWindow.close();
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
				GradeRuleValueAddWindow.close();
			}
		}]
	});
