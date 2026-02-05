//<script>
    <?php
        $this->ExtForm->create('EduCourse');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduCourseEditForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_course['EduCourse']['id'])); ?>,
            <?php
                $options0 = array();
                $options0['items'] = $edu_classes;
                $options0['value'] = $edu_course['EduCourse']['edu_class_id'];
                $this->ExtForm->input('edu_class_id', $options0);
            ?>,
            <?php
                $options1 = array();
                $options1['hidden'] = $parent_id;
                $options1['value'] = $edu_course['EduCourse']['edu_subject_id'];
                $this->ExtForm->input('edu_subject_id', $options1);
            ?>,
            <?php
                $options2 = array();
                $options2['value'] = $edu_course['EduCourse']['description'];
                $this->ExtForm->input('description', $options2);
            ?>,
            <?php
                $options = array('fieldLabel' => 'Min. Mark to Pass');
                $options['value'] = $edu_course['EduCourse']['min_for_pass'];
                $this->ExtForm->input('min_for_pass', $options);
            ?>,
            <?php
                $options = array();
                $options['value'] = $edu_course['EduCourse']['is_mandatory'];
                $this->ExtForm->input('is_mandatory', $options);
            ?>,
            <?php
                $options = array();
                $options['value'] = $edu_course['EduCourse']['is_scale_based'];
                $this->ExtForm->input('is_scale_based', $options);
            ?>
        ]
    });
		
    var EduCourseEditWindow = new Ext.Window({
        title: '<?php __('Edit Course'); ?>',
        width: 500,
        minWidth: 500,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduCourseEditForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    EduCourseEditForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to modify an existing Edu Course.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(EduCourseEditWindow.collapsed)
                        EduCourseEditWindow.expand(true);
                    else
                        EduCourseEditWindow.collapse(true);
                }
        }],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                EduCourseEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduCourseEditWindow.close();
<?php if(isset($parent_id)){ ?>
                        RefreshParentEduCourseData();
<?php } else { ?>
                        RefreshEduCourseData();
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
                EduCourseEditWindow.close();
            }
        }]
    });