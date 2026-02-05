//<script>
    <?php
        $this->ExtForm->create('EduCourse');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduCourseAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 190,
        labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'add')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Class');
                if (isset($parent_id))
                    $options['hidden'] = $parent_id;
                else
                    $options['items'] = $edu_classes;
                $this->ExtForm->input('edu_class_id', $options);
            ?>,
            <?php
                $options = array('fieldLabel' => 'Subject');
                $options['items'] = $edu_subjects;
                $class = $edu_class['EduClass']['name'];
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        var desc = Ext.getCmp('data[EduCourse][description]');
                        var record = combo.findRecord(combo.valueField, combo.getValue());
                        var subject  = record? record.get(combo.displayField) : combo.valueNotFoundText;
                        desc.setValue(subject + ' for $class');
                    }
                }";
                $this->ExtForm->input('edu_subject_id', $options);
            ?>,
            <?php
                $options = array('id' => 'data[EduCourse][description]');
                $this->ExtForm->input('description', $options);
            ?>,
            <?php
                $options = array('fieldLabel' => 'Min. Mark to Pass');
                $this->ExtForm->input('min_for_pass', $options);
            ?>,
            <?php
                $options = array();
                $this->ExtForm->input('is_mandatory', $options);
            ?>,
            <?php
                $options = array();
                $this->ExtForm->input('is_scale_based', $options);
            ?>
        ]
    });

    var EduCourseAddWindow = new Ext.Window({
        title: '<?php __('Add Course'); ?>',
        width: 500,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduCourseAddForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function() {
                    EduCourseAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function() {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Edu Course.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function() {
                    if (EduCourseAddWindow.collapsed)
                        EduCourseAddWindow.expand(true);
                    else
                        EduCourseAddWindow.collapse(true);
                }
            }],
        buttons: [{
                text: '<?php __('Save'); ?>',
                handler: function(btn) {
                    EduCourseAddForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduCourseAddForm.getForm().reset();
    <?php if (isset($parent_id)) { ?>
                            RefreshParentEduCourseData();
    <?php } else { ?>
                            RefreshEduCourseData();
    <?php } ?>
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
                text: '<?php __('Save & Close'); ?>',
                handler: function(btn) {
                    EduCourseAddForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduCourseAddWindow.close();
    <?php if (isset($parent_id)) { ?>
                            RefreshParentEduCourseData();
    <?php } else { ?>
                            RefreshEduCourseData();
    <?php } ?>
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
                    EduCourseAddWindow.close();
                }
            }]
    });