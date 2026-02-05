//<script>
	<?php
		$this->ExtForm->create('EduOutline');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduOutlineEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduOutlines', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_outline['EduOutline']['id'])); ?>,
			<?php
				$options = array('fieldLabel' => 'Course', 'anchor' => '55%');
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $edu_courses;
				$options['value'] = $edu_outline['EduOutline']['edu_course_id'];
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Outline');
				$options['value'] = $edu_outline['EduOutline']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array('anchor' => '30%');
				$options['value'] = $edu_outline['EduOutline']['list_order'];
				$this->ExtForm->input('list_order', $options);
			?>
		]
	});
	
	var EduOutlineEditWindow = new Ext.Window({
		title: '<?php __('Edit Outline'); ?>',
		width: 700,
		minWidth: 600,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduOutlineEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduOutlineEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Outline.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduOutlineEditWindow.collapsed)
					EduOutlineEditWindow.expand(true);
				else
					EduOutlineEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduOutlineEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduOutlineEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduOutlineData();
<?php } else { ?>
						RefreshEduOutlineData();
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
				EduOutlineEditWindow.close();
			}
		}]
	});