//<script>
    <?php
        $this->ExtForm->create('EduCalendarEventType');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var tree = new Ext.tree.TreePanel({
        title: 'Tasks',
        height: 320,
        width: '100%',
        useArrows: true,
        autoScroll:true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        rootVisible: false,
        frame: true,
        root: {
            nodeType: 'async'
        },
        
        // auto create TreeLoader
        dataUrl: '<?php echo $this->Html->url(array(
            'controller' => 'tasks', 'action' => 'list_data3', 'plugin'=> '')); ?>',
        
        listeners: {
            'checkchange': function(node, checked){
                if(checked){
                    node.getUI().addClass('complete');
                }else{
                    node.getUI().removeClass('complete');
                }
            }
        }
    });

    tree.getRootNode().expand(true);

    var EduCalendarEventTypeAddForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_calendar_event_types', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php
                $options = array();
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options = array();
                $this->ExtForm->input('educational', $options);
            ?>,
            tree,
            <?php
                $options = array('hidden' => '', 'id' => 'data[EduCalendarEventType][Task]');
                $this->ExtForm->input('Task', $options);
            ?>
        ]
    });
		
    var EduCalendarEventTypeAddWindow = new Ext.Window({
        title: '<?php __('Add Calendar Event Type'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduCalendarEventTypeAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduCalendarEventTypeAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Calendar Event Type.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduCalendarEventTypeAddWindow.collapsed)
                    EduCalendarEventTypeAddWindow.expand(true);
                else
                    EduCalendarEventTypeAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                var msg = '', selNodes = tree.getChecked();
                Ext.each(selNodes, function(node){
                    if(msg.length > 0){
                        msg += ',';
                    }
                    msg += node.id;
                });
                Ext.getCmp('data[EduCalendarEventType][Task]').setValue(msg);

                EduCalendarEventTypeAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduCalendarEventTypeAddForm.getForm().reset();
                        RefreshEduCalendarEventTypeData();
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
                var msg = '', selNodes = tree.getChecked();
                Ext.each(selNodes, function(node){
                    if(msg.length > 0){
                        msg += ',';
                    }
                    msg += node.id;
                });
                Ext.getCmp('data[EduCalendarEventType][Task]').setValue(msg);
                EduCalendarEventTypeAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduCalendarEventTypeAddWindow.close();
                        RefreshEduCalendarEventTypeData();
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
                EduCalendarEventTypeAddWindow.close();
            }
        }]
    });
