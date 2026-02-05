//<script>
    <?php
        $this->ExtForm->create('EduPaymentSchedule');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduPaymentScheduleAddScheduleForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 1,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'schedule')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options = array();
                if(isset($parent_id)){
                    $options['hidden'] = $parent_id;
                } else {
                    $options['items'] = $edu_classes;
                }
                $this->ExtForm->input('edu_class_id', $options);
            ?>,
            <?php 
                $options = array(
					'fieldLabel' => '',
					'value' => 'Amount depends on the student batch', 
					'disabled' => true);
                $this->ExtForm->input('amount', $options);
            ?>			
        ]
    });
		
    var EduPaymentScheduleAddScheduleWindow = new Ext.Window({
        title: '<?php __('Schedule Payments'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduPaymentScheduleAddScheduleForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    EduPaymentScheduleAddScheduleForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Payment Schedule.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(EduPaymentScheduleAddScheduleWindow.collapsed)
                        EduPaymentScheduleAddScheduleWindow.expand(true);
                    else
                        EduPaymentScheduleAddScheduleWindow.collapse(true);
                }
            }
        ],
        buttons: [{
            text: '<?php __('Create Schedule'); ?>',
            handler: function(btn){
		EduPaymentScheduleAddScheduleForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduPaymentScheduleAddScheduleWindow.close();
<?php if(isset($parent_id)){ ?>
                        RefreshParentEduPaymentScheduleData();
<?php } else { ?>
                        RefreshEduPaymentScheduleData();
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
                EduPaymentScheduleAddScheduleWindow.close();
            }
        }]
    });
