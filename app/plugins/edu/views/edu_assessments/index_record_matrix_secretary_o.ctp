//<script>
    var store_assessment_matrix_records = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'student', 'identity_number'
                <?php foreach ($assessments as $assessment) { ?>
                , 'ar_id_<?php echo $assessment['EduAssessment']['id']; ?>',
                {name: 'rvalue_<?php echo $assessment['EduAssessment']['id']; ?>', type: 'decimal'},
                'max_value_<?php echo $assessment['EduAssessment']['id']; ?>'
                <?php } ?>,
                'remark'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'list_data_matrix_records')); ?>'
		})
    });

	store_assessment_matrix_records.reload({
        params: {
            start: 0,
            edu_course_id: <?php echo $edu_course_id; ?>,
            edu_section_id: <?php echo $edu_section_id; ?>,
        }
    });

    function reloadTheAssessmentMatrix(){
        store_assessment_matrix_records.reload({
            params: {
                start: 0,
                edu_course_id: <?php echo $edu_course_id; ?>,
                edu_section_id: <?php echo $edu_section_id; ?>,
            }
        });
    }

    <?php
        $this->ExtForm->create('EduAssessment');
        $this->ExtForm->defineFieldFunctions();
    ?>

    function viewAssessmentRecordsDetail(){
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'view_detail')); ?>/'+
                <?php echo $edu_section_id; ?>+'/'+<?php echo $edu_course_id; ?>,
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
                width: 100,
                sortable: false
            }
            <?php foreach ($assessments as $assessment) { ?>
            , {
                header: '<p title="<?php echo $assessment['EduAssessmentType']['name']; ?>">'+
                    '<?php echo $assessment['EduAssessment']['max_value']; ?></p>',
                dataIndex: 'rvalue_<?php echo $assessment['EduAssessment']['id']; ?>',
                width: 50,
                sortable: false,
                menuDisabled: true,
                align: 'center'
                <?php if ($assessment['EduAssessment']['status'] == 'S') { ?>,
                editor: new Ext.ux.form.SpinnerField({
                    name: 'MarkValue',
                    minValue: -1,   // not filled mark
                    maxValue: <?php echo $assessment['EduAssessment']['max_value']; ?>,
                    allowDecimals: true,
                    decimalPrecision: 1,
                    incrementValue: 1,
                    alternateIncrementValue: 5,
                    accelerate: true
                })
                <?php } ?>
            }
            <?php } ?>, {
                header: 'Remark',
                dataIndex: 'remark',
                width: 100,
                sortable: false
            }
            
        ]
    });
	
    var g = new Ext.grid.EditorGridPanel({
        title: '<?php __('Record Matrix'); ?>',
        cm: cm,
        store: store_assessment_matrix_records,
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
        }
    });
    
    g.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if (!(e.originalValue === e.value)) {
            Ext.getCmp('btnSaveAll').enable();
            Ext.getCmp('btnSubmitAllAssessment').disable();
        }
    }
    
    var EduAssessmentMatrixRecordingWindow = new Ext.Window({
        title: 'Assessment Records Matrix',
        width: 700,
        autoHeight: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: g,

        buttons: [{
            text: 'Save',
            id: 'btnSaveAll',
            disabled: true,
            handler : function() {
				g.disable();
				Ext.getCmp('btnSaveAll').disable();
				Ext.getCmp('btnSubmitAllAssessment').disable();
				//Ext.getCmp('btnViewDetail').disable();
				Ext.getCmp('btnClose').disable();
				
                var records = store_assessment_matrix_records.getRange();
                var params = {};
				var all_negative = false;
				var everything_good = true;
                for(var i = 0; i < records.length; i++) {
                    params['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    params['data['+i+'][edu_course_id]'] = <?php echo $edu_course_id; ?>,
                    params['data['+i+'][edu_section_id]'] = <?php echo $edu_section_id; ?>,
                    
                    <?php foreach ($assessments as $assessment) { ?>
                    params['data['+i+'][ar_id_<?php echo $assessment['EduAssessment']['id']; ?>]'] =
                        Ext.encode(records[i].get('ar_id_<?php echo $assessment['EduAssessment']['id']; ?>'));
                    params['data['+i+'][mark_<?php echo $assessment['EduAssessment']['id']; ?>]'] =
                        Ext.encode(records[i].get('rvalue_<?php echo $assessment['EduAssessment']['id']; ?>'));

                    <?php } ?>
                }

                if(all_negative) {
					Ext.Msg.alert("<?php __('Oops!'); ?>", "<?php __('You cannot save while all mark values are -1.'); ?>");
					g.enable();
					Ext.getCmp('btnClose').enable();
				} else {
					Ext.Ajax.request({
						url: '<?php echo $this->Html->url(array(
                            'controller' => 'edu_assessments', 'action' => 'save_assessment_records_matrix')); ?>',
						params: params,
						method: 'POST',
						success: function() {
							Ext.Msg.alert("<?php __('Success'); ?>",
                                "<?php __('Assessment Records Matrix created/updated successfully!'); ?>");
							
							reloadTheAssessmentMatrix();
							g.enable();
							Ext.getCmp('btnSaveAll').enable();
							Ext.getCmp('btnSubmitAllAssessment').enable();
							//Ext.getCmp('btnViewDetail').enable();
							Ext.getCmp('btnClose').enable();
						},
						failure: function() {
							alert('Error Saving Schedules, Please Try Again!');
							g.enable();
							Ext.getCmp('btnSaveAll').enable();
							Ext.getCmp('btnSubmitAllAssessment').enable();
							//Ext.getCmp('btnViewDetail').enable();
							Ext.getCmp('btnClose').enable();
						}
					});
				}
            }
        }, {
            text: 'Submit All',
            id: 'btnSubmitAllAssessment',
            disabled: true,
            handler: function(btn){
				g.disable();
				Ext.getCmp('btnSaveAll').disable();
				Ext.getCmp('btnSubmitAllAssessment').disable();
				//Ext.getCmp('btnViewDetail').disable();
				Ext.getCmp('btnClose').disable();
				
				var records = store_assessment_matrix_records.getRange();
                var all_negative = false; // was true
				
				if(all_negative) {
					Ext.Msg.alert("<?php __('Oops!'); ?>",
                        "<?php __('You cannot submit assessment while all mark values are -1.'); ?>");
				} else {
					var params = {};
					params['data[edu_section_id]'] = <?php echo $edu_section_id; ?>;
					params['data[edu_course_id]'] = <?php echo $edu_course_id; ?>;

					Ext.Ajax.request({
						url: '<?php echo $this->Html->url(array(
                            'controller' => 'edu_assessments', 'action' => 'submit_assessments')); ?>/',
						params: params,
						method: 'POST',
						success: function(response, opts){
							Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Assessment Records submitted successfully!'); ?>");

                            if(typeof EduAssessmentAllRecordingWindow !== 'undefined') {
                                EduAssessmentAllRecordingWindow.close();
                            }
                            if(typeof EduAssessmentRecordingMatrixWindow !== 'undefined') {
                                EduAssessmentRecordingMatrixWindow.close();
                            }
						},
						failure: function(response, opts){
							var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
							ShowErrorBox(obj.errormsg, obj.helpcode);

							g.enable();
							Ext.getCmp('btnSaveAll').enable();
							Ext.getCmp('btnSubmitAllAssessment').enable();
							//Ext.getCmp('btnViewDetail').enable();
							Ext.getCmp('btnClose').enable();
						}
					});
				}
            }
        }, {
            text: 'View Detail',
            id: 'btnViewDetail',
            //disabled: true,
            handler: function(btn){
                viewAssessmentRecordsDetail();
            }
        }, {
            text: 'Close',
			id: 'btnClose',
            handler: function(btn){
                EduAssessmentMatrixRecordingWindow.close();
                
                if(typeof EduAssessmentAllRecordingWindow !== 'undefined') {
                    EduAssessmentAllRecordingWindow.close();
                }
                if(typeof EduAssessmentRecordingMatrixWindow !== 'undefined') {
                    EduAssessmentRecordingMatrixWindow.close();
                }
            }
        }]
    });
    EduAssessmentMatrixRecordingWindow.show();
