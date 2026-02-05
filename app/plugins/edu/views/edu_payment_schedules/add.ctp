//<script>
    <?php
        if($ay == FALSE) {
    ?>
    Ext.Msg.show({
        title: 'Ooops!',
        buttons: Ext.MessageBox.OK,
        msg: 'There is no active academic year',
        icon: Ext.MessageBox.ERROR
    });
    <?php
        } else {
            $this->ExtForm->create('EduPaymentSchedule');
            $this->ExtForm->defineFieldFunctions();
    ?>
    var EduPaymentScheduleAddForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_payment_schedules', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php
                $options = array('xtype' => 'combo');
                if($setting == 'M') {
                    $options['fieldLabel'] = 'Month';
                    $months = array(
                        1 => '01 - September', 2 => '02 - October',
                        3 => '03 - November',4 => '04 - December',5 => '05 - January',
                        6 => '06 - February',7 => '07 - March',8 => '08 - April',9 => '09 - May',
                        10 => '10 - June',11 => '11 - July',12 => '12 - August');
                    $items = array();
                    for ($i = 1; $i <= 12; $i++) {
                        if (!in_array($i, $ps_options)) {
                            $items[$i] = $months[$i];
                        }
                    }
                    $options['items'] = $items;
                } else {
                    $options['fieldLabel'] = 'Term';
                    $quarters = array(1 => 'Quarter 1', 2 => 'Quarter 2',3 => 'Quarter 3',4 => 'Quarter 4',
                        5 => 'Summer');
                    $items = array();
                    for ($i = 1; $i <= 5; $i++){
                        if (!in_array($i, $ps_options)) {
                            $items[$i] = $quarters[$i];
                        }
                    }
                    $options['items'] = $items;
                }
                $this->ExtForm->input('month', $options);
            ?>,
            <?php
                $options = array();
                if (isset($parent_id)) {
                    $options['hidden'] = $parent_id;
                } else {
                    $options['items'] = $edu_classes;
                }
                $this->ExtForm->input('edu_class_id', $options);
            ?>,
            <?php
                $options = array();
                $this->ExtForm->input('amount', $options);
            ?>
        ]
    });
		
    var EduPaymentScheduleAddWindow = new Ext.Window({
        title: '<?php __('Add Payment Schedule'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduPaymentScheduleAddForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduPaymentScheduleAddForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Payment Schedule.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduPaymentScheduleAddWindow.collapsed)
                    EduPaymentScheduleAddWindow.expand(true);
                else
                    EduPaymentScheduleAddWindow.collapse(true);
            }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                EduPaymentScheduleAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        EduPaymentScheduleAddWindow.close();
<?php if (isset($parent_id)) { ?>
                        RefreshParentEduPaymentScheduleData();
<?php } else { ?>
                        RefreshEduPaymentScheduleData();
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
                EduPaymentScheduleAddWindow.close();
            }
        }]
    });
<?php
    }
?>
