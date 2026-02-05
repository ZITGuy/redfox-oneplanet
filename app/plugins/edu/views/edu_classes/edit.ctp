//<script>
    <?php
        $this->ExtForm->create('EduClass');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduClassEditForm = new Ext.form.FormPanel({
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
        url: '<?php echo $this->Html->url(array('controller' => 'edu_classes', 'action' => 'edit')); ?>',
        defaultType: 'textfield',
        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_class['EduClass']['id'])); ?>,
            <?php
                $options0 = array();
                $options0['value'] = $edu_class['EduClass']['name'];
                $this->ExtForm->input('name', $options0);
            ?>,
            <?php
                $options1 = array('fieldLabel' => 'Class Order');
                $options1['value'] = $edu_class['EduClass']['cvalue'];
                $this->ExtForm->input('cvalue', $options1);
            ?>,
            <?php
                $options2 = array('fieldLabel' => 'Min. Mark for Promotion', 'anchor'=>'60%');
                $options2['value'] = $edu_class['EduClass']['min_for_promotion'];
                $this->ExtForm->input('min_for_promotion', $options2);
            ?>,
            <?php
                $options3 = array('fieldLabel' => 'Class Level', 'anchor'=>'90%');
                $options3['value'] = $edu_class['EduClass']['edu_class_level_id'];
                $options3['items'] = $edu_class_levels;
                $this->ExtForm->input('edu_class_level_id', $options3);
            ?>,
            <?php
                $options4 = array('fieldLabel' => 'Is Uni-Teacher?');
                $options4['value'] = $edu_class['EduClass']['uni_teacher'];
                $this->ExtForm->input('uni_teacher', $options4);
            ?>,
            <?php
                $options4 = array('fieldLabel' => 'Course Items Applicable?');
                $options4['value'] = $edu_class['EduClass']['course_item_enabled'];
                $this->ExtForm->input('course_item_enabled', $options4);
            ?>,
            <?php
                $options5 = array('fieldLabel' => 'Grading System Type', 'anchor'=>'90%');
                $options5['value'] = $edu_class['EduClass']['grading_type'];
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
                $options6['value'] = $edu_class['EduClass']['rank_display'];
                $options6['items'] = array('D' => 'Display for All', 'N' => 'Do not display', 'U' => 'Display Upto');
                $options6['disabled'] = ($edu_class['EduClass']['grading_type'] == 'G'? true: false);
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
                    'value' => $edu_class['EduClass']['rank_display_upto'],
                    'id' => 'data[EduClass][rank_display_upto]'
                );
                $this->ExtForm->input('rank_display_upto', $options7);
            ?>
        ]
    });

    var EduClassEditWindow = new Ext.Window({
        title: '<?php __('Edit Class'); ?>',
        width: 500,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduClassEditForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function() {
                    EduClassEditForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function() {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to modify an existing Class.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function() {
                    if (EduClassEditWindow.collapsed)
                        EduClassEditWindow.expand(true);
                    else
                        EduClassEditWindow.collapse(true);
                }
            }],
        buttons: [{
                text: '<?php __('Save'); ?>',
                handler: function(btn) {
                    EduClassEditForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduClassEditWindow.close();
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
                text: '<?php __('Cancel'); ?>',
                handler: function(btn) {
                    EduClassEditWindow.close();
                }
            }]
    });