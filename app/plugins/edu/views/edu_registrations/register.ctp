//<script>
    <?php
        $this->ExtForm->create('EduRegistration');
        $this->ExtForm->defineFieldFunctions();
    ?>
    
    var classPayments = [
<?php foreach ($class_payments as $cpk => $cpv) {
        echo '[' . $cpk . ', ' . $cpv['EduClass']['enrollment_fee'] . ', ' .
            $cpv['EduClass']['registration_fee'] . '],';
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
    
    function SearchStudent() {
        EduRegistrationAddForm.el.mask('Please wait', 'x-mask-loading');
        var search_box = Ext.getCmp('txt_search_student');
        if(search_box.getValue() == '') {
            Ext.Msg.show({
                title: "<?php __('Ooops!'); ?>",
                buttons: Ext.MessageBox.OK,
                msg: "<?php __('You cannot search by empty text'); ?>: ",
                icon: Ext.MessageBox.ERROR
            });
        } else {
            var edu_student = Ext.getCmp('data[EduRegistration][edu_student_id]');
            edu_student.setValue('');
            edu_student.store.removeAll();
            edu_student.store.reload({
                params: {
                    query: search_box.getValue(),
                    limit: 1000
                }
            });
        }
    }
	
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
                    'controller' => 'edu_registrations', 'action' => 'list_registration_students')); ?>'
        }),
        listeners: {
            'load': function(s, records, options) {
                if(s.getTotalCount() == 0){
                    Ext.Msg.show({
                        title: "<?php __('Ooops!'); ?>",
                        buttons: Ext.MessageBox.OK,
                        msg: "<?php __('No record found'); ?>: ",
                        icon: Ext.MessageBox.ERROR
                    });
                }
                EduRegistrationAddForm.el.unmask();
            }
        }
    });
	
    var store_classes = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'class_id'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registrations', 'action' => 'list_registration_classes')); ?>'
        }),
        listeners: {
            'load': function(s, records, options) {
                if(s.getTotalCount() == 0) {
                    Ext.Msg.show({
                        title: "<?php __('Ooops!'); ?>",
                        buttons: Ext.MessageBox.OK,
                        msg: "<?php __('No record found'); ?>: ",
                        icon: Ext.MessageBox.ERROR
                    });
                }
                EduRegistrationAddForm.el.unmask();
            }
        }
    });
	
    var selected_student_id = 0;
	
    var EduRegistrationAddForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array(
            'controller' => 'edu_registrations', 'action' => 'register')); ?>',
        defaultType: 'textfield',

        items: [{
                xtype: 'compositefield',
                labelWidth: 120,
                fieldLabel: 'Search Student',
                items:[
                    <?php
                        $options = array('emptyText' => 'By Student ID or Name', 'id' => 'txt_search_student');
                        $options['listeners'] = "{
                            specialkey: function(field, e){
                                if (e.getKey() == e.ENTER) {
                                    SearchStudent();
                                }
                            }
                        }";
                        $this->ExtForm->input('search_student', $options);
                    ?>, {
                        xtype: 'button',
                        text: '<?php __('Search...'); ?>',
                        handler: function(btn){
                            SearchStudent();
                        }
                    }
                ]
            }, {
                xtype: 'combo',
                id: 'data[EduRegistration][edu_student_id]',
                name: 'data[EduRegistration][edu_student_id]',
                store : store_students,
                displayField : 'name',
                valueField : 'id',
                anchor:'97%',
                fieldLabel: 'Student',
                mode: 'local',
                disableKeyFilter : true,
                allowBlank: false,
                typeAhead: true,
                emptyText: '[Select One]',
                editable: false,
                triggerAction: 'all',
                listeners: {
                    scope: this,
                    'select': function(combo, record, index){
                        var edu_class = Ext.getCmp('data[EduRegistration][edu_class_id]');
                        selected_student_id = combo.getValue();
                        edu_class.setValue('');
                        edu_class.store.removeAll();
                        EduRegistrationAddForm.el.mask('Please wait', 'x-mask-loading');
                        edu_class.store.reload({
                            params: {
                                edu_student_id : combo.getValue(),
                                limit: 1000
                            }
                        });
                    }
                }
            },  {
                xtype: 'combo',
                id: 'data[EduRegistration][edu_class_id]',
                name: 'data[EduRegistration][edu_class_id]',
                store : store_classes,
                displayField : 'name',
                valueField : 'id',
                anchor:'97%',
                fieldLabel: 'To Class',
                mode: 'local',
                disableKeyFilter : true,
                allowBlank: false,
                typeAhead: true,
                emptyText: '[Select One]',
                editable: false,
                triggerAction: 'all',
                listeners: {
                    scope: this,
                    'select': function(combo, record, index){
                        rec_index = class_payments_store.find('id', record.get('class_id'));
                        rec = class_payments_store.getAt(rec_index);
                        pay = rec.get('registration_fee');
                        
                        amountTxt = Ext.getCmp('data[EduPayment][amount]');
                        amountTxt.setValue(pay);
                    }
                }
            }, <?php
                $optionsf = array(
                    'anchor' => '50%',
                    'id' => 'form-file',
                    'xtype' => 'fileuploadfield',
                    'fieldLabel' => 'Upload Photo to Replace',
                    'buttonText' => '',
                    'emptyText' => 'Upload Photo',
                    'buttonCfg' => "{
                                iconCls: 'upload-icon'
                        }"
                );
                $this->ExtForm->input('photo_file_name', $optionsf);
            ?>, {
                xtype:'fieldset',
                title: 'Payment',
                autoHeight: true,
                boxMinHeight: 300,
                items: [
                    <?php
                        $this->ExtForm->create('EduPayment');
                        $options21 = array(
                            'anchor' => '50%',
                            'fieldLabel' => 'Amount',
                            'style' => 'text-align: right;',
                            'readOnly' => true,
                            'value' => '0.00',
                            'id' => 'data[EduPayment][amount]');
                        $this->ExtForm->input('amount', $options21);
                    ?>,
                    <?php
                        $options22 = array('anchor' => '60%', 'fieldLabel' => 'Cheque Number',
                            'disabled' => ($is_cheque_payment_allowed == 'False'));
                        $this->ExtForm->input('cheque_number', $options22);
                    ?>,
                    <?php
                        $options23 = array('anchor' => '60%', 'fieldLabel' => 'Cash Reg. Ref. Number');
                        $this->ExtForm->input('crm_number', $options23);
                    ?>,
                    <?php
                        $options24 = array('anchor' => '60%', 'fieldLabel' => 'Reason', 'xtype' => 'textarea',
                            'value' => 'Registration fee');
                        $this->ExtForm->input('description', $options24);
                    ?>,
                    <?php
                        $options25 = array(
                            'fieldLabel' => 'Include Monthly Payments',
                            'xtype' => 'checkbox',
                            'id' => 'data[EduPayment][include_monthly_payments]');
                        $this->ExtForm->input('include_monthly_payments', $options25);
                    ?>
                ]
			}
		]
	});
		
	var EduRegistrationAddWindow = new Ext.Window({
		title: '<?php __('Student Registration Form'); ?>',
		width: 650,
		minWidth: 600,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Registration.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationAddWindow.collapsed)
					EduRegistrationAddWindow.expand(true);
				else
					EduRegistrationAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduRegistrationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
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
								url: "<?php echo $this->Html->url(array(
                                    'controller' => 'edu_payments',
                                    'action' => 'make_payments')); ?>/" + selected_student_id,
								success: function(response, opts) {
									var eduPayment_data = response.responseText;
									eval(eduPayment_data);
									EduRegistrationAddForm.getForm().reset();
								},
								failure: function(response, opts) {
									Ext.Msg.alert("<?php __('Error'); ?>",
                                        "<?php __('Cannot get the Student payment form. Error code'); ?>: " +
                                        response.status);
								}
							});
						} else {
							var amount = Ext.getCmp('data[EduPayment][amount]');
							if(amount.getValue() != 0)
								printReceipt();
							EduRegistrationAddForm.getForm().reset();
						}
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
				EduRegistrationAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduRegistrationData();
<?php } else { ?>
						RefreshEduRegistrationData();
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
				EduRegistrationAddWindow.close();
			}
		}]
	});
	
	EduRegistrationAddWindow.show();
	
	var popUpWin_reg=0;
	
    function popUpWindow(URLStr, left, top, width, height) {
        if(popUpWin_reg){
            if(!popUpWin_reg.closed) popUpWin_reg.close();
        }
        popUpWin_reg = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,'+
            'menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+
            ',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printReceipt() {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_receipts', 'action' => 'print_receipt', 'plugin' => 'edu')); ?>";
        popUpWindow(url, 200, 200, 700, 1000);
    }
