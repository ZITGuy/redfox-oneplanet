                        {
                            xtype: 'fieldset',
                            title: 'Learning Condition',
                            collapsible: true,
                            items: [
                                <?php
                                    $this->ExtForm->create('EduStudentCondition');
                                    $options10 = array('anchor' => '90%', 'xtype' => 'checkbox', 'fieldLabel' => 'Normal');
                                    $this->ExtForm->input('normal_learing_condition', $options10);
                                ?>,
                                <?php
                                    $options11 = array('anchor' => '90%', 'fieldLabel' => 'Special Need');
                                    $this->ExtForm->input('special_learning_need', $options11);
                                ?>
                            ]
                        }, {
                            xtype: 'fieldset',
                            title: 'Health Condition',
                            collapsible: true,
                            items: [
                                <?php
                                    $options10 = array('anchor' => '90%', 'xtype' => 'checkbox', 'fieldLabel' => 'Normal');
                                    $this->ExtForm->input('normal_health_condition', $options10);
                                ?>,
                                <?php
                                    $options11 = array('anchor' => '90%', 'fieldLabel' => 'Treatment Type');
                                    $this->ExtForm->input('treatment_type', $options11);
                                ?>,
                                <?php
                                    $options12 = array('anchor' => '90%');
                                    $this->ExtForm->input('health_care_institute', $options12);
                                ?>,
                                <?php
                                    $options13 = array('anchor' => '60%', 'fieldLabel' => 'Name of Physician');
                                    $this->ExtForm->input('physician', $options13);
                                ?>,
                                <?php
                                    $options14 = array('anchor' => '60%', 'fieldLabel' => 'Alergy (If Any)', 'xtype' => 'textarea',
                                        'value' => 'Nothing');
                                    $this->ExtForm->input('alergy', $options14);
                                ?>
                            ]
                        }, {
                            xtype: 'fieldset',
                            title: 'Physical Condition',
                            collapsible: true,
                            items: [
                                <?php
                                    $options10 = array('anchor' => '90%', 'xtype' => 'checkbox', 'fieldLabel' => 'Normal');
                                    $this->ExtForm->input('normal_physical_condition', $options10);
                                ?>,
                                <?php
                                    $options11 = array('anchor' => '90%', 'fieldLabel' => 'Disabled');
                                    $this->ExtForm->input('physically_disabled', $options11);
                                ?>
                            ]
                        }