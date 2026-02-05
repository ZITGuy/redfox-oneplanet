//<script>
    <?php
        $this->ExtForm->create('EduTeacher');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduTeacherUploadPhotoForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        fileUpload: true,
        labelWidth: 150,
        labelAlign: 'right',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'upload_photo')); ?>',
        defaultType: 'textfield',
        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_teacher['EduTeacher']['id'])); ?>,
            <?php 
				$pd = $edu_teacher['EduTeacher'];
                $options0 = array('anchor' => '95%', 'fieldLabel' => 'Full Name', 'disabled' => true, 'required' => false);
                $options0['value'] = $edu_teacher['User']['Person']['first_name'] . ' ' . $edu_teacher['User']['Person']['middle_name'] . ' ' . $edu_teacher['User']['Person']['last_name'];
                $this->ExtForm->input('name', $options0);
            ?>,
			<?php
				$optionsf1 = array(
					'anchor' => '60%',
					'id' => 'form-file',
					'xtype' => 'fileuploadfield',
					'fieldLabel' => 'Photo File',
					'buttonText' => '',
					'emptyText' => 'Select a Photo',
					'buttonCfg' => "{
						iconCls: 'upload-icon'
					}"
				);
				$this->ExtForm->input('photo', $optionsf1);
			?>
        ]
    });

    var EduTeacherUploadPhotoWindow = new Ext.Window({
        title: '<?php __('Upload Teacher Photo'); ?>',
        width: 650,
        minWidth: 600,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduTeacherUploadPhotoForm,
        tools: [{
					id: 'refresh',
					qtip: 'Reset',
					handler: function() {
						EduTeacherUploadPhotoForm.getForm().reset();
					},
					scope: this
				}, {
					id: 'help',
					qtip: 'Help',
					handler: function() {
						Ext.Msg.show({
							title: 'Help',
							buttons: Ext.MessageBox.OK,
							msg: 'This form is used to modify an existing Parent.',
							icon: Ext.MessageBox.INFO
						});
					}
				}, {
					id: 'toggle',
					qtip: 'Collapse / Expand',
					handler: function() {
						if (EduTeacherUploadPhotoWindow.collapsed)
							EduTeacherUploadPhotoWindow.expand(true);
						else
							EduTeacherUploadPhotoWindow.collapse(true);
					}
				}],
			buttons: [{
                text: '<?php __('Upload'); ?>',
                handler: function(btn) {
                    EduTeacherUploadPhotoForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduTeacherUploadPhotoWindow.close();
                        },
                        failure: function(f, a) {
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
                handler: function(btn) {
                    EduTeacherUploadPhotoWindow.close();
                }
            }]
    });