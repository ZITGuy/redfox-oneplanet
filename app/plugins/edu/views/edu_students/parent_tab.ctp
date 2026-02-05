                    {
                            xtype: 'compositefield',
                            fieldLabel: 'Existing Parent?',
                            msgTarget: 'qtip',
                            anchor: '-20',
                            defaults: {
                                flex: 1
                            },
                            items: [
                                <?php
                                    $this->ExtForm->create('EduParent');
                                    $options051 = array(
                                        'fieldLabel' => 'Existing Parent?',
                                        'xtype' => 'checkbox');
                                    $options051['listeners'] = "{
                                        scope: this,
                                        'check': function(Checkbox , checked){
                                            if(checked==true){
                                                EnrollmentForm.findById('field[mother_info]').disable();
                                                EnrollmentForm.findById('field[father_info]').disable();
                                                EnrollmentForm.findById('field[parent_user_info]').disable();
                                            } else {
                                                EnrollmentForm.findById('field[mother_info]').enable().expand();
                                                EnrollmentForm.findById('field[father_info]').enable().expand();
                                                EnrollmentForm.findById('field[parent_user_info]').enable().expand();
                                            }
                                        }
                                    }";
                                    $this->ExtForm->input('existing_parent', $options051);
                                ?>, {
                                    xtype: 'combo',
                                    store: new Ext.data.ArrayStore({
                                        id: 0,
                                        fields: [
                                            'id',
                                            'name'
                                        ],
                                        data: [['0', 'New Parent'], <?php asort($edu_parents);  foreach ($edu_parents as $k => $v) { ?>['<?php echo $k; ?>', '<?php echo $v; ?>'], <?php } ?>]
                                    }),
                                    displayField: 'name',
                                    typeAhead: true,
                                    id: 'data[EduStudent][edu_parent_id]',
                                    name: 'data[EduStudent][edu_parent_id]',
                                    mode: 'local',
                                    triggerAction: 'all',
                                    emptyText: 'Select One...',
                                    selectOnFocus: true,
                                    valueField: 'id',
                                    value: 0,
                                    anchor: '100%',
                                    fieldLabel: '<span style="color:red">*</span>Parent',
                                    allowBlank: false,
                                    listeners: {
                                        scope: this,
                                        'select': function(combo, record, index){
                                            populateParentInfo(combo.getValue());
                                        }
                                    }
                                }
                            ]
                        }, <?php
                            $options051 = array(
                                'anchor' => '60%',
                                'fieldLabel' => 'Marital Status',
                                'xtype' => 'combo');
                            $options051['items'] = array(0 => 'Single', 1 => 'Married', 2 => 'Divorsed', 3 => 'Widowed', 4 => 'Separated');
                            $this->ExtForm->input('marital_status', $options051);
                        ?>, <?php
                            $options052 = array(
                                'anchor' => '60%',
                                'fieldLabel' => 'Primary Parent',
                                'xtype' => 'combo', 'value' => 'M');
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
                                'emptyText' => 'Authorized person to take student from school');
                            $this->ExtForm->input('authorized_person', $options0555);
                        ?>, {
                            xtype: 'fieldset', 
                            title: 'Detailed Family Information',
                            collapsible: false,
                            items: [{
                                xtype: 'tabpanel',
                                activeTab: 0,
                                height: 310,
                                id: 'family_tabs',
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
                                                    $options04 = array('anchor' => '90%', 'emptyText' => 'First Name', 'id' => 'motherName1');
                                                    $this->ExtForm->input('mother_name1', $options04);
                                                ?>,
                                                <?php
                                                    $options05 = array('anchor' => '90%', 'emptyText' => 'Middle Name', 'id' => 'motherName2');
                                                    $this->ExtForm->input('mother_name2', $options05);
                                                ?>,
                                                <?php
                                                    $options06 = array('anchor' => '90%', 'emptyText' => 'Last Name', 'id' => 'motherName3');
                                                    $this->ExtForm->input('mother_name3', $options06);
                                                ?>
                                            ]
                                        },
                                        <?php
                                            $options0554 = array('anchor' => '90%', 'fieldLabel' => 'Residence Address');
                                            $this->ExtForm->input('mother_residence_address', $options0554);
                                        ?>,
                                        <?php
                                            $options0557 = array('anchor' => '70%', 'xtype' => 'combo', 'items' => $nationalities, 'fieldLabel' => 'Nationality', 'value' => 'Ethiopian');
                                            $this->ExtForm->input('mother_nationality', $options0557);
                                        ?>, 
                                        <?php
                                            $options0556 = array(
                                                'anchor' => '90%',
                                                'fieldLabel' => 'Occupation');
                                            $this->ExtForm->input('mother_occupation', $options0556);
                                        ?>,
                                        <?php
                                            $options0558 = array('anchor' => '70%', 
                                                'fieldLabel' => 'Academic Qualification', 
                                                'xtype' => 'combo', 
                                                'items' => array('Diploma' => 'Diploma', 'BA' => 'BA', 'BSc' => 'BSc', 'MA' => 'MA', 'MSc' => 'MSc', 'PhD' => 'PhD', 'Other' => 'Other')
                                            );
                                            $this->ExtForm->input('mother_academic_qualification', $options0558);
                                        ?>,
                                        <?php
                                            $options0559 = array('anchor' => '70%', 'xtype' => 'combo', 
                                                'items' => array('Employed' => 'Employed', 'Self Employed' => 'Self Employed'), 
                                                'fieldLabel' => 'Employment', 'value' => 'Employed');
                                            $this->ExtForm->input('mother_employment', $options0559);
                                        ?>,
                                        <?php
                                            $options0560 = array('anchor' => '90%', 'fieldLabel' => 'Employment Organization');
                                            $this->ExtForm->input('mother_employment_organization', $options0560);
                                        ?>, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Address',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $options04 = array('anchor' => '90%', 'emptyText' => 'Mobile', 'id' => 'motherMobile');
                                                    $this->ExtForm->input('mother_mobile', $options04);
                                                ?>,
                                                <?php
                                                    $options05 = array('anchor' => '90%', 'emptyText' => 'Work Address', 'id' => 'motherWorkAddress');
                                                    $this->ExtForm->input('mother_work_address', $options05);
                                                ?>,
                                                <?php
                                                    $options06 = array('anchor' => '90%', 'emptyText' => 'Work Telephone', 'id' => 'motherWorkTelephone');
                                                    $this->ExtForm->input('mother_work_telephone', $options06);
                                                ?>
                                            ]
                                        }, <?php
                                            $optionsme05 = array('anchor' => '90%', 'emptyText' => 'Email', 'id' => 'motherEmail', 'vtype' => 'email');
                                            $this->ExtForm->input('mother_email', $optionsme05);
                                        ?>,
                                        <?php
                                            $optionsf = array(
                                                'anchor' => '90%',
                                                'id' => 'data[EduParent][mother_photo_file_name]',
                                                'xtype' => 'fileuploadfield',
                                                'fieldLabel' => 'Mother Photo',
                                                'buttonText' => '',
                                                'emptyText' => 'Select a Photo',
                                                'buttonCfg' => "{
                                                    iconCls: 'upload-icon'
                                                }"
                                            );
                                            $this->ExtForm->input('mother_photo_file_name', $optionsf);
                                        ?>]
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
                                                        $optionsf04 = array('anchor' => '90%', 'emptyText' => 'First Name', 'id' => 'fatherName1');
                                                        $this->ExtForm->input('father_name1', $optionsf04);
                                                ?>,
                                                <?php
                                                        $optionsf05 = array('anchor' => '90%', 'emptyText' => 'Middle Name', 'id' => 'fatherName2');
                                                        $this->ExtForm->input('father_name2', $optionsf05);
                                                ?>,
                                                <?php
                                                        $optionsf06 = array('anchor' => '90%', 'emptyText' => 'Last Name', 'id' => 'fatherName3');
                                                        $this->ExtForm->input('father_name3', $optionsf06);
                                                ?>
                                            ]
                                        },
                                        <?php
                                            $optionsf0554 = array('anchor' => '90%', 'fieldLabel' => 'Residence Address');
                                            $this->ExtForm->input('father_residence_address', $optionsf0554);
                                        ?>,
                                        <?php
                                            $optionsf0557 = array('anchor' => '70%', 'xtype' => 'combo', 'items' => $nationalities, 'fieldLabel' => 'Nationality', 'value' => 'Ethiopian');
                                            $this->ExtForm->input('father_nationality', $optionsf0557);
                                        ?>, 
                                        <?php
                                            $optionsf0556 = array(
                                                'anchor' => '90%',
                                                'fieldLabel' => 'Occupation');
                                            $this->ExtForm->input('father_occupation', $optionsf0556);
                                        ?>,
                                        <?php
                                            $optionsf0558 = array('anchor' => '70%', 
                                                'fieldLabel' => 'Academic Qualification', 
                                                'xtype' => 'combo', 
                                                'items' => array('Diploma' => 'Diploma', 'BA' => 'BA', 'BSc' => 'BSc', 'MA' => 'MA', 'MSc' => 'MSc', 'PhD' => 'PhD', 'Other' => 'Other')
                                            );
                                            $this->ExtForm->input('mother_academic_qualification', $optionsf0558);
                                        ?>,
                                        <?php
                                            $optionsf0559 = array('anchor' => '70%', 'xtype' => 'combo', 
                                                'items' => array('Employed' => 'Employed', 'Self Employed' => 'Self Employed'), 
                                                'fieldLabel' => 'Employment', 'value' => 'Employed');
                                            $this->ExtForm->input('father_employment', $optionsf0559);
                                        ?>,
                                        <?php
                                            $optionsf0560 = array('anchor' => '90%', 'fieldLabel' => 'Employment Organization');
                                            $this->ExtForm->input('father_employment_organization', $optionsf0560);
                                        ?>, {
                                            xtype: 'compositefield',
                                            fieldLabel: 'Address',
                                            msgTarget: 'qtip',
                                            anchor: '-20',
                                            defaults: {
                                                flex: 1
                                            },
                                            items: [
                                                <?php
                                                    $optionsf04 = array('anchor' => '90%', 'emptyText' => 'Mobile', 'id' => 'fatherMobile');
                                                    $this->ExtForm->input('father_telephone', $optionsf04);
                                                ?>,
                                                <?php
                                                    $optionsf05 = array('anchor' => '90%', 'emptyText' => 'Work Address', 'id' => 'fatherWorkAddress');
                                                    $this->ExtForm->input('father_work_address', $optionsf05);
                                                ?>,
                                                <?php
                                                    $optionsf06 = array('anchor' => '90%', 'emptyText' => 'Work Telephone', 'id' => 'fatherWorkTelephone');
                                                    $this->ExtForm->input('father_work_telephone', $optionsf06);
                                                ?>
                                            ]
                                        }, <?php
                                            $optionsfe05 = array('anchor' => '90%', 'emptyText' => 'Email', 'id' => 'fatherEmail', 'vtype' => 'email');
                                            $this->ExtForm->input('father_email', $optionsfe05);
                                        ?>,
                                        <?php
                                            $optionsff = array(
                                                'anchor' => '90%',
                                                'id' => 'data[EduParent][father_photo_file_name]',
                                                'xtype' => 'fileuploadfield',
                                                'fieldLabel' => 'Father Photo',
                                                'buttonText' => '',
                                                'emptyText' => 'Select a Photo',
                                                'buttonCfg' => "{
                                                    iconCls: 'upload-icon'
                                                }"
                                            );
                                            $this->ExtForm->input('father_photo_file_name', $optionsff);
                                        ?>]
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
                                                        $optionsg04 = array('anchor' => '90%', 'emptyText' => 'First Name', 'id' => 'guardianName1');
                                                        $this->ExtForm->input('guardian_name1', $optionsg04);
                                                ?>,
                                                <?php
                                                        $optionsg05 = array('anchor' => '90%', 'emptyText' => 'Middle Name', 'id' => 'guardianName2');
                                                        $this->ExtForm->input('guardian_name2', $optionsg05);
                                                ?>,
                                                <?php
                                                        $optionsg06 = array('anchor' => '90%', 'emptyText' => 'Last Name', 'id' => 'guardianName3');
                                                        $this->ExtForm->input('guardian_name3', $optionsg06);
                                                ?>
                                            ]
                                        },
                                        <?php
                                            $optionsg0554 = array('anchor' => '90%', 'fieldLabel' => 'Residence Address');
                                            $this->ExtForm->input('guardian_residence_address', $optionsg0554);
                                        ?>,
                                        <?php
                                            $optionsg0557 = array('anchor' => '70%', 'xtype' => 'combo', 'items' => $nationalities, 'fieldLabel' => 'Nationality', 'value' => 'Ethiopian');
                                            $this->ExtForm->input('guardian_nationality', $optionsg0557);
                                        ?>,
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
                                                        'items' => array('Aunt' => 'Aunt', 'Uncle' => 'Uncle', 'Cousin' => 'Cousin', 'Grand mother' => 'Grand mother',
                                                                'Sister' => 'Sister', 'Brother' => 'Brother', 'Other' => 'Other'
                                                            ),
                                                        'fieldLabel' => 'Relationship'
                                                    );
                                                    $this->ExtForm->input('guardian_relationship', $optionsg0555);
                                                ?>, <?php
                                                    $optionsg05551 = array(
                                                        'anchor' => '95%',
                                                        'fieldLabel' => 'Other', 
                                                        'emptyText' => 'Write here if any other relation');
                                                    $this->ExtForm->input('guardian_relationship_other', $optionsg05551);
                                                ?>
                                            ]},
                                        <?php
                                            $optionsg0556 = array(
                                                'anchor' => '90%',
                                                'fieldLabel' => 'Occupation');
                                            $this->ExtForm->input('guardian_occupation', $optionsg0556);
                                        ?>,
                                        <?php
                                            $optionsg0558 = array('anchor' => '70%', 
                                                'fieldLabel' => 'Academic Qualification', 
                                                'xtype' => 'combo', 
                                                'items' => array('Diploma' => 'Diploma', 'BA' => 'BA', 'BSc' => 'BSc', 'MA' => 'MA', 'MSc' => 'MSc', 'PhD' => 'PhD', 'Other' => 'Other')
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
                                                        'items' => array('Employed' => 'Employed', 'Self Employed' => 'Self Employed'), 
                                                        'fieldLabel' => 'Employment', 'value' => 'Employed');
                                                    $this->ExtForm->input('guardian_employment', $optionsg0559);
                                                ?>,
                                                <?php
                                                    $optionsg0560 = array('anchor' => '90%', 'emptyText' => 'Employment Organization');
                                                    $this->ExtForm->input('guardian_employment_organization', $optionsg0560);
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
                                                    $optionsg004 = array('anchor' => '90%', 'emptyText' => 'Mobile', 'id' => 'guardianMobile');
                                                    $this->ExtForm->input('guardian_telephone', $optionsg004);
                                                ?>,
                                                <?php
                                                    $optionsg005 = array('anchor' => '90%', 'emptyText' => 'Work Address', 'id' => 'guardianWorkAddress');
                                                    $this->ExtForm->input('guardian_work_address', $optionsg005);
                                                ?>,
                                                <?php
                                                    $optionsg006 = array('anchor' => '90%', 'emptyText' => 'Work Telephone', 'id' => 'guardianWorkTelephone');
                                                    $this->ExtForm->input('guardian_work_telephone', $optionsg006);
                                                ?>
                                            ]
                                        }, <?php
                                            $optionsge05 = array('anchor' => '90%', 'emptyText' => 'Email', 'id' => 'guardianEmail', 'vtype' => 'email');
                                            $this->ExtForm->input('guardian_email', $optionsge05);
                                        ?>,
                                        <?php
                                            $optionsfg = array(
                                                'anchor' => '90%',
                                                'id' => 'data[EduParent][guardian_photo_file_name]',
                                                'xtype' => 'fileuploadfield',
                                                'fieldLabel' => 'Guardian Photo',
                                                'buttonText' => '',
                                                'emptyText' => 'Select a Photo',
                                                'buttonCfg' => "{
                                                    iconCls: 'upload-icon'
                                                }"
                                            );
                                            $this->ExtForm->input('guardian_photo_file_name', $optionsfg);
                                        ?>]
                                }, {
                                    title: 'Siblings',
                                    layout: 'form',
                                    defaultType: 'textfield',
                                    id: 'brothers_tab',
                                    items: [
                                        {
                                            xtype: 'fieldset',
                                            title: 'Siblings',
                                            collapsible: false,
                                            items: [{
                                                    layout: 'column',
                                                    labelWidth: 45,
                                                    items: [{
                                                            columnWidth: .55,
                                                            layout: 'form',
                                                            items: [
                                                                <?php
                                                                    $this->ExtForm->create('EduSibling');
                                                                    $optionsb61 = array('anchor' => '99%', 'fieldLabel' => 'Name');
                                                                    $this->ExtForm->input('brother_name1', $optionsb61);
                                                                ?>,
                                                                <?php
                                                                        $optionsb62 = array('anchor' => '99%', 'fieldLabel' => 'Name');
                                                                        $this->ExtForm->input('brother_name2', $optionsb62);
                                                                ?>,
                                                                <?php
                                                                        $optionsb63 = array('anchor' => '99%', 'fieldLabel' => 'Name');
                                                                        $this->ExtForm->input('brother_name3', $optionsb63);
                                                                ?>,
                                                                <?php
                                                                        $optionsb64 = array('anchor' => '99%', 'fieldLabel' => 'Name');
                                                                        $this->ExtForm->input('brother_name4', $optionsb64);
                                                                ?>,
                                                                <?php
                                                                        $optionsb65 = array('anchor' => '99%', 'fieldLabel' => 'Name');
                                                                        $this->ExtForm->input('brother_name5', $optionsb65);
                                                                ?>,
                                                                <?php
                                                                        $optionsb66 = array('anchor' => '99%', 'fieldLabel' => 'Name');
                                                                        $this->ExtForm->input('brother_name6', $optionsb66);
                                                                ?>
                                                            ]
                                                        }, {
                                                            columnWidth: .15,
                                                            layout: 'form',
                                                            items: [
                                                                <?php
                                                                        $optionsb71 = array('anchor' => '99%', 'fieldLabel' => 'Age');
                                                                        $this->ExtForm->input('brother_age1', $optionsb71);
                                                                ?>,
                                                                <?php
                                                                        $optionsb72 = array('anchor' => '99%', 'fieldLabel' => 'Age');
                                                                        $this->ExtForm->input('brother_age2', $optionsb72);
                                                                ?>,
                                                                <?php
                                                                        $optionsb73 = array('anchor' => '99%', 'fieldLabel' => 'Age');
                                                                        $this->ExtForm->input('brother_age3', $optionsb73);
                                                                ?>,
                                                                <?php
                                                                        $optionsb74 = array('anchor' => '99%', 'fieldLabel' => 'Age');
                                                                        $this->ExtForm->input('brother_age4', $optionsb74);
                                                                ?>,
                                                                <?php
                                                                        $optionsb75 = array('anchor' => '99%', 'fieldLabel' => 'Age');
                                                                        $this->ExtForm->input('brother_age5', $optionsb75);
                                                                ?>,
                                                                <?php
                                                                        $optionsb76 = array('anchor' => '99%', 'fieldLabel' => 'Age');
                                                                        $this->ExtForm->input('brother_age6', $optionsb76);
                                                                ?>
                                                            ]
                                                        }, {
                                                            columnWidth: .15,
                                                            layout: 'form',
                                                            items: [
                                                                <?php
                                                                        $optionsb71 = array('anchor' => '99%', 'fieldLabel' => 'Grade');
                                                                        $this->ExtForm->input('brother_grade1', $optionsb71);
                                                                ?>,
                                                                <?php
                                                                        $optionsb72 = array('anchor' => '99%', 'fieldLabel' => 'Grade');
                                                                        $this->ExtForm->input('brother_grade2', $optionsb72);
                                                                ?>,
                                                                <?php
                                                                        $optionsb73 = array('anchor' => '99%', 'fieldLabel' => 'Grade');
                                                                        $this->ExtForm->input('brother_grade3', $optionsb73);
                                                                ?>,
                                                                <?php
                                                                        $optionsb74 = array('anchor' => '99%', 'fieldLabel' => 'Grade');
                                                                        $this->ExtForm->input('brother_grade4', $optionsb74);
                                                                ?>,
                                                                <?php
                                                                        $optionsb75 = array('anchor' => '99%', 'fieldLabel' => 'Grade');
                                                                        $this->ExtForm->input('brother_grade5', $optionsb75);
                                                                ?>,
                                                                <?php
                                                                        $optionsb76 = array('anchor' => '99%', 'fieldLabel' => 'Grade');
                                                                        $this->ExtForm->input('brother_grade6', $optionsb76);
                                                                ?>
                                                            ]
                                                        }, {
                                                            columnWidth: .15,
                                                            layout: 'form',
                                                            items: [
                                                                <?php
                                                                        $sex = array('M' => 'M', 'F' => 'F');
                                                                        $optionsb81 = array('anchor' => '99%', 'value' => 'F', 'allowBlank' => true, 'xtype' => 'combo', 'items' => $sex, 'fieldLabel' => 'Sex');
                                                                        $this->ExtForm->input('brother_sex1', $optionsb81);
                                                                ?>,
                                                                <?php
                                                                        $optionsb82 = array('anchor' => '99%', 'value' => 'F', 'allowBlank' => true, 'xtype' => 'combo', 'items' => $sex, 'fieldLabel' => 'Sex');
                                                                        $this->ExtForm->input('brother_sex2', $optionsb82);
                                                                ?>,
                                                                <?php
                                                                        $optionsb83 = array('anchor' => '99%', 'value' => 'F', 'allowBlank' => true, 'xtype' => 'combo', 'items' => $sex, 'fieldLabel' => 'Sex');
                                                                        $this->ExtForm->input('brother_sex3', $optionsb83);
                                                                ?>,
                                                                <?php
                                                                        $optionsb84 = array('anchor' => '99%', 'value' => 'F', 'allowBlank' => true, 'xtype' => 'combo', 'items' => $sex, 'fieldLabel' => 'Sex');
                                                                        $this->ExtForm->input('brother_sex4', $optionsb84);
                                                                ?>,
                                                                <?php
                                                                        $optionsb85 = array('anchor' => '99%', 'value' => 'F', 'allowBlank' => true, 'xtype' => 'combo', 'items' => $sex, 'fieldLabel' => 'Sex');
                                                                        $this->ExtForm->input('brother_sex5', $optionsb85);
                                                                ?>,
                                                                <?php
                                                                        $optionsb86 = array('anchor' => '99%', 'value' => 'F', 'allowBlank' => true, 'xtype' => 'combo', 'items' => $sex, 'fieldLabel' => 'Sex');
                                                                        $this->ExtForm->input('brother_sex6', $optionsb86);
                                                                ?>
                                                            ]
                                                        }]
                                                }]
                                        }
                                    ]
                                }, {
                                    title: 'User Info',
                                    layout: 'form',
                                    defaultType: 'textfield',
                                    id: 'user_info_tab',
                                    items: [{
                                            xtype: 'fieldset',
                                            title: 'User Credentials for the specified parent',
                                            collapsible: false,
                                            items: [
                                                <?php
                                                    $this->ExtForm->create('UserParent');
                                                    $options00 = array('anchor' => '70%',
                                                        'fieldLabel' => 'Username',
                                                        'id' => 'parentUserName'
                                                    );
                                                    $this->ExtForm->input('username', $options00);
                                                ?>,
                                                <?php
                                                    $options01 = array('anchor' => '70%',
                                                        'id' => 'parentPassword',
                                                        'fieldLabel' => 'Password');
                                                    $this->ExtForm->input('password', $options01);
                                                ?>,
                                                <?php
                                                    $options02 = array('anchor' => '70%', 'fieldLabel' => 'Email', 'vtype' => 'email');
                                                    $this->ExtForm->input('email', $options02);
                                                ?>
                                            ]
                                    }, {
                                            xtype: 'fieldset',
                                            title: 'Subscriptions',
                                            collapsible: false,
                                            items: [
                                                new Ext.form.CheckboxGroup({
                                                    id:'attendanceGroup',
                                                    xtype: 'checkboxgroup',
                                                    fieldLabel: 'Attendance',
                                                    itemCls: 'x-check-group-alt',
                                                    columns: 3,
                                                    items: [
                                                        { 
                                                            boxLabel: '<?php __('SMS'); ?>', 
                                                            name: '<?php echo "data[User][sms_attendance]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('E-mail'); ?>', 
                                                            name: '<?php echo "data[User][email_attendance]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('Portal'); ?>', 
                                                            name: '<?php echo "data[User][portal_attendance]"; ?>'
                                                        }
                                                    ]
                                                }), new Ext.form.CheckboxGroup({
                                                    id:'studentResultGroup',
                                                    xtype: 'checkboxgroup',
                                                    fieldLabel: 'Student Result',
                                                    itemCls: 'x-check-group-alt',
                                                    columns: 3,
                                                    items: [
                                                        { 
                                                            boxLabel: '<?php __('SMS'); ?>', 
                                                            name: '<?php echo "data[User][sms_student_result]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('E-mail'); ?>', 
                                                            name: '<?php echo "data[User][email_student_result]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('Portal'); ?>', 
                                                            name: '<?php echo "data[User][portal_student_result]"; ?>'
                                                        }
                                                    ]
                                                }), new Ext.form.CheckboxGroup({
                                                    id:'paymentsResultGroup',
                                                    xtype: 'checkboxgroup',
                                                    fieldLabel: 'Notify Payment by',
                                                    itemCls: 'x-check-group-alt',
                                                    columns: 3,
                                                    items: [
                                                        { 
                                                            boxLabel: '<?php __('SMS'); ?>', 
                                                            name: '<?php echo "data[User][sms_payments]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('E-mail'); ?>', 
                                                            name: '<?php echo "data[User][email_payments]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('Portal'); ?>', 
                                                            name: '<?php echo "data[User][portal_payments]"; ?>'
                                                        }
                                                    ]
                                                }), new Ext.form.CheckboxGroup({
                                                    id:'announcementsResultGroup',
                                                    xtype: 'checkboxgroup',
                                                    fieldLabel: 'Announcements by',
                                                    itemCls: 'x-check-group-alt',
                                                    columns: 3,
                                                    items: [
                                                        { 
                                                            boxLabel: '<?php __('SMS'); ?>', 
                                                            name: '<?php echo "data[User][sms_announcements]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('E-mail'); ?>', 
                                                            name: '<?php echo "data[User][email_announcements]"; ?>'
                                                        }, { 
                                                            boxLabel: '<?php __('Portal'); ?>', 
                                                            name: '<?php echo "data[User][portal_announcements]"; ?>'
                                                        }
                                                    ]
                                                })
                                            ]
                                    }]
                                }]
                            }]
                        }
