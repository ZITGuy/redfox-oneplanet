//<script>
	<?php
		$this->ExtForm->create('EduOutline');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduOutlineAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduOutlines', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('fieldLabel' => 'Course', 'anchor' => '55%');
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $edu_courses;
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Outline');
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array('anchor' => '30%');
				$this->ExtForm->input('list_order', $options);
			?>
		]
	});
		
	var EduOutlineAddWindow = new Ext.Window({
		title: '<?php __('Add Outline'); ?>',
		width: 700,
		minWidth: 600,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduOutlineAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduOutlineAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Outline.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduOutlineAddWindow.collapsed)
					EduOutlineAddWindow.expand(true);
				else
					EduOutlineAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduOutlineAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduOutlineAddForm.getForm().reset();
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
			text: '<?php __('Save & Close'); ?>',
			handler: function(btn){
				EduOutlineAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduOutlineAddWindow.close();
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
		},{
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduOutlineAddWindow.close();
			}
		}]
	});