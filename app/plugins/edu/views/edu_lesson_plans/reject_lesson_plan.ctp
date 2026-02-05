//<script>
    <?php
        $this->ExtForm->create('EduLessonPlan');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var RejectLessonPlanForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 170,
        labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
        url:'<?php echo $this->Html->url(array(
            'controller' => 'edu_lesson_plans', 'action' => 'reject_lesson_plan')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_lesson_plan['EduLessonPlan']['id'])); ?>,
            <?php
                $options = array('fieldLabel' => 'Reason for Reject', 'xtype' => 'textarea');
                $options['value'] = ($edu_lesson_plan['EduLessonPlan']['reason'] == 'Created')? '':
                    $edu_lesson_plan['EduLessonPlan']['reason'];
                $this->ExtForm->input('reason', $options);
            ?>,
            <?php
                $options = array('fieldLabel' => 'Return it for Ammendments', 'xtype' => 'checkbox');
                $this->ExtForm->input('returned', $options);
            ?>
        ]
    });
		
    var RejectLessonPlanWindow = new Ext.Window({
        title: '<?php __('Reject the Lesson plan'); ?>',
        width: 600,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: RejectLessonPlanForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    RejectLessonPlanForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to reject an existing Lesson Plan.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(RejectLessonPlanWindow.collapsed)
                        RejectLessonPlanWindow.expand(true);
                    else
                        RejectLessonPlanWindow.collapse(true);
                }
            }],
        buttons: [ {
            text: '<?php __('Ok'); ?>',
            handler: function(btn){
                RejectLessonPlanForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        RejectLessonPlanWindow.close();
                        RefreshEduLessonPlanData();
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
                RejectLessonPlanWindow.close();
            }
        }]
    });
