//<script>
    <?php
        $this->ExtForm->create('Task');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var TaskAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options = array();
                $this->ExtForm->input('name', $options);
            ?>,
            <?php 
                $options = array('anchor' => '70%');
                $this->ExtForm->input('controller', $options);
            ?>,
            <?php 
                $options = array('anchor' => '70%');
                $this->ExtForm->input('action', $options);
            ?>,
            <?php 
                $options = array('anchor' => '70%', 'value' => 'icon-activity');
                $this->ExtForm->input('iconcls', $options);
            ?>,
            <?php 
                $options = array('anchor' => '50%');
                $this->ExtForm->input('list_order', $options);
            ?>,
            <?php 
                $options = array();
                $options['hidden'] = $parent_id;
                $this->ExtForm->input('parent_id', $options);
            ?>
        ]
    });

    var TaskAddWindow = new Ext.Window({
        title: '<?php __('Add New Task'); ?>',
        width: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        collapsible: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: TaskAddForm,

        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                TaskAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Successfully saved!'); ?>');
                        TaskAddForm.getForm().reset();
                        RefreshTaskData();
                    },
                    failure: function(f,a){
                        Ext.Msg.alert('<?php __('Warning'); ?>', a.result.errormsg);
                    }
                });
            }
        }, {
            text: '<?php __('Save & Close'); ?>',
            handler: function(btn){
                TaskAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Successfully saved!'); ?>');
                        TaskAddWindow.close();
                        RefreshTaskData();
                    },
                    failure: function(f,a){
                        Ext.Msg.alert('<?php __('Warning'); ?>', a.result.errormsg);
                    }
                });
            }
        }, {
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                TaskAddWindow.close();
            }
        }]
    });
