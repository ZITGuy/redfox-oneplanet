//<script>
    var sel_edu_assessment_id = '';
    var sel_edu_section_id = '';
    var sel_edu_course_id = '';

    var store_assessment_records = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'student', 'identity_number', {name: 'rvalue', type: 'decimal'}, 'max_value'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'list_data_records')); ?>'
		})
    });

    function RefreshEduAssessmentRecordsData() {
        if(sel_edu_assessment_id!=''){
            store_assessment_records.reload({
				params: {
                    start: 0,
                    edu_assessment_id: sel_edu_assessment_id,
				}
            });
		}
    }

    <?php
        $this->ExtForm->create('EduAssessment');
        $this->ExtForm->defineFieldFunctions();
    ?>
	var store_sections = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data_for_teacher')); ?>'
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
			url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data_for_teacher')); ?>'
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
            url: '<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'list_data2')); ?>'
        })
    });

    function viewAssessmentRecordsDetail(){
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'view_detail')); ?>/'+
                sel_edu_section_id+'/'+sel_edu_course_id,
            success: function(response, opts) {
                var assessment_data = response.responseText;

                eval(assessment_data);

                AssessmentRecordDetailViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the assessment view form. Error code'); ?>: ' + response.status);
            }
        });
    }

	var EduAssessmentRecordingTopForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'',
		//defaultType: 'textfield',

		items: [
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
                $options['anchor'] = '45%';
				$this->ExtForm->input('edu_class_id', $options);
            ?>, {
                xtype: 'combo',
                emptyText: 'All',
                name: 'edu_section_id',
                hiddenName: 'data[Assessment][edu_section_id]',
                id:'edu_section_id',
                typeAhead: true,
                store : store_sections,
                displayField : 'name',
                valueField : 'id',
                anchor:'45%',
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
						
						var edu_course_id = Ext.getCmp('edu_course_id');
						edu_course_id.setValue('');
						edu_course_id.store.removeAll();
						edu_course_id.store.reload({
							params: {
								edu_section_id : combo.getValue()
							}
						});
						
                        Ext.getCmp('btnViewDetail').disable();
                        Ext.getCmp('btnSubmitAllAssessment').disable();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_course_id',
                id:'edu_course_id',
                hiddenName: 'data[Assessment][edu_course_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: true,
                anchor: '45%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Course',
                store : store_courses,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        var edu_section_id = Ext.getCmp('edu_section_id');
                        var edu_assessment_id = Ext.getCmp('edu_assessment_id');
                        edu_assessment_id.setValue('');
                        edu_assessment_id.store.removeAll();
                        edu_assessment_id.store.reload({
                            params: {
                                edu_course_id : combo.getValue(),
                                edu_section_id: edu_section_id.getValue()
                            }
                        });

                        sel_edu_course_id = combo.getValue();

                        Ext.getCmp('btnViewDetail').enable();
                        Ext.getCmp('btnSubmitAllAssessment').enable();
                        Ext.getCmp('btnGenerateExcel').enable();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_assessment_id',
                id:'edu_assessment_id',
                hiddenName: 'data[Assessment][edu_assessment_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: true,
                anchor: '55%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Assessment',
                store : store_assessments,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        sel_edu_assessment_id = combo.getValue();
                        RefreshEduAssessmentRecordsData();
                    }
                }
            }
        ]
    });

    var fm = Ext.form;
    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [{
                header: 'Student',
                dataIndex: 'student',
                width: 150,
                sortable: false
            }, {
                header: 'Student ID',
                dataIndex: 'identity_number',
                width: 150,
                sortable: false
            }, {
                header: 'Value',
                dataIndex: 'rvalue',
                width: 150,
                sortable: false,
                align: 'right',
                editor: new Ext.ux.form.SpinnerField({
                    name: 'MarkValue',
                    minValue: 0,
                    maxValue: 100,
                    allowDecimals: true,
                    decimalPrecision: 1,
                    incrementValue: 0.4,
                    alternateIncrementValue: 2.1,
                    accelerate: true
                })
            }
        ]
    });
	
    var g = new Ext.grid.EditorGridPanel({
        title: '<?php __('Assessments'); ?>',
        cm: cm,
        store: store_assessment_records,
        loadMask: true,
        stripeRows: true,
        clicksToEdit: 1,
        height: 272,
        anchor: '100%',
        id: 'assessmentGrid',
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        }/*,
        listeners: {
            cellclick: function(grid, rowIndex, columnIndex, e){
                if(columnIndex == 2){
                    var colm = grid.getColumnModel();
                    var records = store_assessment_records.getRange();
                    var the_spinner = new Ext.ux.form.SpinnerField({
                        name: 'MarkValue',
                        minValue: 0,
                        maxValue: records[rowIndex].get('max_value'),
                        allowDecimals: true,
                        decimalPrecision: 1,
                        incrementValue: 0.5,
                        alternateIncrementValue: 2.5,
                        accelerate: true
                    });
                    colm.setEditor(columnIndex, the_spinner);
                }
            }
        }*/
    });
    
    g.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
        }
    }
    
    var EduAssessmentRecordingWindow = new Ext.Window({
        title: 'Assessment Management',
        width: 700,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: [
            EduAssessmentRecordingTopForm, g
        ],

        buttons: [{
            text: 'Generate Excel',
            id: 'btnGenerateExcel',
            disabled: true,
            handler: function() {
                if(sel_edu_section_id == '' || sel_edu_course_id == '') {
                    Ext.Msg.alert("<?php __('Oops!'); ?>", "<?php __('You have to select a secction and a course first.'); ?>");
                } else {
                    generateExcel();
                }
            }
        }, {
            text: 'Save',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                EduAssessmentRecordingTopForm.disable();
                g.disable();
                Ext.getCmp('btnGenerateExcel').disable();
                var records = store_assessment_records.getRange();
                var params = {};
                var everything_good = true;
                var all_negative = true;
                for(var i = 0; i < records.length; i++) {
                    params['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    params['data['+i+'][mark]'] = Ext.encode(records[i].get('rvalue'));
                    if(records[i].get('rvalue') > records[i].get('max_value')) {
                        everything_good = false;
                    }
                    if(records[i].get('rvalue') != -1) {
						all_negative = false;
                    }
                }
                if(all_negative) {
					Ext.Msg.alert("<?php __('Oops!'); ?>", "<?php __('You cannot save while all mark values are -1.'); ?>");
					EduAssessmentRecordingTopForm.enable();
					g.enable();
					Ext.getCmp('btnClose').enable();
				} else if (everything_good) {
                    Ext.Ajax.request({
                        url: '<?php echo $this->Html->url(array(
                            'controller' => 'edu_assessments', 'action' => 'save_assessment_records')); ?>',
                        params: params,
                        method: 'POST',
                        success: function() {
                            Ext.Msg.alert("<?php __('Success'); ?>",
                                "<?php __('Assessment Records created/updated successfully!'); ?>");
                            EduAssessmentRecordingTopForm.enable();
                            g.enable();
                            RefreshEduAssessmentRecordsData();
                        },
                        failure: function() {
                            alert('Error Saving Schedules, Please Try Again!');
                            EduAssessmentRecordingTopForm.enable();
                            g.enable();
                        }
                    });
                } else {
                    Ext.Msg.alert('<?php __('Error'); ?>',
                        '<?php __('Some values are above the expected, Please correct it, then try Again!'); ?>');

                    EduAssessmentRecordingTopForm.enable();
                    g.enable();
                }
            }
        }, {
            text: 'Submit All',
            id: 'btnSubmitAllAssessment',
            disabled: true,
            handler: function(btn) {
                Ext.Msg.show({
                    title: '<?php __('Are you sure?'); ?>',
                    buttons: Ext.MessageBox.YESNOCANCEL,
                    msg: '<?php __('Are you sure to submit?'); ?>',
                    icon: Ext.MessageBox.QUESTION,
                    fn: function(btn){
                        if (btn == 'yes'){
                            EduAssessmentRecordingTopForm.disable();
                            g.disable();
                            var params = {};
                            params['data[edu_section_id]'] = sel_edu_section_id;
                            params['data[edu_course_id]'] = sel_edu_course_id;
                            Ext.Ajax.request({
                                url: '<?php echo $this->Html->url(array(
                                    'controller' => 'edu_assessments', 'action' => 'submit_assessments')); ?>/',
                                params: params,
                                method: 'POST',
                                success: function(response, opts){
                                    Ext.Msg.alert("<?php __('Success'); ?>",
                                        "<?php __('Assessment Records submitted successfully!'); ?>");
                                    EduAssessmentRecordingTopForm.enable();
                                    g.enable();
                                    RefreshEduAssessmentRecordsData();
                                },
                                failure: function(response, opts){
                                    var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                                    ShowErrorBox(obj.errormsg, obj.helpcode);

                                    EduAssessmentRecordingTopForm.enable();
                                    g.enable();
                                }
                            });
                        }
                    }
                });
                
            }
        }, {
            text: 'View Detail',
            id: 'btnViewDetail',
            disabled: true,
            handler: function(btn){
                viewAssessmentRecordsDetail();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                EduAssessmentRecordingWindow.close();
            }
        }]
    });
    EduAssessmentRecordingWindow.show();

    var popUpWin_1=0;
	
	function popUpWindow(URLStr, left, top, width, height) {
		if(popUpWin_1){
			if(!popUpWin_1.closed) popUpWin_1.close();
		}
		popUpWin_1 = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
	}

	function generateExcel() {
		url = "<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'generate_excel')); ?>/" + sel_edu_section_id + '/' + sel_edu_course_id;
		popUpWindow(url, 0, 0, 1200, 1200);
	}