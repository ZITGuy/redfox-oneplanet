//<script>
    <?php
        $this->ExtForm->create('EduEventTask');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduEventTaskAddForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_event_tasks', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php
                $options0 = array();
                if (isset($parent_id)) {
                    $options0['hidden'] = $parent_id;
                } else {
                    $options0['items'] = $edu_calendar_event_types;
                }
                $this->ExtForm->input('edu_calendar_event_type_id', $options0);
            ?>,
            <?php
                $options1 = array();
                $this->ExtForm->input('task', $options1);
            ?>,
            <?php
                $options2 = array();
                $this->ExtForm->input('permissions', $options2);
            ?>
        ]
    });

    var EduEventTaskAddWindow = new Ext.Window({
        title: '<?php __('Add Allowed Task during this Event'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduEventTaskAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduEventTaskAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Edu Event Task.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduEventTaskAddWindow.collapsed)
                    EduEventTaskAddWindow.expand(true);
                else
                    EduEventTaskAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                EduEventTaskAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduEventTaskAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
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
        }, {
            text: '<?php __('Save & Close'); ?>',
            handler: function(btn){
                EduEventTaskAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduEventTaskAddWindow.close();
<?php if(isset($parent_id)){ ?>
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
                EduEventTaskAddWindow.close();
            }
        }]
    });
