//<script>
    var sel_edu_section_id = '';
    var sel_edu_course_id = '';
	
    var store_students = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_student', 'identity_number', 'course_result', 'teacher_comment'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'list_data_for_comment')); ?>'	
		})
    });
	
    function RefreshStudentListData() {
        if(sel_edu_course_id != '' && sel_edu_section_id != ''){
            store_students.reload({
				params: {
                    start: 0,   
                    edu_section_id: sel_edu_section_id,
					edu_course_id: sel_edu_course_id
				}
            });
		}
    }

    <?php
        $this->ExtForm->create('EduRegistrationQuarterResult');
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

    function getCommentForm(id = 0){
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_registration_quarter_results', 'action' => 'set_comment')); ?>/'+id,
            success: function(response, opts) {
                var comment_data = response.responseText;

                eval(comment_data);

                CourseCommentEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the comment edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

	var EduCourseCommentTopForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'',

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
                $options['anchor'] = '45%';
				$this->ExtForm->input('edu_class_id', $options);
            ?>, {
                xtype: 'combo',
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
                    'select': function(combo, record, index){ 
                        sel_edu_section_id = combo.getValue();

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
                    'select': function(combo, record, index){ 0
                        sel_edu_course_id = combo.getValue();
						
						RefreshStudentListData();
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
                dataIndex: 'edu_student',
                width: 200,
                sortable: false
            }, {
                header: 'Student ID',
                dataIndex: 'identity_number',
                width: 50,
                sortable: false
            }, {
                header: 'Course Result',
                dataIndex: 'course_result',
                width: 50,
                sortable: false
            }, {
                header: 'Comment',
                dataIndex: 'teacher_comment',
                width: 100,
                sortable: true
            }
        ]
    });
	
    var g = new Ext.grid.GridPanel({
        title: '<?php __('Students'); ?>',
        cm: cm,
        store: store_students,
        loadMask: true,
        stripeRows: true,
        clicksToEdit: 1,
        height: 300,
        anchor: '100%',
        id: 'studentGrid',
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
			celldblclick: function(){
				var id = Ext.getCmp('studentGrid').getSelectionModel().getSelected().data.id;
				if(id > 0) {
					getCommentForm(Ext.getCmp('studentGrid').getSelectionModel().getSelected().data.id);
				} else {
					Ext.Msg.show({
						title: '<?php __('Oops!'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: 'The selected Student has incorrect configuration',
						icon: Ext.MessageBox.ERROR
					});
				}
			}
        }
    });
    
    var EduCourseCommentWindow = new Ext.Window({
        title: 'Course Comment',
        width: 700,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: [
            EduCourseCommentTopForm, g
        ],
        buttons: [{
            text: 'Close',
			id: 'btnClose',
            handler: function(btn){
                EduCourseCommentWindow.close();
            }
        }]
    });
    EduCourseCommentWindow.show();
