//<script>
    <?php
        $this->ExtForm->create('Holiday');
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
                this.dateRangeMax = date;
            }
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = Ext.getCmp(field.endDateField);
                end.setMinValue(date);
                this.dateRangeMin = date;
            }
            /*
             * Always return true since we're only using this vtype to set the
             * min/max allowed values (these are tested for after the vtype test)
             */
            return true;
        }
    });
	
    var HolidayAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'holidays', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options = array();
                $this->ExtForm->input('name', $options);
            ?>,
            <?php 
                $options = array(
					'xtype' => 'datefield',
                    'name' => 'data[Holiday][from_date]',
                    'id' => 'data[Holiday][from_date]',
                    'vtype' => 'daterange',
                    'endDateField' => 'data[Holiday][to_date]',
                    'anchor' => '60%'
                );
                $this->ExtForm->input('from_date', $options);
            ?>,
            <?php 
                $options = array(
					'xtype' => 'datefield',
                    'name' => 'data[Holiday][to_date]',
                    'id' => 'data[Holiday][to_date]',
                    'vtype' => 'daterange',
                    'startDateField' => 'data[Holiday][from_date]',
                    'anchor' => '60%'
                );
                $this->ExtForm->input('to_date', $options);
            ?>,
            <?php 
                $options = array();
                $this->ExtForm->input('is_recurrent', $options);
            ?>			
        ]
    });
		
    var HolidayAddWindow = new Ext.Window({
        title: '<?php __('Add Holiday'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: HolidayAddForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    HolidayAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Holiday.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(HolidayAddWindow.collapsed)
                        HolidayAddWindow.expand(true);
                    else
                        HolidayAddWindow.collapse(true);
                }
        }],
        buttons: [  {
            text: '<?php __('Set'); ?>',
            handler: function(btn){
                HolidayAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HolidayAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
                        RefreshParentHolidayData();
<?php } else { ?>
                        RefreshHolidayData();
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
            text: '<?php __('Set & Close'); ?>',
            handler: function(btn){
                HolidayAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HolidayAddWindow.close();
<?php if(isset($parent_id)){ ?>
                        RefreshParentHolidayData();
<?php } else { ?>
                        RefreshHolidayData();
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
                HolidayAddWindow.close();
            }
        }]
    });