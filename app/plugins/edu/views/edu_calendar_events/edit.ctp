//<script>
    <?php
        $this->ExtForm->create('EduCalendarEvent');
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
                start.setMinValue('<?php echo $edu_quarter['EduQuarter']['start_date']; ?>');
                //start.validate();
                this.dateRangeMax = date;
            }
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = Ext.getCmp(field.endDateField);
                end.setMinValue(date);
                end.setMaxValue('<?php echo $edu_quarter['EduQuarter']['end_date']; ?>');
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
	
	function initializeDateMargins() {
        var startDt = Ext.getCmp('data[EduCalendarEvent][start_date]');
        var endDt = Ext.getCmp('data[EduCalendarEvent][end_date]');
        
        endDt.setMinValue('<?php echo $edu_quarter['EduQuarter']['start_date']; ?>');
        endDt.setMaxValue('<?php echo $edu_quarter['EduQuarter']['end_date']; ?>');
        
        startDt.setMinValue('<?php echo $edu_quarter['EduQuarter']['start_date']; ?>');
        startDt.setMaxValue('<?php echo $edu_quarter['EduQuarter']['end_date']; ?>');
    }
	
    var EduCalendarEventEditForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'eduCalendarEvents', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_calendar_event['EduCalendarEvent']['id'])); ?>,
            <?php
                $options = array('fieldLabel'=>'Event Type', 'anchor' => '70%');
                $options['items'] = $edu_calendar_event_types;
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        var desc = Ext.getCmp('data[EduCalendarEvent][name]');
                        var record = combo.findRecord(combo.valueField, combo.getValue());
                        var event_type  = record? record.get(combo.displayField) : combo.valueNotFoundText;
                        desc.setValue(event_type);
                    }
                }";
				$options['value'] = $edu_calendar_event['EduCalendarEvent']['edu_calendar_event_type_id'];
                $this->ExtForm->input('edu_calendar_event_type_id', $options);
            ?>,
            <?php
                $options = array();
                $options['value'] = $edu_calendar_event['EduCalendarEvent']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
			<?php
                $options = array(
                    'anchor' => '50%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduCalendarEvent][start_date]',
                    'vtype' => 'daterange',
                    'endDateField' => 'data[EduCalendarEvent][end_date]',
                    'fieldLabel' => 'Start Date',
                    'value' => $edu_quarter['EduQuarter']['start_date']
                );
                $options['value'] = $edu_calendar_event['EduCalendarEvent']['start_date'];
                $this->ExtForm->input('start_date', $options);
            ?>,
            <?php
                $options = array(
                    'anchor' => '50%',
                    'xtype' => 'datefield',
                    'id' => 'data[EduCalendarEvent][end_date]',
                    'vtype' => 'daterange',
                    'startDateField' => 'data[EduCalendarEvent][start_date]',
                    'fieldLabel' => 'End Date',
                    'value' => $edu_quarter['EduQuarter']['end_date']
                );
                $options['value'] = $edu_calendar_event['EduCalendarEvent']['end_date'];
                $this->ExtForm->input('end_date', $options);
            ?>,
            <?php
                $options = array();
                if (isset($parent_id)) {
                    $options['hidden'] = $parent_id;
                } else {
                    $options['items'] = $edu_quarters;
                }
                $options['value'] = $edu_calendar_event['EduCalendarEvent']['edu_quarter_id'];
                $this->ExtForm->input('edu_quarter_id', $options);
            ?>,
            <?php
                $options = array('anchor' => '60%');
                if (count($edu_campuses) > 1) {
                    $options['items'] = array_merge(array(1111 => 'All Campuses'), $edu_campuses);
                } else {
                    $options['items'] = $edu_campuses;
                }
                $options['value'] = $edu_calendar_event['EduCalendarEvent']['edu_campus_id'];
                $this->ExtForm->input('edu_campus_id', $options);
            ?>
        ]
    });

    var EduCalendarEventEditWindow = new Ext.Window({
        title: '<?php __('Edit Calendar Event'); ?>',
        width: 550,
        minWidth: 550,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduCalendarEventEditForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    EduCalendarEventEditForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to modify an existing Edu Calendar Event.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(EduCalendarEventEditWindow.collapsed)
                        EduCalendarEventEditWindow.expand(true);
                    else
                        EduCalendarEventEditWindow.collapse(true);
                }
            }
        ],
        buttons: [ {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                EduCalendarEventEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduCalendarEventEditWindow.close();
<?php if (isset($parent_id)) { ?>
                        RefreshParentEduCalendarEventData();
<?php } else { ?>
                        RefreshEduCalendarEventData();
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
                EduCalendarEventEditWindow.close();
            }
        }]
    });
	
	initializeDateMargins();
