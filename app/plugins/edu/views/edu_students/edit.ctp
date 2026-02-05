//<script>
    <?php
        $this->ExtForm->create('EduStudent');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var EduStudentEditForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        fileUpload: true,
        labelWidth: 150,
        labelAlign: 'right',
        url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'edit')); ?>',
        defaultType: 'textfield',
        items: [
            <?php $this->ExtForm->input('id', array('hidden' => $edu_student['EduStudent']['id'])); ?>,
            <?php 
                $this->ExtForm->create('EduStudent');
                $options = array('anchor' => '60%', 'xtype' => 'textfield', 'fieldLabel' => 'Student ID', 'disabled' => true);
                $options['value'] = $edu_student['EduStudent']['identity_number'];
                $this->ExtForm->input('theidentity_number', $options);
            ?>,
            <?php 
                $this->ExtForm->create('EduStudent');
                $options0 = array('anchor' => '60%', 'fieldLabel' => 'Student Full Name');
                $options0['value'] = strtoupper($edu_student['EduStudent']['name']);
                $this->ExtForm->input('name', $options0);
            ?>,      
            <?php 
                $options2 = array(
                    'anchor' => '50%', 
                    'fieldLabel' => 'Date of Birth',
                    'value' => date('Y-m-d',  strtotime($edu_student['EduStudent']['birth_date'])),
                    'maxValue' => "'" . date('Y-m-d',  strtotime('-3 years')) . "'");
                $this->ExtForm->input('birth_date', $options2);
            ?>,
            <?php 
                $options4 = array('anchor' => '50%', 'fieldLabel' => 'Country of Birth');
                $options4['value'] = $edu_student['EduStudent']['birth_country'];
                $this->ExtForm->input('birth_country', $options4);
            ?>,
            <?php
                $options3 = array('anchor' => '50%', 'fieldLabel' => 'Gender', 'xtype'=>'combo');
                $options3['items'] = array('F' => 'Female', 'M' => 'Male');
                $options3['value'] = $edu_student['EduStudent']['gender'];
                $this->ExtForm->input('gender', $options3);
            ?>,
            <?php
                $options4 = array('anchor' => '50%');
                $options4['value'] = $edu_student['EduStudent']['nationality'];
                $this->ExtForm->input('nationality', $options4);
            ?>,
            <?php
                $options4 = array('anchor' => '50%', 'fieldLabel' => 'Language at Home');
                $options4['value'] = $edu_student['EduStudent']['home_language'];
                $this->ExtForm->input('home_language', $options4);
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
										$options51 = array('anchor' => '95%', 'xtype' => 'combo', 
											'items' => $sub_cities, 'fieldLabel'=>'Sub City');
										$options51['value'] = $edu_student['EduStudent']['sub_city_id'];
										$this->ExtForm->input('sub_city_id', $options51);
									?>
								]
							}, {
								columnWidth: .33,
								layout: 'form',
								items: [
									<?php
										$options52 = array('anchor' => '95%');
										$options52['value'] = $edu_student['EduStudent']['woreda'];
										$this->ExtForm->input('woreda', $options52);
									?>
								]
							}, {
								columnWidth: .34,
								layout: 'form',
								items: [
									<?php
										$options53 = array('anchor' => '95%');
										$options53['value'] = $edu_student['EduStudent']['house_number'];
										$this->ExtForm->input('house_number', $options53);
									?>
								]
							}]
					}
				]
			}, {
                xtype: 'fieldset',
                title: 'Medical Info and Class Change',
                collapsible: true,
                items: [
					<?php
						$this->ExtForm->create('EduStudentCondition');
						$options60 = array('anchor' => '95%');
						$options60['value'] = isset($edu_student['EduStudentCondition'][0]['learning_condition'])? $edu_student['EduStudentCondition'][0]['learning_condition']: 'N/A';
						$this->ExtForm->input('learning_condition', $options60);
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
					}),
					<?php 
						$options61 = array('anchor' => '95%');
						$options61['value'] = isset($edu_student['EduStudentCondition'][0]['health_condition'])? $edu_student['EduStudentCondition'][0]['health_condition']: 'N/A';
						$this->ExtForm->input('health_condition', $options61);
					?>,
					<?php 
						$options61 = array('anchor' => '95%');
						$options61['value'] = isset($edu_student['EduStudentCondition'][0]['treatment_type'])? $edu_student['EduStudentCondition'][0]['treatment_type']: 'N/A';
						$this->ExtForm->input('treatment_type', $options61);
					?>,
					<?php 
						$options62 = array('anchor' => '95%');
						$options62['value'] = isset($edu_student['EduStudentCondition'][0]['health_care_institute'])? $edu_student['EduStudentCondition'][0]['health_care_institute']: 'N/A';
						$this->ExtForm->input('health_care_institute', $options62);
					?>,
					<?php 
						$options63 = array('anchor' => '95%', 'fieldLabel' => 'Physician');
						$options63['value'] = isset($edu_student['EduStudentCondition'][0]['physician'])? $edu_student['EduStudentCondition'][0]['physician']: 'N/A';
						$this->ExtForm->input('physician', $options63);
					?>,
					<?php 
						$options64 = array('anchor' => '95%', 'fieldLabel' => 'Alergy (If Any)');
						$options64['value'] = isset($edu_student['EduStudentCondition'][0]['alergy'])? $edu_student['EduStudentCondition'][0]['alergy']: 'N/A';
						$this->ExtForm->input('alergy', $options64);
					?>,
					<?php 
						$options65 = array('anchor' => '95%', 'fieldLabel' => 'Physical Condition');
						$options65['value'] = isset($edu_student['EduStudentCondition'][0]['physical_condition'])? $edu_student['EduStudentCondition'][0]['physical_condition']: 'N/A';
						$this->ExtForm->input('physical_condition', $options65);
					?>                             
                ]
            }
        ]
    });

    var EduStudentEditWindow = new Ext.Window({
        title: '<?php __('Update Student Information'); ?>',
        width: 700,
        minWidth: 600,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduStudentEditForm,
        tools: [{
					id: 'refresh',
					qtip: 'Reset',
					handler: function() {
						EduStudentEditForm.getForm().reset();
					},
					scope: this
				}, {
					id: 'help',
					qtip: 'Help',
					handler: function() {
						Ext.Msg.show({
							title: 'Help',
							buttons: Ext.MessageBox.OK,
							msg: 'This form is used to modify an existing Edu Student.',
							icon: Ext.MessageBox.INFO
						});
					}
				}, {
					id: 'toggle',
					qtip: 'Collapse / Expand',
					handler: function() {
						if (EduStudentEditWindow.collapsed)
							EduStudentEditWindow.expand(true);
						else
							EduStudentEditWindow.collapse(true);
					}
				}],
			buttons: [{
                text: '<?php __('Save'); ?>',
                handler: function(btn) {
                    EduStudentEditForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduStudentEditWindow.close();
                            RefreshEduStudentData();
                        },
                        failure: function(f, a) {
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
                handler: function(btn) {
                    EduStudentEditWindow.close();
                }
            }]
    });
