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
        
        $applicable_start = $edu_quarter['EduQuarter']['start_date'];
        $applicable_end = $edu_quarter['EduAcademicYear']['end_date'];
        
        // lets go for the start
        $new_end = $applicable_start;
        foreach ($edu_quarters as $q) {
			$q = $q['EduQuarter'];
            if ($q['end_date'] < $applicable_start
                    && $q['id'] != $edu_quarter['EduQuarter']['id']) {
                $new_end = $q['end_date'];
            }
        }
        $new_applicable_start = ($edu_quarter['EduQuarter']['start_date'] == $new_end)?
            $new_end: date('Y-m-d', strtotime($new_end . ' +1 day'));
        
        // lets go for the end
        $new_start = $applicable_end;
        foreach ($edu_academic_year['EduQuarter'] as $q) {
            if ($q['start_date'] > $applicable_end
                    && $q['id'] != $edu_quarter['EduQuarter']['id']) {
                $new_start = $q['start_date'];
                break;
            }
        }
        $new_applicable_end = ($edu_quarter['EduQuarter']['end_date'] == $new_start)?
            $new_start: date('Y-m-d', strtotime($new_start));
        
		// new way
		$end_d = $edu_quarter['EduQuarter']['end_date'];
		$min_start_d = date('Y-m-d', strtotime($edu_quarter['EduAcademicYear']['end_date']));
		foreach ($edu_academic_year['EduQuarter'] as $q) {
            if ($q['start_date'] > $end_d && $min_start_d > $q['start_date']) {
                $min_start_d = $q['start_date'];
            }
        }
		$new_applicable_end = date('Y-m-d', strtotime($min_start_d . ' -1 day'));
		
        ?>
        endDt.setMinValue('<?php echo $new_applicable_start; ?>');
        endDt.setMaxValue('<?php echo $new_applicable_end; ?>');
        
        startDt.setMinValue('<?php echo $new_applicable_start; ?>');
        startDt.setMaxValue('<?php echo $new_applicable_end; ?>');
    }
    
    var EduQuarterEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'eduQuarters', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_quarter['EduQuarter']['id'])); ?>,
            <?php
                $options = array();
                $options['value'] = $edu_quarter['EduQuarter']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
            <?php
                $options1 = array('anchor' => '50%');
                $options1['value'] = $edu_quarter['EduQuarter']['short_name'];
                $this->ExtForm->input('short_name', $options1);
            ?>,
            <?php
                $options = array(
                    'anchor' => '70%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduQuarter][start_date]',
                    'vtype' => 'daterange',
                    'readOnly' => ($edu_quarter['EduQuarter']['start_date'] <= date('Y-m-d')? true: false),
                    'endDateField' => 'data[EduQuarter][end_date]',
                    'fieldLabel' => 'Start Date',
                    'value' => $edu_quarter['EduQuarter']['start_date']
                );
                $this->ExtForm->input('start_date', $options);
            ?>,
            <?php
                $options = array(
                    'anchor' => '70%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduQuarter][end_date]',
                    'vtype' => 'daterange',
                    'readOnly' => ($edu_quarter['EduQuarter']['end_date'] <= date('Y-m-d')? true: false),
                    'startDateField' => 'data[EduQuarter][start_date]',
                    'fieldLabel' => 'End Date',
                    'value' => $edu_quarter['EduQuarter']['end_date']
                );
                $this->ExtForm->input('end_date', $options);
            ?>,
            <?php
                $options = array('xtype' => 'combo', 'value' => 'E', 'fieldLabel' => 'Quarter Type', 'items' => array(
                        'E' => 'Educational',
                        'N' => 'Non-Educational'
                    ),
                    'readOnly' => ($edu_quarter['EduQuarter']['start_date'] <= date('Y-m-d')? true: false)
                );
                $options['value'] = $edu_quarter['EduQuarter']['quarter_type'];
                $this->ExtForm->input('quarter_type', $options);
            ?>,
            <?php
                $options = array();
                if (isset($parent_id)) {
                    $options['hidden'] = $parent_id;
                } else {
                    $options['items'] = $edu_academic_years;
                }
                $options['value'] = $edu_quarter['EduQuarter']['edu_academic_year_id'];
                $this->ExtForm->input('edu_academic_year_id', $options);
            ?>
        ]
    });
		
    var EduQuarterEditWindow = new Ext.Window({
        title: '<?php __('Edit Quarter'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduQuarterEditForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                    EduQuarterEditForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to modify an existing Edu Quarter.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduQuarterEditWindow.collapsed)
                    EduQuarterEditWindow.expand(true);
                else
                    EduQuarterEditWindow.collapse(true);
            }
        }],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
				if(EduQuarterEditForm.getForm().isValid()) {
					EduQuarterEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
								icon: Ext.MessageBox.INFO
							});
							EduQuarterEditWindow.close();
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
				} else {
					Ext.Msg.show({
						title: '<?php __('Ooops!'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: '<?php __('Invalid input, Please provide all required fields.'); ?>',
						icon: Ext.MessageBox.ERROR
					});
				}
            }
        }, {
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                EduQuarterEditWindow.close();
            }
        }]
    });
