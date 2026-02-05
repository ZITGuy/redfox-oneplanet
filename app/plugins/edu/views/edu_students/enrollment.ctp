//<script>
    <?php
        $this->ExtForm->create('EduStudent');
        $this->ExtForm->defineFieldFunctions();
	    $this->ExtForm->create('EduParent');
        $this->ExtForm->defineFieldFunctions();
        $this->ExtForm->create('EduParentDetail');
        $this->ExtForm->defineFieldFunctions();
    ?>

    function populateParentInfo(id) {
        EnrollmentForm.el.mask('Please wait', 'x-mask-loading');
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'populate_parent_info')); ?>/'+id,
            success: function(response, opts) {
                var populate_parent_info = response.responseText;

                eval(populate_parent_info);
                
                EnrollmentForm.el.unmask();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('ERROR'); ?>', '<?php __('Unexpected Error. Please press F5 to refresh the application.'); ?>: ' + response.status);
                
                EnrollmentForm.el.unmask();
            }
        });
    }
	
	var store_sections = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'		
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data2')); ?>'
		})
	});
    
    function parentsValidation() {
        return true;
    }
    
    function EnableParentInfoFields() {
        Ext.getCmp('marital_status').enable();
        Ext.getCmp('primaryParentBox').enable().setValue('Mother');
        Ext.getCmp('authorized_person').enable().setValue('');
        Ext.getCmp('phone_for_sms').enable().setValue('');
        Ext.getCmp('motherName1').enable().setValue('');
        Ext.getCmp('motherName2').enable().setValue('');
        Ext.getCmp('motherName3').enable().setValue('');
        Ext.getCmp('mother_residence_address').enable().setValue('');
        Ext.getCmp('mother_nationality').enable().setValue('Ethiopian');
        Ext.getCmp('mother_country_of_birth').enable().setValue('Ethiopia');
        Ext.getCmp('mother_occupation').enable().setValue('');
        Ext.getCmp('mother_academic_qualification').enable().setValue('Not Applicable');
        Ext.getCmp('mother_employment').enable().setValue('Not Applicable');
        Ext.getCmp('mother_employment_organization').enable().setValue('');
        Ext.getCmp('motherMobile').enable().setValue('');
        Ext.getCmp('motherWorkAddress').enable().setValue('');
        Ext.getCmp('motherWorkTelephone').enable().setValue('');
        Ext.getCmp('motherEmail').enable().setValue('');
        Ext.getCmp('motherPobox').enable().setValue('');

        Ext.getCmp('fatherName1').enable().setValue('');
        Ext.getCmp('fatherName2').enable().setValue('');
        Ext.getCmp('fatherName3').enable().setValue('');
        Ext.getCmp('father_residence_address').enable().setValue('');
        Ext.getCmp('father_nationality').enable().setValue('Ethiopian');
        Ext.getCmp('father_country_of_birth').enable().setValue('Ethiopia');
        Ext.getCmp('father_occupation').enable().setValue('');
        Ext.getCmp('father_academic_qualification').enable().setValue('Not Applicable');
        Ext.getCmp('father_employment').enable().setValue('Not Applicable');
        Ext.getCmp('father_employment_organization').enable().setValue('');
        Ext.getCmp('fatherMobile').enable().setValue('');
        Ext.getCmp('fatherWorkAddress').enable().setValue('');
        Ext.getCmp('fatherWorkTelephone').enable().setValue('');
        Ext.getCmp('fatherEmail').enable().setValue('');
        Ext.getCmp('fatherPobox').enable().setValue('');

        Ext.getCmp('guardianName1').enable().setValue('');
        Ext.getCmp('guardianName2').enable().setValue('');
        Ext.getCmp('guardianName3').enable().setValue('');
        Ext.getCmp('guardian_residence_address').enable().setValue('');
        Ext.getCmp('guardian_nationality').enable().setValue('Ethiopian');
        Ext.getCmp('guardian_country_of_birth').enable().setValue('Ethiopia');
        Ext.getCmp('guardian_relationship').enable().setValue('');
        Ext.getCmp('guardian_relationship_other').enable().setValue('');
        Ext.getCmp('guardian_occupation').enable().setValue('Not Applicable');
        Ext.getCmp('guardian_academic_qualification').enable().setValue('Not Applicable');
        Ext.getCmp('guardian_employment').enable().setValue('Not Applicable');
        Ext.getCmp('guardian_employment_organization').enable().setValue('');
        Ext.getCmp('guardianMobile').enable().setValue('');
        Ext.getCmp('guardianWorkAddress').enable().setValue('');
        Ext.getCmp('guardianWorkTelephone').enable().setValue('');
        Ext.getCmp('guardianEmail').enable().setValue('');
        Ext.getCmp('guardianPobox').enable().setValue('');
            
        Ext.getCmp('siblings').enable();
        Ext.getCmp('subscriptions').enable();
        Ext.getCmp('parent_user_info_fieldset').enable();
    }
    
    function DisableParentInfoFields() {
        Ext.getCmp('marital_status').disable();
        Ext.getCmp('primaryParentBox').disable();
        Ext.getCmp('authorized_person').disable();
        Ext.getCmp('phone_for_sms').disable();
        Ext.getCmp('motherShortName').disable();
        Ext.getCmp('motherName1').disable();
        Ext.getCmp('motherName2').disable();
        Ext.getCmp('motherName3').disable();
        Ext.getCmp('mother_residence_address').disable();
        Ext.getCmp('mother_nationality').disable();
        Ext.getCmp('mother_country_of_birth').disable();
        Ext.getCmp('mother_occupation').disable();
        Ext.getCmp('mother_academic_qualification').disable();
        Ext.getCmp('mother_employment').disable();
        Ext.getCmp('mother_employment_organization').disable();
        Ext.getCmp('motherMobile').disable();
        Ext.getCmp('motherWorkAddress').disable();
        Ext.getCmp('motherWorkTelephone').disable();
        Ext.getCmp('motherEmail').disable();
        Ext.getCmp('motherPobox').disable();

        Ext.getCmp('fatherShortName').disable();
        Ext.getCmp('fatherName1').disable();
        Ext.getCmp('fatherName2').disable();
        Ext.getCmp('fatherName3').disable();
        Ext.getCmp('father_residence_address').disable();
        Ext.getCmp('father_nationality').disable();
        Ext.getCmp('father_country_of_birth').disable();
        Ext.getCmp('father_occupation').disable();
        Ext.getCmp('father_academic_qualification').disable();
        Ext.getCmp('father_employment').disable();
        Ext.getCmp('father_employment_organization').disable();
        Ext.getCmp('fatherMobile').disable();
        Ext.getCmp('fatherWorkAddress').disable();
        Ext.getCmp('fatherWorkTelephone').disable();
        Ext.getCmp('fatherEmail').disable();
        Ext.getCmp('fatherPobox').disable();

        Ext.getCmp('guardianShortName').disable();
        Ext.getCmp('guardianName1').disable();
        Ext.getCmp('guardianName2').disable();
        Ext.getCmp('guardianName3').disable();
        Ext.getCmp('guardian_residence_address').disable();
        Ext.getCmp('guardian_nationality').disable();
        Ext.getCmp('guardian_country_of_birth').disable();
        Ext.getCmp('guardian_relationship').disable();
        Ext.getCmp('guardian_relationship_other').disable();
        Ext.getCmp('guardian_occupation').disable();
        Ext.getCmp('guardian_academic_qualification').disable();
        Ext.getCmp('guardian_employment').disable();
        Ext.getCmp('guardian_employment_organization').disable();
        Ext.getCmp('guardianMobile').disable();
        Ext.getCmp('guardianWorkAddress').disable();
        Ext.getCmp('guardianWorkTelephone').disable();
        Ext.getCmp('guardianEmail').disable();
        Ext.getCmp('guardianPobox').disable();
        
        Ext.getCmp('siblings').disable();
        Ext.getCmp('subscriptions').disable();
        Ext.getCmp('parent_user_info_fieldset').disable();
    }
    
    var classPayments = [
<?php foreach($class_payments as $cp) {
        echo '[' . $cp['EduClassPayment']['id'] . ', ' . $cp['EduClassPayment']['enrollment_fee'] . ', ' . $cp['EduClassPayment']['registration_fee'] . ', "' . $cp['EduClassPayment']['edu_class_id'] . '_' . $cp['EduClassPayment']['edu_academic_year_id'] . '"],';
}
?>
    ];
    
    var class_payments_store = new Ext.data.ArrayStore({
        fields: [
           {name: 'id'},
           {name: 'enrollment_fee',       type: 'float'},
           {name: 'registration_fee',     type: 'float'},
		   {name: 'class_ay'}
        ]
    });
    
    class_payments_store.loadData(classPayments);
    
    var EnrollmentForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        fileUpload: true,
        isUpload: true,
        labelWidth: 150,
        labelAlign: 'right',
        buttonAlign: 'center',
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
                    items: [<?php
                        $this->ExtForm->create('EduStudent');
                        $options05_1 = array('anchor' => '90%', 'id' => 'data[EduStudent][name]');
                        $options05_1['listeners'] = "{
                                scope: this,
                                'blur': function(textField){
                                    var name = textField.getValue();
                                    textField.setValue(name.toUpperCase());
                                }
                            }";
                        $this->ExtForm->input('name', $options05_1);
                    ?>, {
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
                        $options21 = array('anchor' => '50%', 
							'fieldLabel' => 'Country of Birth', 
							'xtype' => 'combo',
							'value' => 'Ethiopia'
						);
                        $options21['items'] = $countries;
                        $this->ExtForm->input('birth_country', $options21);
                    ?>,
                    <?php
                        $options3 = array('anchor' => '50%', 'fieldLabel' => 'Gender', 'xtype' => 'combo');
                        $options3['items'] = array('F' => 'Female', 'M' => 'Male');
                        $this->ExtForm->input('gender', $options3);
                    ?>,
                    <?php
                        $options4 = array('anchor' => '50%', 'xtype' => 'combo', 
							'items' => $nationalities, 
							'fieldLabel' => 'Nationality', 
							'value' => 'Ethiopian');
                        $this->ExtForm->input('nationality', $options4);
                    ?>,
                    <?php
                        $options41 = array('anchor' => '50%', 'fieldLabel' => 'Language at Home', 'value' => 'Amharic');
                        $this->ExtForm->input('home_language', $options41);
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
                                                $this->ExtForm->input('sub_city_id', $options51);
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
                                                $options1 = array('anchor' => '90%', 'fieldLabel' => 'Current Class/Grade');
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
                                                                var ayCombo = Ext.getCmp('data[EduStudent][edu_academic_year_id]')
																var ayValue = ayCombo.getValue();
																

                                                                /*rec_index = class_payments_store.find('class_ay', combo.getValue() + '_' + ayValue);
                                                                rec = class_payments_store.getAt(rec_index);
                                                                pay = rec.get('enrollment_fee');

                                                                if(incld.getValue()) {
                                                                    if($academic_year_id == combo.getValue())
																		pay = rec.get('enrollment_fee') + rec.get('registration_fee');
																	else
																		pay = rec.get('registration_fee');
																}
                                                                amountTxt = Ext.getCmp('data[EduPayment][amount]');
                                                                amountTxt.setValue(pay);*/
																
																var edu_section_id = Ext.getCmp('edu_section_id');
																edu_section_id.setValue('');
																edu_section_id.store.removeAll();
																edu_section_id.store.reload({
																	params: {
																		edu_class_id : combo.getValue()
																	}
																});
                                                            }
                                                        }
                                                    }";
                                                $this->ExtForm->input('edu_class_id', $options1);
												
                                            ?>, {
												xtype: 'combo',
												//disabled: <?php echo ($current_quarter['EduQuarter']['quarter_type'] == 'E')? 'false': 'true'; ?>,
												emptyText: 'All',
												name: 'edu_section_id',
												hiddenName: 'data[EduStudent][edu_section_id]',
												id:'edu_section_id',
												typeAhead: true,
												store : store_sections,
												displayField : 'name',
												valueField : 'id',
												anchor:'90%',
												fieldLabel: '<span style="color:red;">*</span> Section',
												mode: 'local',
												allowBlank: false,
												emptyText: 'Select Section',
												editable: false,
												triggerAction: 'all'
											}
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        items: [
                                            <?php
                                                $options52 = array('anchor' => '95%', 'id' => 'data[EduPayment][include_registration]', 
                                                    'xtype' => 'checkbox', 'checked' => true, 'disabled' => 'true');
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
                                                                includeMonthlyPmt = Ext.getCmp('data[EduPayment][include_monthly_payments]');
                                                                if(is_chcked){
                                                                    pay = rec.get('enrollment_fee') + rec.get('registration_fee');
                                                                    includeMonthlyPmt.setVisible( true );
                                                                } else {
                                                                    pay = rec.get('enrollment_fee');
                                                                    includeMonthlyPmt.setVisible( false );
                                                                }
                                                                amountTxt = Ext.getCmp('data[EduPayment][amount]');
                                                                amountTxt.setValue(pay);
                                                            }
                                                        }
                                                    }";
                                                $this->ExtForm->input('include_registration', $options52);
                                            ?>,
											<?php
                                                $options_ey_1 = array('anchor' => '90%', 'fieldLabel' => 'Enrolled For');
                                                $options_ey_1['items'] = $academic_years;
                                                $options_ey_1['value'] = $academic_year_id;
                                                $options_ey_1['id'] = 'data[EduStudent][edu_academic_year_id]';
												$options_ey_1['listeners'] = "{
                                                        scope: this,
                                                        'select': function(combo, record, index){
                                                            x = new RegExp(\"abc\");
															incld = Ext.getCmp('data[EduPayment][include_registration]');
															var clCombo = Ext.getCmp('data[EduStudent][edu_class_id]')
															var clValue = clCombo.getValue();
															
															rec_index = class_payments_store.find('class_ay', clValue + '_' + combo.getValue());
															rec = class_payments_store.getAt(rec_index);
															pay = rec.get('enrollment_fee');
															if(incld.getValue()) {
																if($academic_year_id == combo.getValue())
																	pay = rec.get('enrollment_fee') + rec.get('registration_fee');
																else
																	pay = rec.get('registration_fee');
															}
															amountTxt = Ext.getCmp('data[EduPayment][amount]');
															amountTxt.setValue(pay);
                                                        }
                                                    }";
                                                $this->ExtForm->input('edu_academic_year_id', $options_ey_1);
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
									checked: true,
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
                    }]
                }, {
                    title: 'Family Info',
                    id: 'parent_tab',
                    layout: 'form',
                    defaultType: 'textfield',
                    disabled: true,
                    items: [{
                            xtype: 'combo',
                            store: new Ext.data.ArrayStore({
                                id: 0,
                                fields: [
                                    'id',
                                    'name'
                                ],
                                data: [['0', 'New Parent'], <?php  foreach ($edu_parents as $k => $v) { ?>['<?php echo $k; ?>', '<?php echo $v; ?>'], <?php } ?>]
                            }),
                            displayField: 'name',
                            typeAhead: true,
                            anchor: '60%',
                            id: 'data[EduStudent][edu_parent_id]',
                            name: 'data[EduStudent][edu_parent_id]',
                            mode: 'local',
                            triggerAction: 'all',
                            emptyText: 'Select One...',
                            selectOnFocus: true,
                            valueField: 'id',
                            value: 0,
                            fieldLabel: 'Select Parent',
                            allowBlank: false,
                            listeners: {
                                scope: this,
                                'select': function(combo, record, index){
                                    if(combo.getValue() == '0'){
                                        EnableParentInfoFields();
                                    } else {
                                        populateParentInfo(combo.getValue());
                                        DisableParentInfoFields();
                                    }
                                }
                            }
                        }, <?php
                            $this->ExtForm->create('EduParent');
                            $options051 = array(
                                'anchor' => '60%',
                                'id' => 'marital_status',
                                'fieldLabel' => 'Marital Status',
                                'value' => 'M',
                                'xtype' => 'combo',
                                'items' => array('S' => 'Single', 'M' => 'Married', 'D' => 'Divorsed', 'W' => 'Widowed', 'P' => 'Separated'));
                            $this->ExtForm->input('marital_status', $options051);
                        ?>, <?php
                            $options052 = array(
                                'anchor' => '60%',
                                'fieldLabel' => 'Primary Parent',
                                'xtype' => 'combo', 'value' => 'M', 'id' => 'primaryParentBox');
                            $options052['listeners'] = "{
                                        scope: this,
                                        'select': function(combo, record, index){
                                            var ftabs = Ext.getCmp('family_tabs');
                                            var ft = ftabs.findById(combo.getValue() + '_tab');
                                            ftabs.setActiveTab(ft);
                                        }
                                    }";
                            $options052['items'] = array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian');
                            $this->ExtForm->input('primary_parent', $options052);
                        ?>, <?php
                            $options0555 = array(
                                'anchor' => '90%',
                                'fieldLabel' => 'Authorized Person', 
                                'id' => 'authorized_person',
                                'emptyText' => '[Authorized person to take student from school]');
                            $options0555['listeners'] = "{
                                scope: this,
                                'blur': function(textField){
                                    var authorized_name = textField.getValue();
                                    textField.setValue(authorized_name.toUpperCase());
                                }
                            }";
                            $this->ExtForm->input('authorized_person', $options0555);
                        ?>, <?php
                            $options0555p = array(
                                'anchor' => '90%',
                                'fieldLabel' => 'Phone No for SMS', 
                                'id' => 'phone_for_sms',
                                'vtype' => 'mphone',
                                'emptyText' => '2519xxxxxxxx');
                            $options0555p['listeners'] = "{
                                scope: this,
                                'blur': function(textField){
                                    
                                }
                            }";
                            $this->ExtForm->input('sms_phone_number', $options0555p);
                        ?>, {
                            xtype: 'fieldset', 
                            title: 'Detailed Family Information',
                            id: 'field[family_information]',
                            collapsible: false,
                            items: [{
                                xtype: 'tabpanel',
                                activeTab: 0,
                                height: 280,
                                id: 'family_tabs',
                                listeners: {
                                    'tabchange': function(t, p){
                                        if(p.getId() == 'user_info_tab'){
                                            Ext.getCmp('next').enable();
                                        }
                                    }
                                },
                                tabWidth: 225,
                                defaults: {bodyStyle: 'padding:10px'},
                                items: [{
                                    title: 'Mother',
                                    layout: 'form',
                                    defaultType: 'textfield',
                                    id: 'M_tab',
                                    items: [{
                                            xtype: 'compositefield',
                                            fieldLabel: 'Full Name',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $this->ExtForm->create('EduParentDetail');
                                                    $options04_11 = array('anchor' => '90%', 
                                                        'emptyText' => '[First Name]', 
                                                        'id' => 'motherName1',
														'enableKeyEvents' => 'true'
                                                    );
                                                    $options04_11['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var motherName1 = textField.getValue();
                                                            textField.setValue(motherName1.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var motherName1 = textField.getValue().trim();
																textField.setValue(motherName1.toUpperCase());
																
																var motherName2 = Ext.getCmp('motherName2');
																motherName2.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('mother_name1', $options04_11);
                                                ?>,
                                                <?php
                                                    $options05_11 = array('anchor' => '90%', 
                                                        'emptyText' => '[Middle Name]', 
                                                        'id' => 'motherName2',
														'enableKeyEvents' => 'true');
                                                    $options05_11['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var motherName2 = textField.getValue();
                                                            textField.setValue(motherName2.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var motherName2 = textField.getValue().trim();
																textField.setValue(motherName2.toUpperCase());
																
																var motherName3 = Ext.getCmp('motherName3');
																motherName3.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('mother_name2', $options05_11);
                                                ?>,
                                                <?php
                                                    $options06 = array('anchor' => '90%', 
                                                        'emptyText' => '[Last Name]', 
                                                        'id' => 'motherName3',
														'enableKeyEvents' => 'true');
                                                    $options06['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var motherName3 = textField.getValue();
                                                            textField.setValue(motherName3.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var motherName3 = textField.getValue().trim();
																textField.setValue(motherName3.toUpperCase());
																
																var motherShortName = Ext.getCmp('motherShortName');
																motherShortName.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('mother_name3', $options06);
                                                ?>,
                                                <?php
                                                    $options07 = array('anchor' => '90%', 
                                                        'emptyText' => '[Short Name]', 
                                                        'id' => 'motherShortName',
														'enableKeyEvents' => 'true');
                                                    $options07['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var motherShortName = textField.getValue();
                                                            textField.setValue(motherShortName.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var motherShortName = textField.getValue().trim();
																textField.setValue(motherShortName.toUpperCase());
																
																var mother_residence_address = Ext.getCmp('mother_residence_address');
																mother_residence_address.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('mother_short_name', $options07);
                                                ?>
                                            ]
                                        },
                                        <?php
                                            $options0554 = array('anchor' => '90%', 
                                                'fieldLabel' => 'Residence Address', 
                                                'id' => 'mother_residence_address');
                                            $this->ExtForm->input('mother_residence_address', $options0554);
                                        ?>, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Nationality',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
												<?php
                                                    $options07a = array('anchor' => '90%', 
                                                        'emptyText' => '[Nationality]', 
                                                        'xtype' => 'combo', 
														'items' => $nationalities, 
														'fieldLabel' => 'Nationality', 
														'value' => 'Ethiopian', 
														'id' => 'mother_nationality');
                                                    
                                                    $this->ExtForm->input('mother_nationality', $options07a);
                                                ?>,
												<?php
                                                    $options07b = array('anchor' => '90%', 
                                                        'emptyText' => '[Country of Birth]', 
                                                        'fieldLabel' => 'Country of Birth', 
														'value' => '',
														'id' => 'mother_country_of_birth');
                                                    
                                                    $this->ExtForm->input('mother_country_of_birth', $options07b);
                                                ?>
											]
										}, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Address',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $options04 = array('anchor' => '90%', 
                                                        'emptyText' => '[Mobile]', 
                                                        'id' => 'motherMobile');
                                                    $this->ExtForm->input('mother_mobile', $options04);
                                                ?>,
                                                <?php
                                                    $options05 = array('anchor' => '90%', 
                                                        'emptyText' => '[Work Address]', 
                                                        'id' => 'motherWorkAddress');
                                                    $this->ExtForm->input('mother_work_address', $options05);
                                                ?>,
                                                <?php
                                                    $options06a = array('anchor' => '90%', 
                                                        'emptyText' => '[Work Telephone]', 
                                                        'id' => 'motherWorkTelephone');
                                                    $this->ExtForm->input('mother_work_telephone', $options06a);
                                                ?>
                                            ]
                                        }, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Email',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
												<?php
													$optionsge05 = array('anchor' => '90%', 'emptyText' => '[Email]', 
														'id' => 'motherEmail', 'vtype' => 'email');
													$this->ExtForm->input('mother_email', $optionsge05);
												?>,
												<?php
													$optionsge05 = array('anchor' => '90%', 'emptyText' => '[P.o.Box]', 
														'id' => 'motherPobox');
													$this->ExtForm->input('mother_pobox', $optionsge05);
												?>
											]
										},
                                        <?php
                                            $options0556 = array(
                                                'anchor' => '90%',
                                                'fieldLabel' => 'Occupation', 
                                                'id' => 'mother_occupation');
                                            $this->ExtForm->input('mother_occupation', $options0556);
                                        ?>,
                                        <?php
                                            $options0558 = array('anchor' => '70%', 
                                                'fieldLabel' => 'Academic Qualification', 
                                                'xtype' => 'combo', 
                                                'value' => 'NA', 
                                                'id' => 'mother_academic_qualification',
                                                'items' => array('NA' => 'Not Applicable', 'Diploma' => 'Diploma', 'BA' => 'BA', 'BSc' => 'BSc', 'MA' => 'MA', 'MSc' => 'MSc', 'PhD' => 'PhD', 'Other' => 'Other')
                                            );
                                            $this->ExtForm->input('mother_academic_qualification', $options0558);
                                        ?>,
                                        <?php
                                            $options0559 = array('anchor' => '70%', 'xtype' => 'combo',
                                                'value' => 'NA', 
                                                'id' => 'mother_employment',
                                                'items' => array('NA' => 'Not Applicable', 'E' => 'Employed', 'S' => 'Self Employed', 'N' => 'Not Employed'), 
                                                'fieldLabel' => 'Employment');
                                            $this->ExtForm->input('mother_employment', $options0559);
                                        ?>,
                                        <?php
                                            $options0560 = array('anchor' => '90%', 
                                                'fieldLabel' => 'Employment Organization', 
                                                'id' => 'mother_employment_organization');
                                            $this->ExtForm->input('mother_employment_organization', $options0560);
                                        ?>
									]
                                }, {
                                    title: 'Father',
                                    layout: 'form',
                                    defaultType: 'textfield',
                                    id: 'F_tab',
                                    items: [{
                                            xtype: 'compositefield',
                                            fieldLabel: 'Full Name',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsf04 = array('anchor' => '90%', 
                                                        'emptyText' => '[First Name]', 
                                                        'id' => 'fatherName1',
														'enableKeyEvents' => 'true');
                                                    $optionsf04['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var fatherName1 = textField.getValue();
                                                            textField.setValue(fatherName1.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var fatherName1 = textField.getValue().trim();
																textField.setValue(fatherName1.toUpperCase());
																
																var fatherName2 = Ext.getCmp('fatherName2');
																fatherName2.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('father_name1', $optionsf04);
                                                ?>,
                                                <?php
                                                    $optionsf05_11 = array('anchor' => '90%', 
                                                        'emptyText' => '[Middle Name]', 
                                                        'id' => 'fatherName2',
														'enableKeyEvents' => 'true');
                                                    $optionsf05_11['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var fatherName2 = textField.getValue();
                                                            textField.setValue(fatherName2.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var fatherName2 = textField.getValue().trim();
																textField.setValue(fatherName2.toUpperCase());
																
																var fatherName3 = Ext.getCmp('fatherName3');
																fatherName3.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('father_name2', $optionsf05_11);
                                                ?>,
                                                <?php
                                                    $optionsf06 = array('anchor' => '90%', 
                                                        'emptyText' => '[Last Name]', 
                                                        'id' => 'fatherName3',
														'enableKeyEvents' => 'true');
                                                    $optionsf06['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var fatherName3 = textField.getValue();
                                                            textField.setValue(fatherName3.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var fatherName3 = textField.getValue().trim();
																textField.setValue(fatherName3.toUpperCase());
																
																var fatherShortName = Ext.getCmp('fatherShortName');
																fatherShortName.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('father_name3', $optionsf06);
                                                ?>,
                                                <?php
                                                    $options07 = array('anchor' => '90%', 
                                                        'emptyText' => '[Short Name]', 
                                                        'id' => 'fatherShortName',
														'enableKeyEvents' => 'true');
                                                    $options07['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var fatherShortName = textField.getValue();
                                                            textField.setValue(fatherShortName.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var fatherShortName = textField.getValue().trim();
																textField.setValue(fatherShortName.toUpperCase());
																
																var father_residence_address = Ext.getCmp('father_residence_address');
																father_residence_address.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('father_short_name', $options07);
                                                ?>
                                            ]
                                        },
                                        <?php
                                            $optionsf0554 = array('anchor' => '90%', 
                                                'fieldLabel' => 'Residence Address', 
                                                'id' => 'father_residence_address');
                                            $this->ExtForm->input('father_residence_address', $optionsf0554);
                                        ?>, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Nationality',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
												<?php
                                                    $options07a = array('anchor' => '90%', 
                                                        'emptyText' => '[Nationality]', 
                                                        'xtype' => 'combo', 
														'items' => $nationalities, 
														'fieldLabel' => 'Nationality', 
														'value' => 'Ethiopian', 
														'id' => 'father_nationality');
                                                    
                                                    $this->ExtForm->input('father_nationality', $options07a);
                                                ?>,
												<?php
                                                    $options07b = array('anchor' => '90%', 
                                                        'emptyText' => '[Country of Birth]', 
                                                        'fieldLabel' => 'Country of Birth', 
														'value' => '',
														'id' => 'father_country_of_birth');
                                                    
                                                    $this->ExtForm->input('father_country_of_birth', $options07b);
                                                ?>
											]
										}, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Address',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsf04 = array('anchor' => '90%', 
                                                        'emptyText' => '[Mobile]', 
                                                        'id' => 'fatherMobile');
                                                    $this->ExtForm->input('father_mobile', $optionsf04);
                                                ?>,
                                                <?php
                                                    $optionsf05 = array('anchor' => '90%', 
                                                        'emptyText' => '[Work Address]', 
                                                        'id' => 'fatherWorkAddress');
                                                    $this->ExtForm->input('father_work_address', $optionsf05);
                                                ?>,
                                                <?php
                                                    $optionsf06 = array('anchor' => '90%', 
                                                        'emptyText' => '[Work Telephone]', 
                                                        'id' => 'fatherWorkTelephone');
                                                    $this->ExtForm->input('father_work_telephone', $optionsf06);
                                                ?>
                                            ]
                                        }, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Email',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
												<?php
													$optionsge05 = array('anchor' => '90%', 'emptyText' => '[Email]', 
														'id' => 'fatherEmail', 'vtype' => 'email');
													$this->ExtForm->input('father_email', $optionsge05);
												?>,
												<?php
													$optionsge05 = array('anchor' => '90%', 'emptyText' => '[P.o.Box]', 
														'id' => 'fatherPobox');
													$this->ExtForm->input('father_pobox', $optionsge05);
												?>
											]
										}, 
                                        <?php
                                            $optionsf0556 = array(
                                                'anchor' => '90%',
                                                'fieldLabel' => 'Occupation', 
                                                'id' => 'father_occupation');
                                            $this->ExtForm->input('father_occupation', $optionsf0556);
                                        ?>,
                                        <?php
                                            $optionsf0558 = array('anchor' => '70%', 
                                                'fieldLabel' => 'Academic Qualification', 
                                                'xtype' => 'combo', 
                                                'value' => 'NA', 
                                                'id' => 'father_academic_qualification',
                                                'items' => array('NA' => 'Not Applicable', 'Diploma' => 'Diploma', 'BA' => 'BA', 'BSc' => 'BSc', 'MA' => 'MA', 'MSc' => 'MSc', 'PhD' => 'PhD', 'Other' => 'Other')
                                            );
                                            $this->ExtForm->input('father_academic_qualification', $optionsf0558);
                                        ?>,
                                        <?php
                                            $optionsf0559 = array('anchor' => '70%', 'xtype' => 'combo', 
                                                'value' => 'NA', 
                                                'id' => 'father_employment',
                                                'items' => array('NA' => 'Not Applicable', 'E' => 'Employed', 'S' => 'Self Employed', 'N' => 'Not Employed'), 
                                                'fieldLabel' => 'Employment', 'value' => 'Employed');
                                            $this->ExtForm->input('father_employment', $optionsf0559);
                                        ?>,
                                        <?php
                                            $optionsf0560 = array(
                                                'anchor' => '90%', 
                                                'id' => 'father_employment_organization', 
                                                'fieldLabel' => 'Employment Organization'
                                            );
                                            $this->ExtForm->input('father_employment_organization', $optionsf0560);
                                        ?>
									]
                                }, {
                                    title: 'Guardian',
                                    layout: 'form',
                                    defaultType: 'textfield',
                                    id: 'G_tab',
                                    items: [{
                                            xtype: 'compositefield',
                                            fieldLabel: 'Full Name',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsg04 = array('anchor' => '90%', 
                                                        'emptyText' => '[First Name]', 
                                                        'id' => 'guardianName1',
														'enableKeyEvents' => 'true');
                                                    $optionsg04['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var guardianName1 = textField.getValue();
                                                            textField.setValue(guardianName1.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var guardianName1 = textField.getValue().trim();
																textField.setValue(guardianName1.toUpperCase());
																
																var guardianName2 = Ext.getCmp('guardianName2');
																guardianName2.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('guardian_name1', $optionsg04);
                                                ?>,
                                                <?php
                                                    $optionsg05 = array('anchor' => '90%', 
														'emptyText' => '[Middle Name]', 
														'id' => 'guardianName2',
														'enableKeyEvents' => 'true');
													$optionsg05['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var guardianName2 = textField.getValue();
                                                            textField.setValue(guardianName2.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var guardianName2 = textField.getValue().trim();
																textField.setValue(guardianName2.toUpperCase());
																
																var guardianName3 = Ext.getCmp('guardianName3');
																guardianName3.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('guardian_name2', $optionsg05);
                                                ?>,
                                                <?php
                                                    $optionsg06 = array('anchor' => '90%', 
														'emptyText' => '[Last Name]', 
                                                        'id' => 'guardianName3',
														'enableKeyEvents' => 'true');
                                                    $optionsg06['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var guardianName3 = textField.getValue();
                                                            textField.setValue(guardianName3.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var guardianName3 = textField.getValue().trim();
																textField.setValue(guardianName3.toUpperCase());
																
																var guardianShortName = Ext.getCmp('guardianShortName');
																guardianShortName.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('guardian_name3', $optionsg06);
                                                ?>,
                                                <?php
                                                    $options07 = array('anchor' => '90%', 
                                                        'emptyText' => '[Short Name]', 
                                                        'id' => 'guardianShortName',
														'enableKeyEvents' => 'true');
                                                    $options07['listeners'] = "{
                                                        scope: this,
                                                        'blur': function(textField){
                                                            var guardianShortName = textField.getValue();
                                                            textField.setValue(guardianShortName.toUpperCase());
                                                        },
														'keyup': function(textField, e) {
															var result = textField.getValue().indexOf(' ');
															if (result > -1) {
																var guardianShortName = textField.getValue().trim();
																textField.setValue(guardianShortName.toUpperCase());
																
																var guardian_residence_address = Ext.getCmp('guardian_residence_address');
																guardian_residence_address.focus();
															}
														}
                                                    }";
                                                    $this->ExtForm->input('guardian_short_name', $options07);
                                                ?>
                                            ]
                                        },
                                        <?php
                                            $optionsg0554 = array('anchor' => '90%', 
                                                'fieldLabel' => 'Residence Address', 
                                                'id' => 'guardian_residence_address');
                                            $this->ExtForm->input('guardian_residence_address', $optionsg0554);
                                        ?>, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Nationality',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
												<?php
                                                    $options07a = array('anchor' => '90%', 
                                                        'emptyText' => '[Nationality]', 
                                                        'xtype' => 'combo', 
														'items' => $nationalities, 
														'fieldLabel' => 'Nationality', 
														'value' => 'Ethiopian', 
														'id' => 'guardian_nationality');
                                                    
                                                    $this->ExtForm->input('guardian_nationality', $options07a);
                                                ?>,
												<?php
                                                    $options07b = array('anchor' => '90%', 
                                                        'emptyText' => '[Country of Birth]', 
                                                        'fieldLabel' => 'Country of Birth', 
														'value' => '',
														'id' => 'guardian_country_of_birth');
                                                    
                                                    $this->ExtForm->input('guardian_country_of_birth', $options07b);
                                                ?>
											]
										},
                                        {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Relationship',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsg0555 = array(
                                                        'anchor' => '70%', 'xtype' => 'combo', 
                                                        'value' => 'NA',
                                                        'items' => array('NA' => 'Not Applicable', 'Aunt' => 'Aunt', 'Uncle' => 'Uncle', 'Cousin' => 'Cousin', 'Grand mother' => 'Grand mother',
                                                                'Sister' => 'Sister', 'Brother' => 'Brother', 'Other' => 'Other'
                                                            ), 
                                                        'id' => 'guardian_relationship',
                                                        'fieldLabel' => 'Relationship'
                                                    );
                                                    $this->ExtForm->input('guardian_relationship', $optionsg0555);
                                                ?>, <?php
                                                    $optionsg05551 = array(
                                                        'anchor' => '95%',
                                                        'fieldLabel' => 'Other', 
                                                        'id' => 'guardian_relationship_other', 
                                                        'emptyText' => '[Write here if any other relation]');
                                                    $this->ExtForm->input('guardian_relationship_other', $optionsg05551);
                                                ?>
                                            ]}, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Address',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsg004 = array('anchor' => '90%', 'emptyText' => '[Mobile]', 
                                                        'id' => 'guardianMobile');
                                                    $this->ExtForm->input('guardian_mobile', $optionsg004);
                                                ?>,
                                                <?php
                                                    $optionsg005 = array('anchor' => '90%', 'emptyText' => '[Work Address]', 
                                                        'id' => 'guardianWorkAddress');
                                                    $this->ExtForm->input('guardian_work_address', $optionsg005);
                                                ?>,
                                                <?php
                                                    $optionsg006 = array('anchor' => '90%', 'emptyText' => '[Work Telephone]', 
                                                        'id' => 'guardianWorkTelephone');
                                                    $this->ExtForm->input('guardian_work_telephone', $optionsg006);
                                                ?>
                                            ]
                                        }, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Email',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
												<?php
													$optionsge05 = array('anchor' => '90%', 'emptyText' => '[Email]', 
														'id' => 'guardianEmail', 'vtype' => 'email');
													$this->ExtForm->input('guardian_email', $optionsge05);
												?>,
												<?php
													$optionsge05 = array('anchor' => '90%', 'emptyText' => '[P.o.Box]', 
														'id' => 'guardianPobox');
													$this->ExtForm->input('guardian_pobox', $optionsge05);
												?>
											]
										},
                                        <?php
                                            $optionsg0556 = array(
                                                'anchor' => '90%',
                                                'fieldLabel' => 'Occupation', 
                                                'id' => 'guardian_occupation');
                                            $this->ExtForm->input('guardian_occupation', $optionsg0556);
                                        ?>,
                                        <?php
                                            $optionsg0558 = array('anchor' => '70%', 
                                                'fieldLabel' => 'Academic Qualification', 
                                                'xtype' => 'combo', 
                                                'value' => 'NA', 
                                                'id' => 'guardian_academic_qualification',
                                                'items' => array('NA' => 'Not Applicable', 'Diploma' => 'Diploma', 'BA' => 'BA', 'BSc' => 'BSc', 'MA' => 'MA', 'MSc' => 'MSc', 'PhD' => 'PhD', 'Other' => 'Other')
                                            );
                                            $this->ExtForm->input('guardian_academic_qualification', $optionsg0558);
                                        ?>, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Employment',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsg0559 = array('anchor' => '90%', 'xtype' => 'combo', 
                                                        'value' => 'NA', 
                                                        'id' => 'guardian_employment',
                                                        'items' => array('NA' => 'Not Applicable', 'E' => 'Employed', 'S' => 'Self Employed', 'N' => 'Not Employed'), 
                                                        'fieldLabel' => 'Employment', 'value' => 'Employed');
                                                    $this->ExtForm->input('guardian_employment', $optionsg0559);
                                                ?>,
                                                <?php
                                                    $optionsg0560 = array('anchor' => '90%', 
                                                        'emptyText' => 'Employment Organization', 
                                                        'id' => 'guardian_employment_organization');
                                                    $this->ExtForm->input('guardian_employment_organization', $optionsg0560);
                                                ?>
                                            ]
                                        }
									]
                                }]
                            }]
                        }
                    ]
                }, {
                    title: 'Student Condition',
                    id: 'student_condition_tab',
                    layout: 'form',
                    defaultType: 'textfield',
                    disabled: true,
                    items: [{
                            xtype: 'fieldset',
                            title: 'Learning Condition',
                            collapsible: true,
                            items: [
                                <?php
                                    $this->ExtForm->create('EduStudentCondition');
                                    $options10 = array('anchor' => '90%', 
                                        'xtype' => 'checkbox', 
                                        'checked' => true,
                                        'fieldLabel' => 'Normal', 
                                        'id' => 'normal_learing_condition');
                                    $this->ExtForm->input('normal_learing_condition', $options10);
                                ?>,
                                <?php
                                    $options11 = array('anchor' => '90%', 
                                        'fieldLabel' => 'Special Need', 
                                        'id' => 'special_learning_need');
                                    $this->ExtForm->input('special_learning_need', $options11);
                                ?>, 
								new Ext.form.CheckboxGroup({
									id:'learningDifficulties',
									xtype: 'checkboxgroup',
									fieldLabel: 'Learning Difficulties',
									itemCls: 'x-check-group-alt',
									columns: 4,
									items: [{
											boxLabel: '<?php __('Reading'); ?>', 
											name: '<?php echo "data[EduStudentCondition][reading]"; ?>'
										}, {
											boxLabel: '<?php __('Math'); ?>', 
											name: '<?php echo "data[EduStudentCondition][math]"; ?>'
										}, {
											boxLabel: '<?php __('Language'); ?>',
											name: '<?php echo "data[EduStudentCondition][language]"; ?>'
										}, {
											boxLabel: '<?php __('Behavioral'); ?>', 
											name: '<?php echo "data[EduStudentCondition][behavioral]"; ?>'
										}
									]
								})
                            ]
                        }, {
                            xtype: 'fieldset',
                            title: 'Health Condition',
                            collapsible: true,
                            items: [
                                <?php
                                    $options10_0 = array('anchor' => '90%', 
                                        'xtype' => 'checkbox', 
                                        'checked' => true,
                                        'fieldLabel' => 'Normal', 
                                        'id' => 'normal_health_condition');
                                    $this->ExtForm->input('normal_health_condition', $options10_0);
                                ?>,
                                <?php
                                    $options11_0 = array('anchor' => '90%', 
                                        'fieldLabel' => 'Treatment Type', 
                                        'id' => 'treatment_type');
                                    $this->ExtForm->input('treatment_type', $options11_0);
                                ?>,
                                <?php
                                    $options12 = array('anchor' => '90%', 
                                        'id' => 'health_care_institute');
                                    $this->ExtForm->input('health_care_institute', $options12);
                                ?>,
                                <?php
                                    $options13 = array('anchor' => '60%', 
                                        'fieldLabel' => 'Name of Physician', 
                                        'id' => 'physician');
                                    $this->ExtForm->input('physician', $options13);
                                ?>,
                                <?php
                                    $options14 = array('anchor' => '60%', 
                                        'fieldLabel' => 'Alergy (If Any)', 
                                        'xtype' => 'textarea',
                                        'value' => 'Nothing', 
                                        'id' => 'alergy');
                                    $this->ExtForm->input('alergy', $options14);
                                ?>
                            ]
                        }, {
                            xtype: 'fieldset',
                            title: 'Physical Condition',
                            collapsible: true,
                            items: [
                                <?php
                                    $options10_1 = array('anchor' => '90%', 
                                        'xtype' => 'checkbox', 
                                        'checked' => true,
                                        'fieldLabel' => 'Normal', 
                                        'id' => 'normal_physical_condition');
                                    $this->ExtForm->input('normal_physical_condition', $options10_1);
                                ?>,
                                <?php
                                    $options11_1 = array('anchor' => '90%', 
                                        'fieldLabel' => 'Disabled', 
                                        'id' => 'physically_disabled');
                                    $this->ExtForm->input('physically_disabled', $options11_1);
                                ?>
                            ]
                        }
                    ]
                }, {
                    title: 'Payment and Preferences',
                    id: 'payment_tab',
                    layout: 'form',
                    disabled: true,
                    defaultType: 'textfield',
                    items: [{
                            xtype: 'fieldset',
                            title: 'Scholarship',
                            collapsible: false,
                            items: [
								<?php
									$this->ExtForm->create('EduRegistration');
									$options = array('anchor' => '35%', 'fieldLabel' => 'Tuition Scholarship (%)',
										'value' => '0');
									$this->ExtForm->input('scholarship', $options);
								?>,
								<?php
									$options = array('anchor' => '95%', 'fieldLabel' => 'Scholarship Reason');
									$this->ExtForm->input('scholarship_reason', $options);
								?>
							]
						},
                        {
                            xtype: 'fieldset',
                            title: 'Services Preferences',
                            collapsible: false,
                            items: [
								new Ext.form.CheckboxGroup({
									id:'myServicePreferences',
									xtype: 'checkboxgroup',
									fieldLabel: 'Services Preferences',
									itemCls: 'x-check-group-alt',
									columns: 3,
									items: [
		<?php
									$st = false;
									foreach($edu_exta_payment_types as $key => $value){
										if($st) echo ",";
		?>
										{
											boxLabel: '<?php echo Inflector::humanize($value); ?>', 
											name: '<?php echo "data[EduExtraPaymentType][" . $key . "]"; ?>'
										}
		<?php
										$st = true;
									}
		?>
									]
								}) // end of checkboxgroup
							]
						},
						
						<?php
                            $this->ExtForm->create('EduPayment');
                            $options21 = array('anchor' => '40%', 
                                'fieldLabel' => '<span style="color:red;">*</span> Amount',
                                'id' => 'data[EduPayment][amount]',
                                'allowBlank' => false, 'blankText' => 'Amount should be specified',
                                'style' => 'text-align: right;',
                                'maskRe' => '/^([0-9.,])*$/',
                                'readOnly' => true,
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
                                }, {
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
                                /*'value' => false,
                                'readOnly' => true,
                                'disabled' => true,
                                'boxLabel' => ' (This feature is not applicable right now. Please use Student > Accept Payment in stead.)',*/
                                'id' => 'data[EduPayment][include_monthly_payments]');
                            $options25['listeners'] = "{
                                    scope: this,
                                    'check': function(fld, isChecked){
                                        if(isChecked) {
                                            Ext.getCmp('next').setText('Continue');
                                        } else {
                                            Ext.getCmp('next').setText('Finish');
                                        }
                                    }
                                }";
                            $this->ExtForm->input('include_monthly_payments', $options25);
                        ?>
                    ]
                }]
        
			,
			buttons: [{
				text: 'Previous School Attended',
				pressed: true,
				listeners: {
					'click': function() {
						OpenPreviousSchool();
					}
				}
			}, '-', {
				text: 'Photos',
				pressed: true,
				listeners: {
					'click': function() {
						OpenPhoto();
					}
				}
			}, {
				text: 'Emergency Contact',
				pressed: true,
				listeners: {
					'click': function() {
						OpenEmergencyContact();
					}
				}
			}, {
				text: 'Siblings',
				pressed: true,
				listeners: {
					'click': function() {
						OpenSibling();
					}
				}
			}]
		}
    });
    var activetab = 1;
    Ext.getCmp('data[EduStudent][name]').focus();
    
    var EnrollmentWindow = new Ext.Window({
        title: '<?php __('Enrollment Form'); ?>',
        width: 730,
        height: 610,
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
                        Ext.getCmp('next').enable();
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
                        if(!EnrollmentForm.getForm().isValid()){
                            Ext.Msg.alert(
                                "<?php __('Ooops!'); ?>", 
                                "<?php __('Some of the items should not be left blank'); ?>"
                            );
                            return;
                        }
                        EnrollmentForm.getForm().submit({
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
                                            on_enrollment = true;
                                            EnrollmentWindow.close();
                                            var eduPayment_data = response.responseText;
                                            eval(eduPayment_data);
                                        },
                                        failure: function(response, opts) {
                                            Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Student monthly payment form. Error code'); ?>: " + response.status);
                                        }
                                    });
                                } else {
                                    Ext.MessageBox.confirm(
                                        'Confirm', 
                                        'Do you want to get print out?', 
                                        function(btn){
                                            if (btn === 'yes'){
                                                printReceipt();
                                                printEnrollmentCertificate();

                                                EnrollmentWindow.close();
                                                openEnrollment();
                                            } else {
                                                EnrollmentWindow.close();
                                                openEnrollment();
                                            }
                                        }
                                    );
                                }
                            },
                            failure: function(f, a) {
                                Ext.Msg.show({
                                    title: '<?php __('Oooops!'); ?>',
                                    buttons: Ext.MessageBox.OK,
                                    msg: a.result.errormsg,
                                    icon: Ext.MessageBox.ERROR
                                });
                            }
                        });
                    }
                    if (activetab == 3) {
                        Ext.getCmp('payment_tab').enable();
                        Ext.getCmp('student_condition_tab').disable();
                        Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('payment_tab'));
                        Ext.getCmp('next').setText('Finish');
                        activetab = 4;
                    }
                    if (activetab == 2) {
                        if(!parentsValidation()) {
                            ShowErrorBox('Parent information is not properly maintained.', 'ERR-102-03');
                        } else {
                            Ext.getCmp('student_condition_tab').enable();
                            Ext.getCmp('parent_tab').disable();
                            Ext.getCmp('enrollment_tabs').setActiveTab(Ext.getCmp('student_condition_tab'));
                            activetab = 3;
                        }
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
					Ext.MessageBox.confirm(
						'Confirm', 
						'Are you sure to cancel the enrollment?', 
						function(btn) {
							if (btn === 'yes'){
								EnrollmentWindow.close();
							} else {
								// do nothing
							}
						}
					);
                    
                }
            }]
    });

    EnrollmentWindow.show();
    
	
    var popUpWin_enrl=0;
    var popUpWin_enrl_cert=0;
	
    function popUpWindowEnrl(URLStr, left, top, width, height) {
        if(popUpWin_enrl){
            if(!popUpWin_enrl.closed) popUpWin_enrl.close();
        }
        popUpWin_enrl = open(URLStr, 'popUpWinEnrollment', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printReceipt() {
        var url = "<?php echo $this->Html->url(array('controller' => 'edu_receipts', 'action' => 'print_receipt', 'plugin' => 'edu')); ?>";
        popUpWindowEnrl(url, 150, 150, 700, 1000);
    }
    
    	
    function popUpWindowEnrlCert(URLStr, left, top, width, height) {
        if(popUpWin_enrl_cert){
            if(!popUpWin_enrl_cert.closed) popUpWin_enrl_cert.close();
        }
        popUpWin_enrl_cert = open(URLStr, 'popUpWinEnrollmentCertificate', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printEnrollmentCertificate() {
        var url = "<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'enrollment_certificate', 'plugin' => 'edu')); ?>";
        popUpWindowEnrlCert(url, 250, 250, 700, 1000);
    }
	
	function OpenPhoto() {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_photos', 'action' => 'index')); ?>',
			success: function(response, opts) {
				var photo_data = response.responseText;
				
				eval(photo_data);
				
				PhotoWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Photo Management form. Error code'); ?>: ' + response.status);
			}
		});
	}
	
	function OpenPreviousSchool() {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_previous_schools', 'action' => 'index')); ?>',
			success: function(response, opts) {
				var previous_school_data = response.responseText;
				
				eval(previous_school_data);
				
				PreviousSchoolWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Previous School Management form. Error code'); ?>: ' + response.status);
			}
		});
	}
	
	function OpenEmergencyContact() {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_emergency_contacts', 'action' => 'index')); ?>',
			success: function(response, opts) {
				var emergency_contact_data = response.responseText;
				
				eval(emergency_contact_data);
				
				EmergencyContactWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Emergency Contacts Management form. Error code'); ?>: ' + response.status);
			}
		});
	}
	
	function OpenSibling() {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_siblings', 'action' => 'index')); ?>',
			success: function(response, opts) {
				var sibling_data = response.responseText;
				
				eval(sibling_data);
				
				SiblingWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Emergency Contacts Management form. Error code'); ?>: ' + response.status);
			}
		});
	}
    
