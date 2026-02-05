//<script>
    var sel_edu_section_id = '';
    var sel_edu_course_id = '';

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

    function viewAssessmentRecordsMatrixDetail(){
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
                    '<?php __('Cannot get the assessment Matrix view form. Error code'); ?>: ' + response.status);
            }
        });
    }

	var EduAssessmentRecordingMatrixTopForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url: '',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',

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

                        //Ext.getCmp('btnViewDetail').disable();
					}
				}";
                $options['anchor'] = '95%';
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
                anchor:'95%',
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
                anchor: '95%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Course',
                store : store_courses,
                listeners: {
                    scope: this,
                    'select': function(combo, record, index) {
                        var edu_section_id = Ext.getCmp('edu_section_id');

                        sel_edu_course_id = combo.getValue();

                        Ext.getCmp('btnOpen').enable();
                        //Ext.getCmp('btnViewDetail').enable();

                        MaintainEduAssessmentRecordsMatrix();
                    }
                }
            }
        ]
    });

    function MaintainEduAssessmentRecordsMatrix() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'index_record_matrix_secretary_o')); ?>/'+
                sel_edu_section_id+'/'+sel_edu_course_id,
            success: function(response, opts) {
                var assessment_data = response.responseText;
                eval(assessment_data);
                EduAssessmentMatrixRecordingWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the assessment view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    
    var EduAssessmentRecordingMatrixWindow = new Ext.Window({
        title: 'Assessment Matrix Management',
        width: 400,
        minWidth: 400,
        autoHeight: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: EduAssessmentRecordingMatrixTopForm,

        buttons: [{
            text: 'Open',
            id: 'btnOpen',
            disabled: true,
            handler: function(btn){
                MaintainEduAssessmentRecordsMatrix();
            }
        }, {
            text: 'View Detail',
            id: 'btnViewDetail',
            //disabled: true,
            handler: function(btn){
                viewAssessmentRecordsMatrixDetail();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                EduAssessmentRecordingMatrixWindow.close();
            }
        }]
    });
    EduAssessmentRecordingMatrixWindow.show();
