//<script>
    <?php
        $this->ExtForm->create('Benefit');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var OTBenefitAddForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'benefits', 'action' => 'add_ot', $parent_id)); ?>',
        defaultType: 'textfield',

        items: [
            <?php 
                $options1 = array('xtype'=>'combo', 'fieldLabel'=>'OT Reverance');
                $options1['items'] = array(
                    '1.25' => 'Moday-Frday (12 PM-2-30 PM) and (11-30 PM to 4 PM)',
                    '1.5' => 'Monday-Frayday (4 PM-12 PM) (Evening-Earning)', 
                    '2' => 'Saterday and Sunday',
                    '2.5' => 'Holydays');
                $this->ExtForm->input('ot_reverance', $options1);
            ?>,
            <?php 
                $options = array('fieldLabel' => 'Extra hours worked');
                $this->ExtForm->input('ot_hours', $options);
            ?>			
        ]
    });
		
    var OTBenefitAddWindow = new Ext.Window({
        title: '<?php __('Add Benefit'); ?>',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: OTBenefitAddForm,
        tools: [{
                id: 'refresh',
                qtip: 'Reset',
                handler: function () {
                        OTBenefitAddForm.getForm().reset();
                },
                scope: this
            }, {
                id: 'help',
                qtip: 'Help',
                handler: function () {
                    Ext.Msg.show({
                        title: 'Help',
                        buttons: Ext.MessageBox.OK,
                        msg: 'This form is used to insert a new Benefit.',
                        icon: Ext.MessageBox.INFO
                    });
                }
            }, {
                id: 'toggle',
                qtip: 'Collapse / Expand',
                handler: function () {
                    if(OTBenefitAddWindow.collapsed)
                        OTBenefitAddWindow.expand(true);
                    else
                        OTBenefitAddWindow.collapse(true);
                }
        }],
        buttons: [  {
            text: '<?php __('Save'); ?>',
            handler: function(btn){
                OTBenefitAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        OTBenefitAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
                        RefreshParentBenefitData();
<?php } else { ?>
                        RefreshBenefitData();
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
            text: '<?php __('Save & Close'); ?>',
            handler: function(btn){
                OTBenefitAddForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        OTBenefitAddWindow.close();
<?php if(isset($parent_id)){ ?>
                        RefreshParentBenefitData();
<?php } else { ?>
                        RefreshBenefitData();
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
                OTBenefitAddWindow.close();
            }
        }]
    });
