//<script>
    <?php
        $this->ExtForm->create('EduQuarter');
        $this->ExtForm->defineFieldFunctions();
    ?>
    // Add the additional 'advanced' VTypes
    Ext.apply(Ext.form.VTypes, {
        daterange : function(val, field) {
            var date = field.parseDate(val);
           
            if(!date){
                return false;
            }
            if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
                var start = Ext.getCmp(field.startDateField);
                start.setMaxValue(date);
                start.setMinValue('<?php echo $edu_academic_year['EduAcademicYear']['start_date']; ?>');
                this.dateRangeMax = date;
            }
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = Ext.getCmp(field.endDateField);
                end.setMinValue(date);
                end.setMaxValue('<?php echo $edu_academic_year['EduAcademicYear']['end_date']; ?>');
                this.dateRangeMin = date;
            }
            /*
             * Always return true since we're only using this vtype to set the
             * min/max allowed values (these are tested for after the vtype test)
             */
            return true;
        }
    });

    function initializeDateMargins() {
        var startDt = Ext.getCmp('data[EduQuarter][start_date]');
        var endDt = Ext.getCmp('data[EduQuarter][end_date]');
<?php
        $applicable_start = $edu_academic_year['EduAcademicYear']['start_date'];
        $applicable_end = $edu_academic_year['EduAcademicYear']['end_date'];

        foreach ($edu_quarters as $q) {
			$q = $q['EduQuarter'];
            if ($q['start_date'] == $applicable_start) {
                $applicable_start = date('Y-m-d', strtotime($q['end_date'] . ' +1 day'));
            } else {
                // there is day gap here
                $applicable_end = date('Y-m-d', strtotime($q['start_date'] . ' -1 day'));
                break;
            }
        }
        ?>
        endDt.setMinValue('<?php echo $applicable_start; ?>');
        endDt.setMaxValue('<?php echo $applicable_end; ?>');
        
        startDt.setMinValue('<?php echo $applicable_start; ?>');
        startDt.setMaxValue('<?php echo $applicable_end; ?>');
        
        <?php
        if ($applicable_start >= $applicable_end) {
        ?>
        Ext.Msg.show({
            title: 'Ooooops!',
            buttons: Ext.MessageBox.OK,
            msg: 'All days in the academic year are occupied.',
            icon: Ext.MessageBox.WARNING
        });
        EduQuarterAddWindow.close();
        <?php
        }
        ?>
    }
    
    var EduQuarterAddForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php
                $options1 = array(
                        'value' => (count($edu_academic_year['EduQuarter']) == 0? 'Preparatory Quarter': '')
                    );
                $this->ExtForm->input('name', $options1);
            ?>,
            <?php
                $options1 = array(
                    'anchor' => '50%',
                    'value' => (count($edu_academic_year['EduQuarter']) == 0? 'PRP': '')
                    );
                $this->ExtForm->input('short_name', $options1);
            ?>,
            <?php
                $options2 = array(
                    'anchor' => '70%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduQuarter][start_date]',
                    'vtype' => 'daterange',
					'disabledDays' => '[0, 6]',
					'disabledDaysText' => 'This is weekend',
                    'readOnly' => (count($edu_academic_year['EduQuarter']) == 0? true: false),
                    'endDateField' => 'data[EduQuarter][end_date]',
                    'fieldLabel' => 'Start Date',
                    'value' => $applicable_start
                );
                $this->ExtForm->input('start_date', $options2);
            ?>,
            <?php
                $options3 = array(
                    'anchor' => '70%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduQuarter][end_date]',
                    'vtype' => 'daterange',
					'disabledDays' => '[0, 6]',
					'disabledDaysText' => 'This is weekend',
                    'startDateField' => 'data[EduQuarter][start_date]',
                    'fieldLabel' => 'End Date',
                    'value' => $edu_academic_year['EduAcademicYear']['end_date']
                );
                $this->ExtForm->input('end_date', $options3);
            ?>,
            <?php
                $options = array(
                    'xtype' => 'combo',
                    'value' => 'N',
                    'fieldLabel' => 'Quarter Type',
                    'items' => array(
                        'E' => 'Educational',
                        'N' => 'Non-Educational'
                    ),
                    'readOnly' => (count($edu_academic_year['EduQuarter']) == 0? true: false)
                );
                $this->ExtForm->input('quarter_type', $options);
            ?>,
            <?php
                $options = array();
                if (isset($parent_id)) {
                    $options['hidden'] = $parent_id;
                } else {
                    $options['items'] = $edu_academic_years;
                }
                $this->ExtForm->input('edu_academic_year_id', $options);
            ?>
        ]
    });
    
    
    var EduQuarterAddWindow = new Ext.Window({
        title: '<?php __('Add Quarter'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduQuarterAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduQuarterAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Edu Quarter.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduQuarterAddWindow.collapsed)
                    EduQuarterAddWindow.expand(true);
                else
                    EduQuarterAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Create'); ?>',
            handler: function(btn){
                EduQuarterAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduQuarterAddWindow.close();
<?php if (isset($parent_id)) { ?>
                        RefreshParentEduQuarterData();
<?php } else { ?>
                        RefreshEduQuarterData();
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
        }, {
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                EduQuarterAddWindow.close();
            }
        }]
    });
