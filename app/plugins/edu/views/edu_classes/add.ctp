//<script>
    <?php
    $this->ExtForm->create('EduClass');
    $this->ExtForm->defineFieldFunctions();
    ?>
    var EduClassAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        id: 'EduClassAddForm',
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
        url: '<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'add')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options0 = array();
                $this->ExtForm->input('name', $options0);
            ?>,
            <?php
                $options1 = array('fieldLabel' => 'Class Order', 'anchor'=>'60%', 'value' => '0');
                $this->ExtForm->input('cvalue', $options1);
            ?>,
            <?php
                $options2 = array('fieldLabel' => 'Min. Mark for Promotion', 'anchor'=>'60%', 'value' => '50');
                $this->ExtForm->input('min_for_promotion', $options2);
            ?>,
            <?php
                $options3 = array('fieldLabel' => 'Class Level', 'anchor'=>'90%');
                $options3['items'] = $edu_class_levels;
                $this->ExtForm->input('edu_class_level_id', $options3);
            ?>,
            <?php
                $options4 = array('fieldLabel' => 'Is Uni-Teacher?');
                $this->ExtForm->input('uni_teacher', $options4);
            ?>,
            <?php
                $options4 = array('fieldLabel' => 'Course Items Applicable?');
                $this->ExtForm->input('course_item_enabled', $options4);
            ?>,
            <?php
                $options5 = array('fieldLabel' => 'Grading System Type', 'anchor'=>'90%', 'value' => 'N');
                $options5['xtype'] = 'combo';
                $options5['items'] = array('N' => 'Numeric', 'A' => 'GPA', 'G' => 'Observation');
                $options5['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        var rank_display = Ext.getCmp('data[EduClass][rank_display]');
                        var rank_display_upto = Ext.getCmp('data[EduClass][rank_display_upto]');
                        var grading_type = combo.getValue();
                        if(grading_type != 'G'){
                            rank_display.enable();
                        } else {
                            rank_display.disable();
                            rank_display_upto.disable();
                        }
                    }
                }";
                $this->ExtForm->input('grading_type', $options5);
            ?>,
            <?php
                $options6 = array('fieldLabel' => 'Rank Display', 'anchor'=>'70%', 'value' => 'D', 'id' => 'data[EduClass][rank_display]');
                $options6['xtype'] = 'combo';
                $options6['items'] = array('D' => 'Display for All', 'N' => 'Do not display', 'U' => 'Display Upto');
                $options6['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        var rank_display_upto = Ext.getCmp('data[EduClass][rank_display_upto]');
                        var display_mode = combo.getValue();
                        if(display_mode == 'U'){
                            rank_display_upto.enable();
                        } else {
                            rank_display_upto.disable();
                        }
                    }
                }";
                $this->ExtForm->input('rank_display', $options6);
            ?>,
            <?php
                $options7 = array(
                    'fieldLabel' => 'Rank Display Upto', 
                    'anchor'=>'50%', 
                    'disabled' => true,
                    'id' => 'data[EduClass][rank_display_upto]'
                );
                $this->ExtForm->input('rank_display_upto', $options7);
            ?>
        ]
    });

    var EduClassAddWindow = new Ext.Window({
        title: '<?php __('Add Class'); ?>',
        width: 500,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduClassAddForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function() {
                    EduClassAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function() {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Class.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function() {
                    if (EduClassAddWindow.collapsed)
                        EduClassAddWindow.expand(true);
                    else
                        EduClassAddWindow.collapse(true);
                }
            }],
        buttons: [{
                text: '<?php __('Save'); ?>',
                handler: function(btn) {
                    EduClassAddForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduClassAddForm.getForm().reset();
    <?php if (isset($parent_id)) { ?>
                                RefreshParentEduClassData();
    <?php } else { ?>
                                RefreshEduClassData();
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
                    if (EduClassAddForm.getForm().isValid()) {
                        var sb = Ext.getCmp('form-statusbar');
                        sb.showBusy('Saving form...');
                        EduClassAddForm.getEl().mask();
                        EduClassAddForm.getForm().submit({
                            waitMsg: '<?php __('Submitting your data...'); ?>',
                            waitTitle: '<?php __('Wait Please...'); ?>',
                            success: function(f, a) {
                                Ext.Msg.show({
                                    title: '<?php __('Success'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: a.result.msg,
                                    icon: Ext.MessageBox.INFO
                                });
                                EduClassAddWindow.close();
    <?php if (isset($parent_id)) { ?>
                                    RefreshParentEduClassData();
    <?php } else { ?>
                                    RefreshEduClassData();
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
                }
            }, {
                text: '<?php __('Cancel'); ?>',
                handler: function(btn) {
                    EduClassAddWindow.close();
                }
            }],
        bbar: new Ext.ux.StatusBar({
            id: 'form-statusbar',
            defaultText: '',
            plugins: new Ext.ux.ValidationStatus({form: 'EduClassAddForm'})
        })
    });