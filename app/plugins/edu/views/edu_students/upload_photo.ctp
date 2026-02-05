//<script>
    <?php
        $this->ExtForm->create('EduStudent');
        $this->ExtForm->defineFieldFunctions();
		
		//print_r($edu_student);
    ?>
    var EduStudentUploadPhotoForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        fileUpload: true,
        labelWidth: 150,
        labelAlign: 'right',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'upload_photo')); ?>',
        defaultType: 'textfield',
        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_student['EduStudent']['id'])); ?>,
            <?php 
                $this->ExtForm->create('EduStudent');
                $options = array('anchor' => '95%', 'xtype' => 'textfield', 'fieldLabel' => 'Student ID', 'disabled' => true);
                $options['value'] = $edu_student['EduStudent']['identity_number'];
                $this->ExtForm->input('theidentity_number', $options);
            ?>,
            <?php 
                $options0 = array('anchor' => '95%', 'fieldLabel' => 'Student Full Name', 'disabled' => true, 'required' => false);
                $options0['value'] = $edu_student['EduStudent']['name'];
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
				$this->ExtForm->input('photo_file_name', $optionsf1);
			?>
        ]
    });

    var EduStudentUploadPhotoWindow = new Ext.Window({
        title: '<?php __('Upload Student Photo'); ?>',
        width: 650,
        minWidth: 600,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduStudentUploadPhotoForm,
        tools: [{
					id: 'refresh',
					qtip: 'Reset',
					handler: function() {
						EduStudentUploadPhotoForm.getForm().reset();
					},
					scope: this
				}, {
					id: 'help',
					qtip: 'Help',
					handler: function() {
						Ext.Msg.show({
							title: 'Help',
							buttons: Ext.MessageBox.OK,
							msg: 'This form is used to modify an existing Student.',
							icon: Ext.MessageBox.INFO
						});
					}
				}, {
					id: 'toggle',
					qtip: 'Collapse / Expand',
					handler: function() {
						if (EduStudentUploadPhotoWindow.collapsed)
							EduStudentUploadPhotoWindow.expand(true);
						else
							EduStudentUploadPhotoWindow.collapse(true);
					}
				}],
			buttons: [{
                text: '<?php __('Upload'); ?>',
                handler: function(btn) {
                    EduStudentUploadPhotoForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduStudentUploadPhotoWindow.close();
                            //RefreshEduStudentData();
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
                    EduStudentUploadPhotoWindow.close();
                }
            }]
    });