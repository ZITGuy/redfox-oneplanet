//<script>
    <?php
        $this->ExtForm->create('Task');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var TaskEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $task['Task']['id'])); ?>,
            <?php 
                $options = array();
                $options['value'] = $task['Task']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
            <?php 
                $options = array('anchor' => '70%');
                $options['value'] = $task['Task']['controller'];
                $this->ExtForm->input('controller', $options);
            ?>,
            <?php 
                $options = array('anchor' => '70%');
                $options['value'] = $task['Task']['action'];
                $this->ExtForm->input('action', $options);
            ?>,
            <?php 
                $options = array('anchor' => '70%', 'fieldLabel' => 'Icon Class');
                $options['value'] = $task['Task']['iconcls'];
                $this->ExtForm->input('iconcls', $options);
            ?>,
            <?php 
                $options = array('anchor' => '50%');
                $options['value'] = $task['Task']['list_order'];
                $this->ExtForm->input('list_order', $options);
            ?>,
            <?php 
                $options = array();
                $options['hidden'] = $task['Task']['parent_id'];
                $this->ExtForm->input('parent_id', $options);
            ?>
        ]
    });

    var TaskEditWindow = new Ext.Window({
        title: '<?php __('Edit Task'); ?>',
        width: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: TaskEditForm,

        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                TaskEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Successfully saved!'); ?>');
                        TaskEditWindow.close();
                        RefreshTaskData();
                    },
                    failure: function(f,a){
                        Ext.Msg.alert('<?php __('Warning'); ?>', a.result.errormsg);
                    }
                });
            }
        },{
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                TaskEditWindow.close();
            }
        }]
    });
