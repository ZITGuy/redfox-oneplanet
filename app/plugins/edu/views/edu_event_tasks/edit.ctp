//<script>
    <?php
        $this->ExtForm->create('EduEventTask');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduEventTaskEditForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'eduEventTasks', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_event_task['EduEventTask']['id'])); ?>,
            <?php
                $options0 = array();
                if (isset($parent_id)) {
                    $options0['hidden'] = $parent_id;
                } else {
                    $options0['items'] = $edu_calendar_event_types;
                }
                $options0['value'] = $edu_event_task['EduEventTask']['edu_calendar_event_type_id'];
                $this->ExtForm->input('edu_calendar_event_type_id', $options0);
            ?>,
            <?php
                $options1 = array();
                $options1['value'] = $edu_event_task['EduEventTask']['task'];
                $this->ExtForm->input('task', $options1);
            ?>,
            <?php
                $options2 = array();
                $options2['value'] = $edu_event_task['EduEventTask']['permissions'];
                $this->ExtForm->input('permissions', $options2);
            ?>
        ]
    });

    var EduEventTaskEditWindow = new Ext.Window({
        title: '<?php __('Edit Allowed Task during this Event'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduEventTaskEditForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduEventTaskEditForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to modify an existing Edu Event Task.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduEventTaskEditWindow.collapsed)
                    EduEventTaskEditWindow.expand(true);
                else
                    EduEventTaskEditWindow.collapse(true);
            }
        }],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                EduEventTaskEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduEventTaskEditWindow.close();
<?php if (isset($parent_id)) { ?>
                        RefreshParentEduEventTaskData();
<?php } else { ?>
                        RefreshEduEventTaskData();
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
                EduEventTaskEditWindow.close();
            }
        }]
    });
