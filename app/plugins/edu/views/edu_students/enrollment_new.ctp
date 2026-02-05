//<script>
    <?php
        $this->ExtForm->create('User');
        $this->ExtForm->defineFieldFunctions();
        $this->ExtForm->create('Person');
        $this->ExtForm->defineFieldFunctions();
        $this->ExtForm->create('EduStudent');
        $this->ExtForm->defineFieldFunctions();
	
	$password_generated = rand(1000, 9999);
	$password_generated_parent = rand(1000, 9999);
    ?>

    function populateParentInfo(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'populate_parent_info')); ?>/'+id,
            success: function(response, opts) {
                var populate_parent_info = response.responseText;

                eval(populate_parent_info);
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('ERROR'); ?>', '<?php __('Unexpected Error. Please press F5 to refresh the application.'); ?>: ' + response.status);
            }
        });
    }
    
    var classPayments = [
<?php foreach($class_payments as $cpk => $cpv) {
        echo '[' . $cpk . ', ' . $cpv['EduClass']['enrollment_fee'] . ', ' . $cpv['EduClass']['registration_fee'] . '],';
}
?>
    ];
    
    var class_payments_store = new Ext.data.ArrayStore({
        fields: [
           {name: 'id'},
           {name: 'enrollment_fee',       type: 'float'},
           {name: 'registration_fee',     type: 'float'}
        ]
    });
    
    class_payments_store.loadData(classPayments);
    
    var EnrollmentForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        fileUpload: true,
        isUpload: true,
        labelWidth: 150,
        labelAlign: 'right',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'enrollment')); ?>',
        items: {
            xtype: 'tabpanel',
            activeTab: 0,
            height: 538,
            id: 'enrollment_tabs',
            tabWidth: 225,
            defaults: {bodyStyle: 'padding:10px'},
            items: [{
                    title: 'Student Info',
                    layout: 'form',
                    defaultType: 'textfield',
                    id: 'student_tab',
                    items: [{
                            xtype: 'compositefield',
                            fieldLabel: '<span style="color:red;">*</span> Student Full Name',
                            msgTarget: 'qtip',
                            anchor: '-20',
                            defaults: {
                                flex: 1
                            },
                            items: [
                                <?php
                                    $this->ExtForm->create('EduStudent');
                                    $options04_1 = array('anchor' => '90%', 'emptyText' => 'First Name', 'allowBlank' => false, 'blankText' => 'First Name is required', 'id' => 'studentName1');
                                    $this->ExtForm->input('student_name1', $options04_1);
                                ?>,
                                <?php
                                    $options05_1 = array('anchor' => '90%', 'emptyText' => 'Middle Name', 'id' => 'data[EduStudent][studentName2]');
                                    $options05_1['listeners'] = "{
                                            scope: this,
                                            'blur': function(textField){
                                                var studentUsername = Ext.getCmp('studentUserName');
                                                var fn = Ext.getCmp('studentName1');

                                                var un = fn.getValue() + ' ' + textField.getValue();
                                                un = un.trim();
                                                un = un.toLowerCase();
                                                new_un_array = un.split(' ');
                                                un = '';
                                                for (var i=0; i < new_un_array.length; i++) {
                                                    un += new_un_array[i] + '.';
                                                }
                                                un = un.substr(0, un.length-1);

                                                studentUsername.setValue(un);

                                                var studentPassword = Ext.getCmp('studentPassword');
                                                studentPassword.setValue('$password_generated');
                                            }
                                        }";
                                    $this->ExtForm->input('student_name2', $options05_1);
                                ?>,
                                <?php
                                    $options06 = array('anchor' => '90%', 'emptyText' => 'Last Name', 'id' => 'studentName3');
                                    $this->ExtForm->input('student_name3', $options06);
                                ?>
                            ]
                    }, {
                        xtype: 'compositefield',
                        fieldLabel: '<span style="color:red;">*</span> Date of Birth',
                        msgTarget: 'qtip',
                        anchor: '-20',
                        defaults: {
                            flex: 1
                        },
                        items: [
                            <?php
                                $options2 = array(
                                    'anchor' => '50%',
                                    'fieldLabel' => 'Date of Birth',
                                    'value' => date('Y-m-d', strtotime('-3 years')),
                                    'maxValue' => "'" . date('Y-m-d', strtotime('-3 years')) . "'");
                                $options2['listeners'] = "{
                                    scope: this,
                                    'select': function(fld, dt){
                                        // find the date difference between dt and today
                                        today = new Date();
                                        diff = today - dt;
                                        days = diff / (1000 * 60 * 60 * 24);
                                        age = days / 365;
                                        var lblBirthDate = Ext.getCmp('lblBirthDate');
                                        lblBirthDate.setText(age.toFixed(1) + ' years old');
                                    }
                                }";
                                $this->ExtForm->input('birth_date', $options2);
                            ?>,
                            {
                                xtype: 'label',
                                value: '',
                                id: 'lblBirthDate'
                            },
                            {
                                xtype: 'label',
                                value: '',
                                id: 'lblBirthDate2'
                            }
                        ]
                    },
                    <?php
                        $options3 = array('anchor' => '50%', 'fieldLabel' => 'Gender', 'xtype' => 'combo');
                        $options3['items'] = array('F' => 'Female', 'M' => 'Male');
                        $this->ExtForm->input('gender', $options3);
                    ?>,
                    <?php
                        $options4 = array('anchor' => '50%', 'xtype' => 'combo', 'items' => $nationalities, 'fieldLabel' => 'Nationality', 'value' => 'Ethiopian');
                        $this->ExtForm->input('nationality', $options4);
                    ?>, {
                        xtype: 'fieldset',
                        title: 'Address',
                        collapsible: true,
                        items: [{
                                layout: 'column',
                                labelWidth: 100,
                                items: [{
                                        columnWidth: .33,
                                        layout: 'form',
                                        items: [
                                            <?php
                                                $options51 = array('anchor' => '95%', 'xtype' => 'combo', 'items'=>$sub_cities, 'fieldLabel'=>'Sub City');
                                                $this->ExtForm->input('subcity', $options51);
                                            ?>
                                        ]
                                    }, {
                                        columnWidth: .33,
                                        layout: 'form',
                                        items: [
                                            <?php
                                                $options52 = array('anchor' => '95%');
                                                $this->ExtForm->input('woreda', $options52);
                                            ?>
                                        ]
                                    }, {
                                        columnWidth: .34,
                                        layout: 'form',
                                        items: [
                                            <?php
                                                $options53 = array('anchor' => '95%');
                                                $this->ExtForm->input('house_number', $options53);
                                            ?>
                                        ]
                                    }]
                            }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Academic Information',
                        collapsible: true,
                        items: [{
                                layout: 'column',
                                labelWidth: 150,
                                items: [{
                                        columnWidth: .5,
                                        layout: 'form',
                                        items: [
                                            <?php
                                                $options1 = array('anchor' => '90%', 'fieldLabel' => 'Class/Grade');
                                                $options1['items'] = $edu_classes;
                                                $options1['id'] = 'data[EduStudent][edu_class_id]';
                                                $options1['listeners'] = "{
                                                        scope: this,
                                                        'select': function(combo, record, index){
                                                            x = new RegExp(\"abc\");
                                                            if(x.test(combo.getValue())){
                                                                Ext.Msg.alert('Ooops', 'The Selected class has no Payment Schedule. Please create the payment schedules first.');
                                                                combo.setValue('Select One');
                                                            } else {
                                                                incld = Ext.getCmp('data[EduPayment][include_registration]');
                                                                
                                                                rec_index = class_payments_store.find('id', combo.getValue());
                                                                rec = class_payments_store.getAt(rec_index);
                                                                pay = rec.get('enrollment_fee');
                                                                if(incld.getValue())
                                                                    pay = rec.get('enrollment_fee') + rec.get('registration_fee');
                                                                
                                                                amountTxt = Ext.getCmp('data[EduPayment][amount]');
                                                                amountTxt.setValue(pay);
                                                            }
                                                        }
                                                    }";
                                                $this->ExtForm->input('edu_class_id', $options1);
                                            ?>
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        items: [
                                            <?php
                                                $options52 = array('anchor' => '95%', 'id' => 'data[EduPayment][include_registration]', 
                                                    'xtype' => 'checkbox', 'checked' => true);
                                                $options52['listeners'] = "{
                                                        scope: this,
                                                        'check': function(cb, is_chcked){
                                                            x = new RegExp(\"abc\");
                                                            cbo = Ext.getCmp('data[EduStudent][edu_class_id]');
                                                            if(cbo.getValue() == ''){
                                                                Ext.Msg.alert('Ooops', 'It seems you did not select a class. Please select one');
                                                            } else {
                                                                rec_index = class_payments_store.find('id', cbo.getValue());
                                                                rec = class_payments_store.getAt(rec_index);

                                                                if(is_chcked){
                                                                    pay = rec.get('enrollment_fee') + rec.get('registration_fee');
                                                                } else {
                                                                    pay = rec.get('enrollment_fee');
                                                                }
                                                                amountTxt = Ext.getCmp('data[EduPayment][amount]');
                                                                amountTxt.setValue(pay);
                                                            }
                                                        }
                                                    }";
                                                $this->ExtForm->input('include_registration', $options52);
                                            ?>
                                        ]
                                    }]
                            }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Required Documents Presented',
                        collapsible: true,
                        labelWidth: 20,
                        items: [new Ext.form.CheckboxGroup({
                            id:'requiredDocuments',
                            xtype: 'checkboxgroup',
                            fieldLabel: '',
                            itemCls: 'x-check-group-alt',
                            columns: 4,
                            items: [
                                { 
                                    boxLabel: '<?php __('Vaccination'); ?>', 
                                    name: '<?php echo "data[EduDocument][vaccination]"; ?>'
                                }, { 
                                    boxLabel: '<?php __('Birth Certificate'); ?>', 
                                    name: '<?php echo "data[EduDocument][birth_certificate]"; ?>'
                                }, { 
                                    boxLabel: '<?php __('Report Card'); ?>', 
                                    name: '<?php echo "data[EduDocument][report_card]"; ?>'
                                }, { 
                                    boxLabel: '<?php __('Clearance'); ?>', 
                                    name: '<?php echo "data[EduDocument][clearance]"; ?>'
                                }
                            ]
                        })]
                    }, {
                        xtype: 'fieldset',
                        title: 'User Information',
                        collapsible: true,
                        items: [{
                            layout: 'column',
                            items: [{
                                columnWidth: 1.0,
                                layout: 'form',
                                items: [
                                    <?php
                                        $this->ExtForm->create('UserStudent');
                                        $options61 = array('anchor' => '95%', 'id' => 'studentUserName', 'readOnly' => true);
                                        $this->ExtForm->input('username', $options61);
                                    ?>,
                                    <?php
                                        $options62 = array('id' => 'studentPassword', 'anchor' => '95%', 'readOnly' => true);
                                        $this->ExtForm->input('password', $options62);
                                    ?>
                                ]
                            }]
                        }]
                    },
                    <?php
                            $optionsf1 = array(
                                'anchor' => '50%',
                                'id' => 'form-file',
                                'xtype' => 'fileuploadfield',
                                'fieldLabel' => 'Photo File',
                                'buttonText' => '',
                                'emptyText' => 'Select an Image',
                                'buttonCfg' => "{
                                    iconCls: 'upload-icon'
                                }"
                            );
                            $this->ExtForm->input('photo_file_name', $optionsf1);
                    ?>]
                }, {
                    title: 'Family Info',
                    id: 'parent_tab',
                    layout: 'form',
                    defaultType: 'textfield',
                    disabled: true,
                    items: [
                    ]
                }, {
                    title: 'Student Condition',
                    id: 'student_condition_tab',
                    layout: 'form',
                    defaultType: 'textfield',
                    disabled: true,
                    items: [
                    ]
                }, {
                    title: 'Payment',
                    id: 'payment_tab',
                    layout: 'form',
                    disabled: true,
                    defaultType: 'textfield',
                    items: [
                        <?php
                            $this->ExtForm->create('EduPayment');
                            $options21 = array('anchor' => '40%', 
                                'fieldLabel' => '<span style="color:red;">*</span> Amount',
                                'id' => 'data[EduPayment][amount]',
                                'allowBlank' => false, 'blankText' => 'Amount should be specified',
                                'style' => 'text-align: right;',
                                'maskRe' => '/^([0-9.,])*$/',
                                'disabled' => true,
                                'value' => '0.00');
                            $this->ExtForm->input('amount', $options21);
                        ?>, {
                            xtype: 'compositefield',
                            fieldLabel: 'Discount',
                            msgTarget: 'qtip',
                            anchor: '-20',
                            defaults: {
                                flex: 1
                            },
                            items: [
                                <?php
                                    $options211 = array('anchor' => '40%', 
                                        'fieldLabel' => 'Discount',
                                        'id' => 'data[EduPayment][discount]',
                                        'allowBlank' => false, 'blankText' => 'Amount should be specified',
                                        'style' => 'text-align: right;',
                                        'maskRe' => '/^([0-9.,])*$/',
                                        'value' => '0.00');
                                    $options211['listeners'] = "{
                                        scope: this,
                                        'change': function(fld, nv, ov){
                                            var txtAmount = Ext.getCmp('data[EduPayment][amount]');
                                            diff = txtAmount.getValue() - nv;
                                            msg = 'Invalid Amount';
                                            if(diff >= 0){
                                                msg = 'Total: ' + diff + '';
                                            }
                                            var lblTotalAmount = Ext.getCmp('lblTotalAmount');
                                            lblTotalAmount.setText(msg, true);
                                        }
                                    }";
                                    $this->ExtForm->input('discount', $options211);
                                ?>,
                                {
                                    xtype: 'label',
                                    value: '',
                                    id: 'lblTotalAmount'
                                },
                                {
                                    xtype: 'label',
                                    value: '',
                                    id: 'lblTotalAmount2'
                                }
                            ]
                        },
                        <?php
                            $options22 = array('anchor' => '60%', 
                                'fieldLabel' => 'Cheque Number',
                                'disabled' => ($is_cheque_payment_allowed == 'False')
                            );
                            $this->ExtForm->input('cheque_number', $options22);
                        ?>,
                        <?php
                            $options23 = array('anchor' => '60%', 'fieldLabel' => 'Cash Reg. Ref. Number');
                            $this->ExtForm->input('crm_number', $options23);
                        ?>,
                        <?php
                            $options24 = array('anchor' => '60%', 'fieldLabel' => 'Reason', 'xtype' => 'textarea',
                                'value' => 'Enrollment Fee');
                            $this->ExtForm->input('description', $options24);
                        ?>,
                        <?php
                            $options25 = array(
                                'fieldLabel' => 'Include Other Payments', 
                                'xtype' => 'checkbox', 
                                'id' => 'data[EduPayment][include_monthly_payments]');
                            $this->ExtForm->input('include_monthly_payments', $options25);
                        ?>
                    ]
                }]
        }
    });
    var activetab = 1;
    //Ext.getCmp('data[EduStudent][studentName2]').focus();
    
    var EnrollmentWindow = new Ext.Window({
        title: '<?php __('Enrollment Form'); ?>',
        width: 730,
        height: 580,
        layout: 'fit',
        modal: true,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EnrollmentForm,
        buttons: [{
                text: '<?php __('Back'); ?>',
                disabled: true,
                id: 'back',
                handler: function(btn) {
                    if (activetab == 2) {
                        Ext.getCmp('student_tab').enable();
                        Ext.getCmp('parent_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('student_tab'));
                        Ext.getCmp('back').disable();
                        activetab = 1;
                    }
                    if (activetab == 3) {
                        Ext.getCmp('parent_tab').enable();
                        Ext.getCmp('student_condition_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('parent_tab'));
                        activetab = 2;
                    }
                    if (activetab == 4) {
                        Ext.getCmp('student_condition_tab').enable();
                        Ext.getCmp('payment_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('student_condition_tab'));
                        Ext.getCmp('next').setText('Next');
                        activetab = 3;
                    }

                }
            }, {
                text: '<?php __('Next'); ?>',
                id: 'next',
                handler: function(btn) {
                    if (activetab == 4) { // if "Finish"
                        //alert('Before form submit');
                        EnrollmentForm.getForm().submit();
                        /*EnrollmentForm.getForm().submit({
                            method: 'POST',
                            waitMsg: '<?php __('Submitting your data...'); ?>',
                            waitTitle: '<?php __('Wait Please...'); ?>',
                            success: function(f, a) {
                                Ext.Msg.show({
                                    title: '<?php __('Success'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: a.result.msg,
                                    icon: Ext.MessageBox.INFO
                                });
								
                                var include_m_payment = Ext.getCmp('data[EduPayment][include_monthly_payments]');
                                var amount = Ext.getCmp('data[EduPayment][amount]');
                                
                                if(include_m_payment.getValue()) {
                                    Ext.Ajax.request({
                                        url: "<?php echo $this->Html->url(array('controller' => 'edu_payments', 'action' => 'make_payments')); ?>",
                                        success: function(response, opts) {
                                            var eduPayment_data = response.responseText;
                                            eval(eduPayment_data);
                                            EnrollmentForm.getForm().reset();
                                        },
                                        failure: function(response, opts) {
                                            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student monthly payment form. Error code'); ?>: " + response.status);
                                        }
                                    });
                                } else {
                                    printReceipt();
                                    EnrollmentForm.getForm().reset();
                                }
                            },
                            failure: function(f, a) {
                                Ext.Msg.show({
                                    title: '<?php __('Oooops!'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: 'Cannot save the data', //.result.errormsg,
                                    icon: Ext.MessageBox.ERROR
                                });
                            }
                        });*/
                    }
                    if (activetab == 3) {
                        Ext.getCmp('payment_tab').enable();
                        Ext.getCmp('student_condition_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('payment_tab'));
                        Ext.getCmp('next').setText('Finish');
                        activetab = 4;
                    }
                    if (activetab == 2) {
                        Ext.getCmp('student_condition_tab').enable();
                        Ext.getCmp('parent_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('student_condition_tab'));
                        activetab = 3;
                    }
                    if (activetab == 1) {
                        Ext.getCmp('back').enable();
                        Ext.getCmp('parent_tab').enable();
                        Ext.getCmp('student_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('parent_tab'));
                        activetab = 2;
                    }
                }
            }, {
                text: '<?php __('Cancel'); ?>',
                handler: function(btn) {
                    EnrollmentWindow.close();
                }
            }]

    });

    EnrollmentWindow.show();
    
	
    var popUpWin_enrl=0;
	
    function popUpWindow(URLStr, left, top, width, height) {
        if(popUpWin_enrl){
            if(!popUpWin_enrl.closed) popUpWin_enrl.close();
        }
        popUpWin_enrl = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printReceipt() {
        var url = "<?php echo $this->Html->url(array('controller' => 'edu_receipts', 'action' => 'print_receipt', 'plugin' => 'edu')); ?>";
        popUpWindow(url, 200, 200, 700, 1000);
    }