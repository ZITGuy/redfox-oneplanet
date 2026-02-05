//<script>
    <?php
        $this->ExtForm->create('EduAcademicYear');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var EduAcademicYearEditForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_academic_years', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_academic_year['EduAcademicYear']['id'])); ?>,
            <?php
                $options = array();
                $options['value'] = $edu_academic_year['EduAcademicYear']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options = array();
				if ($edu_academic_year['EduAcademicYear']['status_id'] == 1) {
					$options['readOnly'] = true;
					$options['disabled'] = true;
				}
                $options['value'] = $edu_academic_year['EduAcademicYear']['start_date'];
                $this->ExtForm->input('start_date', $options);
            ?>,
            <?php
                $options = array();
				$min_last_date = $edu_academic_year['EduAcademicYear']['end_date'];
				foreach ($edu_quarters as $q) {
					$min_last_date = $q['EduQuarter']['end_date'];
				}
				$options['minValue'] = "'" . $min_last_date . "'";
                $options['value'] = $edu_academic_year['EduAcademicYear']['end_date'];
                $this->ExtForm->input('end_date', $options);
            ?>
        ]
    });

    var EduAcademicYearEditWindow = new Ext.Window({
        title: '<?php __('Edit Academic Year'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduAcademicYearEditForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduAcademicYearEditForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to modify an existing Academic Year.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduAcademicYearEditWindow.collapsed)
                    EduAcademicYearEditWindow.expand(true);
                else
                    EduAcademicYearEditWindow.collapse(true);
            }
        }],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                EduAcademicYearEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduAcademicYearEditWindow.close();
<?php if(isset($parent_id)){ ?>
                        RefreshParentEduAcademicYearData();
<?php } else { ?>
                        RefreshEduAcademicYearData();
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
                EduAcademicYearEditWindow.close();
            }
        }]
    });