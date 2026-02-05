//<script>
	<?php
		$this->ExtForm->create('EduSection');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduSectionEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_section['EduSection']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_section['EduSection']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$options['value'] = $edu_section['EduSection']['edu_class_id'];
				$this->ExtForm->input('edu_class_id', $options);
			?>
		]
	});
	
	var EduSectionEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Section'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduSectionEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduSectionEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Section.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduSectionEditWindow.collapsed)
					EduSectionEditWindow.expand(true);
				else
					EduSectionEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduSectionEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduSectionEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduSectionData();
<?php } else { ?>
						RefreshEduSectionData();
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
				EduSectionEditWindow.close();
			}
		}]
	});