//<script>
    <?php
        $this->ExtForm->create('EduStudent');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var EduChangeStudentStatusForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        fileUpload: true,
        labelWidth: 150,
        labelAlign: 'right',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'change_student_status')); ?>',
        defaultType: 'textfield',
        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_student['EduStudent']['id'])); ?>,
            <?php
                $options3 = array('anchor' => '60%', 'fieldLabel' => 'Change Status To', 'xtype'=>'combo');
                $options3['items'] = $statuses;
                $this->ExtForm->input('status_id', $options3);
            ?>,
            <?php
                $options4 = array('anchor' => '90%', 'xtype' => 'textarea', 'fieldLabel' => 'Remark', 'allowBlank' => 'false');
                $this->ExtForm->input('remark', $options4);
            ?>
        ]
    });

    var EduChangeStudentStatusWindow = new Ext.Window({
        title: '<?php __('Change Student Status: '); ?> - <b><?php echo $edu_student['EduStudent']['name'] . ' [' . $edu_student['EduStudent']['identity_number'] . '] Current Status: <i>' . $edu_student['Status']['name'] . '</i>'; ?></b>',
        width: 700,
        minWidth: 600,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduChangeStudentStatusForm,
        tools: [{
					id: 'refresh',
					qtip: 'Reset',
					handler: function() {
						EduChangeStudentStatusForm.getForm().reset();
					},
					scope: this
				}, {
					id: 'help',
					qtip: 'Help',
					handler: function() {
						Ext.Msg.show({
							title: 'Help',
							buttons: Ext.MessageBox.OK,
							msg: 'This form is used to change Student status.',
							icon: Ext.MessageBox.INFO
						});
					}
				}, {
					id: 'toggle',
					qtip: 'Collapse / Expand',
					handler: function() {
						if (EduChangeStudentStatusWindow.collapsed)
							EduChangeStudentStatusWindow.expand(true);
						else
							EduChangeStudentStatusWindow.collapse(true);
					}
				}],
			buttons: [{
                text: '<?php __('Change'); ?>',
                handler: function(btn) {
                    EduChangeStudentStatusForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduChangeStudentStatusWindow.close();
                            RefreshEduStudentData();
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
                    EduChangeStudentStatusWindow.close();
                }
            }]
    });