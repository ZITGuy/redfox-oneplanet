//<script>
    <?php
        $this->ExtForm->create('Holiday');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var HolidayEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'holidays', 'action' => 'edit')); ?>',
        defaultType: 'textfield',

        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $holiday['Holiday']['id'])); ?>,
            <?php 
                $options = array();
                $options['value'] = $holiday['Holiday']['name'];
                $this->ExtForm->input('name', $options);
            ?>,
            <?php 
                $options = array(
					'xtype' => 'datefield',
                    'id' => 'data[Holiday][from_date]',
                    //'vtype' => 'daterange',
                    //'endDateField' => 'data[Holiday][to_date]',
                    'anchor' => '60%'
                );
                $options['value'] = $holiday['Holiday']['from_date'];
                $this->ExtForm->input('from_date', $options);
            ?>,
            <?php 
                $options = array(
					'xtype' => 'datefield',
                    'id' => 'data[Holiday][to_date]',
                    //'vtype' => 'daterange',
                    //'startDateField' => 'data[Holiday][from_date]',
                    'anchor' => '60%'
                );
                $options['value'] = $holiday['Holiday']['to_date'];
                $this->ExtForm->input('to_date', $options);
            ?>,
            <?php 
                $options = array();
                $options['value'] = $holiday['Holiday']['is_recurrent'];
                $this->ExtForm->input('is_recurrent', $options);
            ?>			
        ]
    });
		
    var HolidayEditWindow = new Ext.Window({
        title: '<?php __('Edit Holiday'); ?>',
        width: 400,
        minWidth: 400,
        //autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: HolidayEditForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                    HolidayEditForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to modify an existing Holiday.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(HolidayEditWindow.collapsed)
                        HolidayEditWindow.expand(true);
                    else
                        HolidayEditWindow.collapse(true);
                }
        }],
        buttons: [{
            text: '<?php __('Set'); ?>',
            handler: function(btn){
                HolidayEditForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        HolidayEditWindow.close();
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
                HolidayEditWindow.close();
            }
        }]
    });