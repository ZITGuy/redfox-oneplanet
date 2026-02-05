//<script>
    <?php
        $this->ExtForm->create('EduCalendarEventType');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var tree = new Ext.tree.TreePanel({
        title: 'Tasks',
        height: 320,
        width: '100%',
        useArrows:true,
        autoScroll:true,
        animate:true,
        enableDD:true,
        containerScroll: true,
        rootVisible: false,
        frame: true,
        root: {
            nodeType: 'async'
        },
        
        // auto create TreeLoader
        dataUrl: '<?php echo $this->Html->url(array(
            'controller' => 'tasks', 'action' => 'list_data3',
            $edu_calendar_event_type['EduCalendarEventType']['id'], 'plugin'=> '')); ?>',
        
        listeners: {
            'checkchange': function(node, checked){
                if (checked) {
                    node.getUI().addClass('complete');
                } else {
                    node.getUI().removeClass('complete');
                }
            }
        }
    });
    tree.getRootNode().expand(true);

    var EduCalendarEventTypeEditForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_calendar_event_types', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array(
                'hidden' => $edu_calendar_event_type['EduCalendarEventType']['id'])); ?>,
            <?php
                $options = array();
                $options['value'] = $edu_calendar_event_type['EduCalendarEventType']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options = array();
                $options['value'] = $edu_calendar_event_type['EduCalendarEventType']['educational'];
                $this->ExtForm->input('educational', $options);
            ?>,
            tree,
            <?php
                $options = array('hidden' => '', 'id' => 'data[EduCalendarEventType][Task]');
                $this->ExtForm->input('Task', $options);
            ?>
        ]
    });
		
    var EduCalendarEventTypeEditWindow = new Ext.Window({
        title: '<?php __('Edit Calendar Event Type'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduCalendarEventTypeEditForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduCalendarEventTypeEditForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to modify an existing Edu Calendar Event Type.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduCalendarEventTypeEditWindow.collapsed)
                    EduCalendarEventTypeEditWindow.expand(true);
                else
                    EduCalendarEventTypeEditWindow.collapse(true);
            }
        }],
        buttons: [ {
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
                EduCalendarEventTypeEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduCalendarEventTypeEditWindow.close();
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
                EduCalendarEventTypeEditWindow.close();
            }
        }]
    });
