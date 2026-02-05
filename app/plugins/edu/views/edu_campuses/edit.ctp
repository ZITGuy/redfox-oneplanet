//<script>
	<?php
		$this->ExtForm->create('EduCampus');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduCampusEditForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_campus['EduCampus']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_campus['EduCampus']['name'];
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_campus['EduCampus']['address'];
				$this->ExtForm->input('address', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_campus['EduCampus']['telephone'];
				$this->ExtForm->input('telephone', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_campus['EduCampus']['principal'];
				$this->ExtForm->input('principal', $options);
			?>
			
		]
	});
	
	var EduCampusEditWindow = new Ext.Window({
		title: '<?php __('Edit Campus'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduCampusEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduCampusEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Campus.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduCampusEditWindow.collapsed)
					EduCampusEditWindow.expand(true);
				else
					EduCampusEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduCampusEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduCampusEditWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduCampusData();
<?php } else { ?>
						RefreshEduCampusData();
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
				EduCampusEditWindow.close();
			}
		}]
	});