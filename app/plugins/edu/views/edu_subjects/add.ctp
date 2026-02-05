//<script>
    <?php
        $this->ExtForm->create('EduSubject');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduSubjectAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 150,
        labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_subjects', 'action' => 'add')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array();
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options = array(
                    'enableKeyEvents' => 'true',
                    'anchor' => '99%', 'fieldLabel' => 'Name in Amharic');
                $options['listeners'] = '{
                    keypress: function(tb,e){
                        translateOnKeyPress(e,1);
                    }
                }';
                $this->ExtForm->input('name_am', $options);
            ?>,
            <?php
                $options = array();
                $this->ExtForm->input('description', $options);
            ?>,
            <?php
                $options = array('fieldLabel' => 'Min. Mark to Pass', 'anchor'=>'60%', 'value' => '50');
                $this->ExtForm->input('min_for_pass', $options);
            ?>,
            <?php
                $options = array();
                $this->ExtForm->input('is_mandatory', $options);
            ?>,
            <?php
                $options = array('xtype' => 'combo', 'fieldLabel' => 'Theme', 'items' => array(
                    '-' => 'None', 
                    'green' => 'green', 
                    'red' => 'red', 
                    'blue' => 'blue'
                ));
                $this->ExtForm->input('color', $options);
            ?>            
        ]
    });

    var EduSubjectAddWindow = new Ext.Window({
        title: '<?php __('Add Subject'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduSubjectAddForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function() {
                    EduSubjectAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function() {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Edu Subject.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function() {
                    if (EduSubjectAddWindow.collapsed)
                        EduSubjectAddWindow.expand(true);
                    else
                        EduSubjectAddWindow.collapse(true);
                }
            }],
        buttons: [{
                text: '<?php __('Save'); ?>',
                handler: function(btn) {
                    EduSubjectAddForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduSubjectAddForm.getForm().reset();
    <?php if (isset($parent_id)) { ?>
                                RefreshParentEduSubjectData();
    <?php } else { ?>
                                RefreshEduSubjectData();
    <?php } ?>
                        },
                        failure: function(f, a) {
                            var obj = a.result;
                            ShowErrorBox(obj.errormsg, obj.helpcode);
                        }
                    });
                }
            }, {
                text: '<?php __('Save & Close'); ?>',
                handler: function(btn) {
                    EduSubjectAddForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduSubjectAddWindow.close();
    <?php if (isset($parent_id)) { ?>
                            RefreshParentEduSubjectData();
    <?php } else { ?>
                            RefreshEduSubjectData();
    <?php } ?>
                        },
                        failure: function(f, a) {
                            var obj = a.result;
                            ShowErrorBox(obj.errormsg, obj.helpcode);
                        }
                    });
                }
            }, {
                text: '<?php __('Cancel'); ?>',
                handler: function(btn) {
                    EduSubjectAddWindow.close();
                }
            }]
    });
                