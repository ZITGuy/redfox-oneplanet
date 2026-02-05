//<script>
	<?php
		$this->ExtForm->create('User');
		$this->ExtForm->defineFieldFunctions();
	?>
				
	var UserChangePasswordForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'change_password')); ?>',
		defaultType: 'textfield',
		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $user['User']['id'])); ?>,
			<?php 
				$options1 = array('inputType' => 'password', 'anchor' => '70%');
				$this->ExtForm->input('new_password', $options1);
			?>,
			<?php 
				$options1 = array('inputType' => 'password', 'anchor' => '70%');
				$this->ExtForm->input('confirm_password', $options1);
			?>
		]
	});

    var UserChangePasswordWindow = new Ext.Window({
		title: '<?php __('Change User Password'); ?> User: <?php echo $user['User']['username']; ?>',
		width: 500,
		minWidth: 500,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: false,
		plain: true,
		bodyStyle: 'padding:5px;',
		buttonAlign: 'right',
		items: UserChangePasswordForm,

		buttons: [{
            text: '<?php __('Save'); ?>',
            //disabled: true,
            handler: function(btn){
                UserChangePasswordForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        UserChangePasswordWindow.close();
                        RefreshUserData();
                    },
                    failure: function(f,a){
                        switch (a.failureType) {
                            case Ext.form.Action.CLIENT_INVALID:
                                Ext.Msg.show({
                                    title: '<?php __('Failure'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: 'Form fields may not be submitted with invalid values' ,
                                    icon: Ext.MessageBox.ERROR
                                });
                                break;
                            case Ext.form.Action.CONNECT_FAILURE:
                                Ext.Msg.show({
                                    title: '<?php __('Failure'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: 'Ajax communication failed' ,
                                    icon: Ext.MessageBox.ERROR
                                });
                                break;
                            case Ext.form.Action.SERVER_INVALID:
                                Ext.Msg.show({
                                    title: '<?php __('Failure'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: action.result.msg ,
                                    icon: Ext.MessageBox.ERROR
                                });
                        }
                    }
                });
            }
		}, {
            text: '<?php __('Reset'); ?>',
            handler: function(btn){
                UserChangePasswordForm.getForm().reset();
            }
		}, {
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                UserChangePasswordWindow.close();
            }
		}]
    });
