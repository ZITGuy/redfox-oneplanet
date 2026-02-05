//<script>
	<?php
		$this->ExtForm->create('EduSibling');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduSiblingAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_siblings', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('anchor' => '80%');
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array('anchor' => '50%');
				$this->ExtForm->input('age', $options);
			?>,
			<?php
				$options = array(
					'anchor' => '60%',
					'xtype' => 'combo',
					'fieldLabel' => 'Sex',
					'value' => 'F',
					'items' => array('F' => 'Female', 'M' => 'Male')
				);
				$this->ExtForm->input('sex', $options);
			?>,
			<?php
				$options = array('anchor' => '60%');
				$this->ExtForm->input('grade', $options);
			?>
		]
	});
	
	var EduSiblingAddWindow = new Ext.Window({
		title: '<?php __('Add Sibling'); ?>',
		width: 450,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduSiblingAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduSiblingAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Emergency Contact.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduParentAddWindow.collapsed)
					EduParentAddWindow.expand(true);
				else
					EduParentAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduSiblingAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.alert('<?php __('Success'); ?>', a.result.msg);
						EduSiblingAddForm.getForm().reset();
						RefreshEduSiblingData();
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
				EduSiblingAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduSiblingAddWindow.close();
						RefreshEduSiblingData();
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
				EduSiblingAddWindow.close();
			}
		}]
	});