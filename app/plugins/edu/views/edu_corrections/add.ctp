//<script>
	<?php
		$this->ExtForm->create('EduCorrection');
		$this->ExtForm->defineFieldFunctions();
	?>

    var sel_edu_academic_year_id = '';
	var sel_edu_section_id = '';
    var sel_edu_course_id = '';
    var sel_edu_registration_id = '';
    var sel_edu_quarter_id = '';
	var current_value = 0;

	var store_sections = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data')); ?>'
		})
	});

	var store_courses = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data2')); ?>'
		})
	});

	var store_registrations = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data_combo')); ?>'
		})
	});

	var store_quarters = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'list_data_combo')); ?>'
		})
	});

	var store_assessments = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'list_data_combo')); ?>'
        })
    });

	var store_assessment_records = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'mark'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'list_data_combo')); ?>'
		})
	});

	var CorrectionAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('fieldLabel' => 'Academic Year');
				$options['items'] = $edu_academic_years;
				$options['listeners'] = "{
					scope: this,
					'select': function(combo, record, index){
						sel_edu_academic_year_id = combo.getValue();
						sel_edu_section_id = '';
						sel_edu_course_id = '';
						sel_edu_registration_id = '';
						sel_edu_quarter_id = '';

						var edu_section_id = Ext.getCmp('edu_section_id');
						edu_section_id.setValue('');
						edu_section_id.store.removeAll();

						var edu_course_id = Ext.getCmp('edu_course_id');
						edu_course_id.setValue('');
						edu_course_id.store.removeAll();

						var edu_quarter_id = Ext.getCmp('edu_quarter_id');
						edu_quarter_id.setValue('');
						edu_quarter_id.store.removeAll();

						var edu_assessment_id = Ext.getCmp('edu_assessment_id');
						edu_assessment_id.setValue('');
						edu_assessment_id.store.removeAll();

						var edu_registration_id = Ext.getCmp('edu_registration_id');
						edu_registration_id.setValue('');
						edu_registration_id.store.removeAll();

						Ext.getCmp('btnViewDetail').disable();
						Ext.getCmp('btnSubmitAllAssessment').disable();
					}
				}";
				$options['anchor'] = '75%';
				$this->ExtForm->input('edu_academic_year_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Class');
				$options['items'] = $edu_classes;
				$options['listeners'] = "{
					scope: this,
					'select': function(combo, record, index){
						var edu_section_id = Ext.getCmp('edu_section_id');
						edu_section_id.setValue('');
						edu_section_id.store.removeAll();
						edu_section_id.store.reload({
							params: {
								edu_class_id : combo.getValue(),
								edu_academic_year_id: sel_edu_academic_year_id,
								include_all: true
							}
						});
						var edu_course_id = Ext.getCmp('edu_course_id');
						edu_course_id.setValue('');
						edu_course_id.store.removeAll();
						edu_course_id.store.reload({
							params: {
								edu_class_id : combo.getValue()
							}
						});

                        sel_edu_course_id = '';
                        sel_edu_section_id = '';

						g.getStore().removeAll();

                        Ext.getCmp('btnViewDetail').disable();
                        Ext.getCmp('btnSubmitAllAssessment').disable();
					}
				}";
                $options['anchor'] = '75%';
				$this->ExtForm->input('edu_class_id', $options);
            ?>, {
                xtype: 'combo',
                name: 'edu_section_id',
                hiddenName: 'data[EduCorrection][edu_section_id]',
                id:'edu_section_id',
                typeAhead: true,
                store : store_sections,
                displayField : 'name',
                valueField : 'id',
                anchor:'75%',
                fieldLabel: '<span style="color:red;">*</span> Section',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Section',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        sel_edu_section_id = combo.getValue();

						var edu_quarter_id = Ext.getCmp('edu_quarter_id');
						edu_quarter_id.setValue('');
						edu_quarter_id.store.removeAll();
						edu_quarter_id.store.reload({
							params: {
								edu_section_id : sel_edu_section_id,
								quarter_type: 'E'
							}
						});
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_quarter_id',
                id:'edu_quarter_id',
                hiddenName: 'data[EduCorrection][edu_quarter_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: true,
                anchor: '75%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Term',
                store : store_quarters,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
						sel_edu_quarter_id = combo.getValue();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_course_id',
                id:'edu_course_id',
                hiddenName: 'data[EduCorrection][edu_course_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: true,
                anchor: '75%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Course',
                store : store_courses,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        var edu_assessment_id = Ext.getCmp('edu_assessment_id');

                        edu_assessment_id.setValue('');
                        edu_assessment_id.store.removeAll();
                        edu_assessment_id.store.reload({
                            params: {
                                edu_course_id : combo.getValue(),
                                edu_section_id: sel_edu_section_id,
								edu_quarter_id: sel_edu_quarter_id,
								include_all   : true
                            }
                        });
                        sel_edu_course_id = combo.getValue();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_assessment_id',
                id:'edu_assessment_id',
                hiddenName: 'data[EduCorrection][edu_assessment_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: true,
                anchor: '85%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Assessment',
                store : store_assessments,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        sel_edu_assessment_id = combo.getValue();

						var edu_registration_id = Ext.getCmp('edu_registration_id');
						edu_registration_id.setValue('');
						edu_registration_id.store.removeAll();
						edu_registration_id.store.reload({
							params: {
								edu_section_id : sel_edu_section_id,
								edu_assessment_id : sel_edu_assessment_id
							}
						});
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_registration_id',
                id:'edu_registration_id',
                hiddenName: 'data[EduCorrection][edu_registration_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: true,
                anchor: '75%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Student',
                store : store_registrations,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
						sel_edu_registration_id = combo.getValue();
						arrayOfStrings = sel_edu_registration_id.split('-');
						current_value = 0;

						if(arrayOfStrings.length == 2) {
							sel_edu_registration_id = arrayOfStrings[0];
							current_value = arrayOfStrings[1];

							var old_value = Ext.getCmp('data[EduCorrection][old_value]');
							old_value.setValue(current_value);
						}
                    }
                }
            },
			<?php
				$options = array('fieldLabel' => 'Current Value', 'readOnly' => 'true', 
					'anchor' => '45%', 'name' => 'data[EduCorrection][old_value]',
                	'id' => 'data[EduCorrection][old_value]');
				$this->ExtForm->input('old_value', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'New Value', 'anchor' => '45%');
				$this->ExtForm->input('new_value', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Reason');
				$this->ExtForm->input('reason', $options);
			?>
		]
	});
	
	var CorrectionAddWindow = new Ext.Window({
		title: '<?php __('Add Correction'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: CorrectionAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				CorrectionAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Correction.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(CorrectionAddWindow.collapsed)
					CorrectionAddWindow.expand(true);
				else
					CorrectionAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn) {
				
				CorrectionAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a) {
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						CorrectionAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentCorrectionData();
<?php } else { ?>
						RefreshCorrectionData();
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
			handler: function(btn) {

				CorrectionAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a) {
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						CorrectionAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentCorrectionData();
<?php } else { ?>
						RefreshCorrectionData();
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
				CorrectionAddWindow.close();
			}
		}]
	});
