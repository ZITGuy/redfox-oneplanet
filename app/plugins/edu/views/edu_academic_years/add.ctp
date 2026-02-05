//<script>
    <?php
        $this->ExtForm->create('EduAcademicYear');
        $this->ExtForm->defineFieldFunctions();
    ?>
    Ext.apply(Ext.form.VTypes, {
        daterange : function(val, field) {
            var date = field.parseDate(val);
            if(!date){
                return false;
            }
            if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
                var start = Ext.getCmp(field.startDateField);
                start.setMaxValue(date);
                //start.setMinValue(new Date(< ?php echo date('Y, m, d, H, i, s') ?>));
                //start.validate();
                this.dateRangeMax = date;
            }
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = Ext.getCmp(field.endDateField);
                end.setMinValue(date);
                //end.validate();
                this.dateRangeMin = date;
            }
            /*
             * Always return true since we're only using this vtype to set the
             * min/max allowed values (these are tested for after the vtype test)
             */
            return true;
        }
    });
    var EduAcademicYearAddForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'eduAcademicYears', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php
                $options = array();
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options = array(
                    'anchor' => '70%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduAcademicYear][start_date]',
                    'vtype' => 'daterange',
                    'endDateField' => 'data[EduAcademicYear][end_date]',
                    'fieldLabel' => 'Start Date',
                    'value' => date('Y-m-d')
                );
                $this->ExtForm->input('start_date', $options);
            ?>,
            <?php
                $options = array(
                    'anchor' => '70%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduAcademicYear][end_date]',
                    'vtype' => 'daterange',
                    'startDateField' => 'data[EduAcademicYear][start_date]',
                    'fieldLabel' => 'End Date',
                    'value' => date('Y-m-d', strtotime('+1 year'))
                );
                $this->ExtForm->input('end_date', $options);
            ?>
        ]
    });
		
    var EduAcademicYearAddWindow = new Ext.Window({
            title: '<?php __('Create Academic Year'); ?>',
            width: 400,
            minWidth: 400,
            autoHeight: true,
            layout: 'fit',
            modal: true,
            resizable: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'right',
            items: EduAcademicYearAddForm,
            tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    EduAcademicYearAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Edu Academic Year.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(EduAcademicYearAddWindow.collapsed)
                        EduAcademicYearAddWindow.expand(true);
                    else
                        EduAcademicYearAddWindow.collapse(true);
                }
            }],
            buttons: [{
                text: '<?php __('Create and Open'); ?>',
                handler: function(btn){
                    EduAcademicYearAddForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f,a){
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduAcademicYearAddWindow.close();
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
                    EduAcademicYearAddWindow.close();
                }
            }]
    });