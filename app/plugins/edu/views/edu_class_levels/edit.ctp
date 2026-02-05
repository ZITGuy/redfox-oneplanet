//<script>
	<?php
		$this->ExtForm->create('EduClassLevel');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduClassLevelEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduClassLevels', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_class_level['EduClassLevel']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_class_level['EduClassLevel']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array('xtype' => 'textarea');
									$options['value'] = $edu_class_level['EduClassLevel']['remark'];
				$this->ExtForm->input('remark', $options);
			?>
		]
	});
	
	var EduClassLevelEditWindow = new Ext.Window({
		title: '<?php __('Edit Class Level'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduClassLevelEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduClassLevelEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Class Level.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduClassLevelEditWindow.collapsed)
					EduClassLevelEditWindow.expand(true);
				else
					EduClassLevelEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduClassLevelEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduClassLevelEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduClassLevelData();
<?php } else { ?>
						RefreshEduClassLevelData();
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
				EduClassLevelEditWindow.close();
			}
		}]
	});
