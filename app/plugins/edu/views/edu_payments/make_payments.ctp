//<script>
    var selected_student= '<?php echo isset($student)? $student['EduStudent']['identity_number']: ''; ?>';

	var store_students = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registrations', 'action' => 'list_data_section_students')); ?>'
        })
    });
	
    var store_payments = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'month', 'student_name', 'amount', 'penalty', 'sibling_discount',
                {name: 'is_paid', type: 'Boolean'}
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_payments', 'action' => 'list_data_payments')); ?>'
        })
    });
	
    store_payments.on('load', function(store, records, options){
        if (store_payments.getCount() == 0)
			Ext.Msg.alert('<?php __('Error'); ?>',
                '<?php __('No pending payments for the selected student.'); ?>');
    });
        
    var store_extra_payments = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'student_name', 'amount', {name: 'is_paid', type: 'Boolean'}
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_extra_payments', 'action' => 'list_data_payments')); ?>'
        })
    });
	
    store_extra_payments.on('load', function(store, records, options){
        //var studentNameField = Ext.getCmp('studentNameField');
        //if(store_extra_payments.getCount() > 0)
        //    studentNameField.setValue(store_extra_payments.getAt(0).get('student_name'));
    });
	
    var popUpWin_print_payment=0;

    function popUpPrintPaymentWindow(URLStr, left, top, width, height) {
        if(popUpWin_print_payment){
            if(!popUpWin_print_payment.closed) popUpWin_print_payment.close();
        }
        popUpWin_print_payment = open(URLStr, 'popUpPrintPaymentWindow',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes'+
            ',copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+
            left+',screenY='+top+'');
    }

    function ShowPrintPayment() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_receipts', 'action' => 'print_receipt', 'plugin' => 'edu')); ?>";
        popUpPrintPaymentWindow(url, 200, 200, 700, 1000);
    }
    
<?php
    if ($this->Session->check('edu_student_id')) {
?>
    var popUpWin_print_cert=0;
    
    function popUpWindowPrintCert(URLStr, left, top, width, height) {
        if(popUpWin_print_cert){
            if(!popUpWin_print_cert.closed) popUpWin_print_cert.close();
        }
        popUpWin_print_cert = open(URLStr, 'popUpWindowPrintCert',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,'+
            'copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+
            left+',screenY='+top+'');
    }

    function printEnrollmentCertificate() {
        var url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_students', 'action' => 'enrollment_certificate', 'plugin' => 'edu')); ?>";
        popUpWindowPrintCert(url, 250, 250, 700, 1000);
    }
<?php
    }

?>

    function RefreshStudentPaymentsData() {
        if(selsubject !== '' & selsection !== '') {
            store_payments.reload({
                params: {
                    start: 0,
                    selected_student: selected_student
                }
            });
        }
    }

    <?php
        $this->ExtForm->create('EduPayment');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var StudentPaymentsForm = new Ext.form.FormPanel({
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
        defaultType: 'textfield',

        items: [
			<?php
				$options = array('fieldLabel' => 'Class/Section');
				$options['items'] = $sections;
				$options['listeners'] = "{
					scope: this,
					'select': function(combo, record, index){
						var edu_student_id = Ext.getCmp('edu_student_id');
						edu_student_id.setValue('');
						edu_student_id.store.removeAll();
						edu_student_id.store.reload({
							params: {
								edu_section_id : combo.getValue()
							}
						});
					}
				}";
                $options['anchor'] = '45%';
				$this->ExtForm->input('edu_section_id', $options);
			?>, {
                xtype: 'combo',
                name: 'edu_student_id',
                hiddenName: 'data[EduPayment][edu_student_id]',
                id:'edu_student_id',
                typeAhead: true,
                store : store_students,
                displayField : 'name',
                valueField : 'id',
                anchor:'65%',
                fieldLabel: '<span style="color:red;">*</span> Student',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Student',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        sel_student_id = combo.getValue();
						selected_student = sel_student_id;
						store_payments.reload({
							params: {
								start: 0,
								selected_student_id_number: selected_student
							}
						});
						store_extra_payments.reload({
							params: {
								start: 0,
								selected_student_id_number: selected_student
							}
						});
                    }
                }
            }
        ]
    });
	
    var AdditionalsForm = new Ext.form.FormPanel({
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
        defaultType: 'textfield',

        items: [{
            xtype:'fieldset',
            title: 'Additional Info',
            autoHeight: true,
            boxMinHeight: 300,
            items: [{
                layout:'column',
                items:[{
                        columnWidth:.33,
                        layout: 'form',
                        items:[
                            <?php
                                $options_cheque_number = array(
                                    'fieldLabel' => 'Chk No',
                                    'id' => 'cheque_number',
                                    'value' => 'CASH'
                                );
                                $this->ExtForm->input('cheque_number', $options_cheque_number);
                            ?>
                        ]
                    }, {
                        columnWidth:.33,
                        layout: 'form',
                        items:[
                            <?php
                                $options_cheque_amount = array(
                                    'fieldLabel' => 'Chk Amt',
                                    'id' => 'cheque_amount',
                                    'value' => 0
                                );
                                $this->ExtForm->input('cheque_amount', $options_cheque_amount);
                            ?>
                        ]
                    }, {
                        columnWidth:.34,
                        layout: 'form',
                        items:[
                            <?php
                                $options_invoice = array(
                                    'fieldLabel' => 'Invoice',
                                    'id' => 'invoice',
                                    'listeners' => "{
                                            blur: function(f, n, o) { calculateValues(); }
                                       }"
                                );
                                $this->ExtForm->input('invoice', $options_invoice);
                            ?>,
                            <?php
                                $options_crm_number = array(
                                    'fieldLabel' => 'CRM Number',
                                    'id' => 'crm_number'
                                );
                                $this->ExtForm->input('crm_number', $options_crm_number);
                            ?>
                        ]
                    }
                ]
            }]
        }, {
            xtype:'fieldset',
            title: '',
            autoHeight: true,
            boxMinHeight: 300,
            items: [{
                layout:'column',
                items:[{
							columnWidth:.5,
							layout: 'form',
							items:[
							]
						}, {
							columnWidth:.5,
							layout: 'form',
							items:[
								<?php
									$options_penalty_amount = array(
										'fieldLabel' => 'Penalty',
										'value' => '0.00',
										'id' => 'penalty_amount',
										'readOnly' => true,
										'listeners' => "{
												blur: function(f, n, o) { calculateValues(); }
										   }"
									);
									$this->ExtForm->input('penalty_amount', $options_penalty_amount);
								?>
							]
						}
					]
				}, {
                    layout:'column',
                    items:[{
                            columnWidth:.5,
                            layout: 'form',
                            items:[
                            ]
                        }, {
                            columnWidth:.5,
                            layout: 'form',
                            items:[
                                <?php
                                    $options_sibling_discount_amount = array(
                                        'fieldLabel' => 'Sibling Discount',
                                        'value' => '0.00',
										'readOnly' => true,
										'id' => 'sibling_discount_amount',
                                        'listeners' => "{
												blur: function(f, n, o) {
													calculateValues();
												}
										   }"
                                    );
                                    $this->ExtForm->input('sibling_discount_amount', $options_sibling_discount_amount);
                                ?>
                            ]
                        }
                    ]
                }, {
                    layout:'column',
                    items:[{
                            columnWidth:.5,
                            layout: 'form',
                            items:[
                            ]
                        }, {
                            columnWidth:.5,
                            layout: 'form',
                            items:[
                                <?php
                                    $options_discount_amount = array(
                                        'fieldLabel' => 'Discount',
                                        'value' => '0.00',
										'id' => 'discount_amount',
										'maskRe' => '/[0-9.]/',
                                        'listeners' => "{
												blur: function(f, n, o) { calculateValues(); }
										   }"
                                    );
                                    $this->ExtForm->input('discount_amount', $options_discount_amount);
                                ?>
                            ]
                        }
                    ]
                }, {
                    layout:'column',
                    items:[{
                            columnWidth:.5,
                            layout: 'form',
                            items:[

                            ]
                        }, {
                            columnWidth:.5,
                            layout: 'form',
                            items:[
                                <?php
                                    $options_total_amount = array(
                                        'fieldLabel' => 'Total',
                                        'value' => '0.00',
                                        'id' => 'txtTotalAmount',
                                        'readOnly' => true
                                    );
                                    $this->ExtForm->input('total_amount', $options_total_amount);
                                ?>
                            ]
                        }
                    ]
                }]
            }
        ]
    });
	
    var StudentPaymentsGrid = new Ext.grid.GridPanel({
        title: '<?php __('Scheduled Payments'); ?>',
        store: store_payments,
        loadMask: true,
        stripeRows: true,
        height: 220,
        anchor: '100%',
        id: 'paymentsGrid',
        columns: [
            {header: "<?php $term_name; ?>", dataIndex: 'month', sortable: true, width: 220},
            {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true, width: 220},
            {header: "<?php __('Penalty'); ?>", dataIndex: 'penalty', sortable: true, width: 220},
            {header: "<?php __('Sibling Discount'); ?>", dataIndex: 'sibling_discount', sortable: true, width: 220},
            {header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true, xtype: 'checkcolumn', width: 80}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            click: function(e){
                calculateValues();
            }
        }
    });
    
    var StudentExtraPaymentsGrid = new Ext.grid.GridPanel({
        title: '<?php __('Extra Payments'); ?>',
        store: store_extra_payments,
        loadMask: true,
        stripeRows: true,
        height: 220,
        anchor: '100%',
        id: 'extraPaymentsGrid',
        columns: [
            {header: "<?php __('Reason for Payment'); ?>", dataIndex: 'name', sortable: true, width: 220},
            {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true, width: 220},
            {header: "<?php __('Is Paid'); ?>", dataIndex: 'is_paid', sortable: true, xtype: 'checkcolumn', width: 80}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            click: function(e){
                calculateValues();
            }
        }
    });
    
    var StudentPaymentsTab = new Ext.TabPanel({
        activeTab: 0,
        height: 180,
        tabWidth: 225,
        items: [
            StudentPaymentsGrid,
            StudentExtraPaymentsGrid
        ]
    });

    function calculateValues() {
        var records = store_payments.getRange(), fields = store_payments.fields;
        var total_amount = 0;
		var total_penalty = 0;
		var total_sibling_discount = 0;
		
        for(var i = 0; i < records.length; i++) {
            if(records[i].get('is_paid')) {
                total_amount += Number(records[i].get('amount'));
                total_penalty += Number(records[i].get('penalty'));
                total_sibling_discount += Number(records[i].get('sibling_discount'));
            }
        }
        var ex_records = store_extra_payments.getRange(), ex_fields = store_extra_payments.fields;
        for(var i = 0; i < ex_records.length; i++) {
            if(ex_records[i].get('is_paid')) {
                total_amount += Number(ex_records[i].get('amount'));
            }
        }
        Ext.getCmp('penalty_amount').setValue(total_penalty);
        Ext.getCmp('sibling_discount_amount').setValue(total_sibling_discount);
        var penalty = Number(Ext.getCmp('penalty_amount').getValue());
        var discount = Number(Ext.getCmp('discount_amount').getValue());
        var sibling_discount = Number(Ext.getCmp('sibling_discount_amount').getValue());
        
        Ext.getCmp('txtTotalAmount').setValue(total_amount + penalty - (discount + sibling_discount));
    }

    var parentStudentPaymentsWindow = new Ext.Window({
        title: 'Student Payments Management',
        width: 700,
        height: 555,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            StudentPaymentsForm,
            StudentPaymentsTab,
            AdditionalsForm
        ],
        buttons: [{
            text: 'Pay',
            id: 'btnSaveChanges',
            handler : function(){
                var btnSaveChanges = Ext.getCmp('btnSaveChanges');
                btnSaveChanges.disable();
				
				StudentPaymentsForm.disable();
				StudentPaymentsTab.disable();
				AdditionalsForm.disable();

                var records = store_payments.getRange(), fields = store_payments.fields;
                var all_params = {};
                var total_amount = 0;
                
                for(var i = 0; i < records.length; i++) {
                    if(records[i].get('is_paid')) {
                        for(var j = 0; j < fields.length; j++){
                            all_params['data[payments]['+ i + '][' + fields['items'][j]['name'] +
                            ']'] = Ext.encode(records[i].get(fields['items'][j]['name']));
                        }
                        total_amount += Number(records[i].get('amount'));
                    }
                }
                
                var ex_records = store_extra_payments.getRange(), ex_fields = store_extra_payments.fields;
                for(var i = 0; i < ex_records.length; i++) {
                    if(ex_records[i].get('is_paid')) {
                        for(var j = 0; j < ex_fields.length; j++){
                            all_params[ 'data[extra_payments]['+ i + '][' + ex_fields['items'][j]['name'] +']'] =
                                Ext.encode(ex_records[i].get(ex_fields['items'][j]['name']));
                        }
                        total_amount += Number(ex_records[i].get('amount'));
                    }
                }
                
                if(total_amount == 0) {
                    Ext.Msg.alert('<?php __('Error'); ?>',
                        '<?php __('You cannot proceed without selecting any. Otherwise click Cancel to close.'); ?>');
                    
                    return false;
                }
                
                all_params['data[additionals][total_amount]'] = total_amount;
                all_params['data[additionals][selected_student]'] = selected_student;

                var cheque_number = Ext.getCmp('cheque_number');
                all_params['data[additionals][cheque_number]'] = cheque_number.getValue();

                var cheque_amount = Ext.getCmp('cheque_amount');
                all_params['data[additionals][cheque_amount]'] = cheque_amount.getValue();
				
                var invoice = Ext.getCmp('invoice');
                all_params['data[additionals][invoice]'] = invoice.getValue();

                var invoice = Ext.getCmp('crm_number');
                all_params['data[additionals][crm_number]'] = invoice.getValue();

                var penalty_amount = Ext.getCmp('penalty_amount');
                all_params['data[additionals][penalty_amount]'] = penalty_amount.getValue();
				
				var discount_amount = Ext.getCmp('discount_amount');
                all_params['data[additionals][discount_amount]'] = discount_amount.getValue();
				
				var sibling_discount_amount = Ext.getCmp('sibling_discount_amount');
                all_params['data[additionals][sibling_discount_amount]'] = sibling_discount_amount.getValue();
				
                if(cheque_amount.getValue() > total_amount){
                    Ext.Msg.alert('<?php __('Error'); ?>',
                        '<?php __('Cheque amount cannot be greater than the total months amount'); ?>');
                    
					StudentPaymentsForm.enable();
					StudentPaymentsTab.enable();
					AdditionalsForm.enable();
                    return false;
                }

				
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array(
                        'controller' => 'edu_payments', 'action' => 'save_changes')); ?>',
                    params: all_params,
                    method: 'POST',
                    success: function(){
                        StudentPaymentsGrid.getStore().removeAll();
                        StudentExtraPaymentsGrid.getStore().removeAll();
                        Ext.MessageBox.confirm(
                            'Confirm',
                            'Do you want to get print out?',
                            function(btn){
                                if (btn === 'yes'){
                                    ShowPrintPayment();
<?php if ($this->Session->check('edu_student_id')) { ?>
                                    printEnrollmentCertificate();
<?php } ?>
                                    if(on_enrollment) {
                                        openEnrollment();
                                    }
                                } else {
                                    if(on_enrollment) {
                                        openEnrollment();
                                    }
                                }
                            }
                        );
						
                        parentStudentPaymentsWindow.close();
                        
                    },
                    failure: function(response, opts){
                        var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                        ShowErrorBox(obj.errormsg, obj.helpcode);
						
						StudentPaymentsForm.enable();
						StudentPaymentsTab.enable();
						AdditionalsForm.enable();
						
						var btnSaveChanges = Ext.getCmp('btnSaveChanges');
						btnSaveChanges.enable();
                    }
                });
            }
        }, {
            text: 'Cancel',
            handler: function(btn){
                parentStudentPaymentsWindow.close();
                if(on_enrollment) {
                    openEnrollment();
                }
            }
        }]
    });
    parentStudentPaymentsWindow.show();

<?php
    if (isset($edu_student_id)) {
        echo "store_payments.load({
            params: {
                start: 0,
                selected_student_id_number: '{$student['EduStudent']['identity_number']}'
            }
        });";
        echo "store_extra_payments.load({
            params: {
                start: 0,
                selected_student_id_number: '{$student['EduStudent']['identity_number']}'
            }
        });";
    }