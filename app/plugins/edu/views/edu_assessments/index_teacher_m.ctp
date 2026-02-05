//<script>
    var sel_edu_course_id='';
    var sel_edu_section_id='';

    var store_parent_assessments = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id','assessment_type','teacher','section',
                'max_value','date','status','detail','subject', 'user_id', 'created_by',
                'quarter'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'list_data', $parent_id)); ?>'
		}),
        listeners: {
            scope: this,
            'load': function(store, records, options){
                var totalMax = 0;
				var firstSection = (records.length > 0? records[0].get('section'): '');
                for(var i = 0; i < records.length; i++) {
					if(firstSection == records[i].get('section'))
						totalMax += Number(records[i].get('max_value'));
                }
                var lblTotalMarkValue = Ext.getCmp('lblTotalMarkValue');

                lblTotalMarkValue.setText('Maximum Values out of ' + totalMax);
            }
        }
    });

    function RefreshParentAssessmentData() {
        if(sel_edu_course_id!='' & sel_edu_section_id!=''){
            store_parent_assessments.reload({
				params: {
                    start: 0,
                    edu_course_id: sel_edu_course_id,
                    edu_section_id: sel_edu_section_id
				}
            });
		}
    }

    function AddParentAssessment() {
        if(sel_edu_course_id !='' & sel_edu_section_id!=''){
            Ext.Ajax.request({
				url: '<?php echo $this->Html->url(array(
                    'controller' => 'edu_assessments', 'action' => 'add'));
                    ?>?edu_course_id='+sel_edu_course_id+'&edu_section_id='+sel_edu_section_id,
				success: function(response, opts) {
                    var parent_assessment_data = response.responseText;
			        
                    eval(parent_assessment_data);
			
                    EduAssessmentAddWindow.show();
				},
				failure: function(response, opts) {
                    Ext.Msg.alert('<?php __('Error'); ?>',
                        '<?php __('Cannot get the assessment add form. Error code'); ?>: ' + response.status);
				}
            });
		} else {
            alert('Please Fill in the required form');
		}
	}

    function EditParentAssessment(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_assessments', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_assessment_data = response.responseText;
			
                eval(parent_assessment_data);
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the assessment edit form. Error code'); ?>: ' + response.status);
            }
		});
    }

    function ViewAssessment(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var assessment_data = response.responseText;

                eval(assessment_data);

                EduAssessmentViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the assessment view form. Error code'); ?>: ' + response.status);
            }
		});
    }

    function DeleteParentAssessment(id) {
		Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Assessment(s) successfully deleted!'); ?>');
                RefreshParentAssessmentData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the assessment to be deleted. Error code'); ?>: ' + response.status);
            }
		});
    }

    function SearchByParentAssessmentName(value){
		var conditions = '\'Assessment.name LIKE\' => \'%' + value + '%\'';
		store_parent_assessments.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
			}
		});
    }

    <?php
        $this->ExtForm->create('TeacherAllocation');
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

	var AssessmentTopForm = new Ext.form.FormPanel({
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
						
						sel_edu_course_id='';
						sel_edu_section_id='';

                        g.getStore().removeAll();
                        var btnAdd = Ext.getCmp('add-parent-assessment');
                        btnAdd.disable();
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
                    'select': function (combo, record, index) {
						var edu_course_id = Ext.getCmp('edu_course_id');
						edu_course_id.setValue('');
						edu_course_id.store.removeAll();
						edu_course_id.store.reload({
							params: {
								edu_section_id : combo.getValue()
							}
						});
						
                        sel_edu_section_id = combo.getValue();
                        RefreshParentAssessmentData();
                        var btnAdd = Ext.getCmp('add-parent-assessment');
                        btnAdd.disable();
                    }
                }
            }, {
                xtype: 'compositefield',
                fieldLabel: '<span style="color:red;">*</span> Course',
                msgTarget: 'qtip',
                anchor: '-20',
                defaults: {
                    flex: 1
                },
                items: [{
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
                    anchor: '35%',
                    blankText: 'Your input is invalid.',
                    fieldLabel: '<span style="color:red;">*</span> Course',
                    store : store_courses,
                    listeners:{
                        scope: this,
                        'select': function (combo, record, index) {
                            sel_edu_course_id=combo.getValue();
                            
                            var btnAdd = Ext.getCmp('add-parent-assessment');
                            btnAdd.enable();
                            RefreshParentAssessmentData();
                        }
                    }
                }, {
                    xtype: 'label',
                    value: '0.0%',
                    text: '0.0%',
                    id: 'lblTotalMarkValue'
                }]
            }
        ]
    });
	
	
    var g = new Ext.grid.GridPanel({
        title: '<?php __('Assessments'); ?>',
        store: store_parent_assessments,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'assessmentGrid',
        columns: [
            {header: "<?php __('Assessment'); ?>", dataIndex: 'assessment_type', sortable: true},
            {header: "<?php __('Max Value'); ?>", dataIndex: 'max_value', sortable: true},
            {header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true},
            {header: "<?php __('Section'); ?>", dataIndex: 'section', sortable: true},
            {header: "<?php __('Quarter'); ?>", dataIndex: 'quarter', sortable: true},
			{header: "<?php __('Maintained By'); ?>", dataIndex: 'created_by', sortable: true},
            {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
			{header: "<?php __('Remark'); ?>", dataIndex: 'detail', sortable: true}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    id: 'add-parent-assessment',
                    tooltip:'<?php __('<b>Add Assessment</b><br />Click here to create a new Assessment'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        AddParentAssessment();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-assessment',
                    tooltip:'<?php __('<b>Edit Assessment</b><br />Click here to modify the selected Assessment'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            EditParentAssessment(sel.data.id);
                        }
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-assessment',
                    tooltip:'<?php __('<b>Delete Assessment(s)</b><br />Click here to remove the selected(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()){
                            if(sel.length==1){
                                Ext.Msg.show({
                                    title: '<?php __('Remove Assessment'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> '+sel[0].data.assessment_type+
                                        ' of '+sel[0].data.max_value+'?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
											//if(sel[0].data.deletable == 1) {
											DeleteParentAssessment(sel[0].data.id);
											//} else {
											//	Ext.Msg.alert('<?php __('Ooops'); ?>',
                                            //        '<?php __('You cannot delete assessment while marked.'); ?>');
											//}
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Assessment'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Assessment'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            var sel_ids = '';
                                            for(i=0;i<sel.length;i++){
                                                if(i>0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentAssessment(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        };
                    }
                }, ' ','-',' ',' '
            ]})
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		record = g.getStore().getAt(rowIdx);
	    if('<?php echo $user_id; ?>' == record.get('user_id')) {
			g.getTopToolbar().findById('edit-parent-assessment').enable();
			g.getTopToolbar().findById('delete-parent-assessment').enable();
            if(record.get('status') == 'Submitted') {
                g.getTopToolbar().findById('edit-parent-assessment').disable();
			    g.getTopToolbar().findById('delete-parent-assessment').disable();
            }
		} else {
			g.getTopToolbar().findById('edit-parent-assessment').disable();
			g.getTopToolbar().findById('delete-parent-assessment').disable();
		}
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-assessment').disable();
        g.getTopToolbar().findById('delete-parent-assessment').disable();
    });

    var parentAssessmentsViewWindow = new Ext.Window({
        title: 'Assessment Management',
        width: 700,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            AssessmentTopForm,g
        ],
        buttons: [{
                text: 'Close',
                handler: function(btn){
                    parentAssessmentsViewWindow.close();
                }
            }]
    });
    parentAssessmentsViewWindow.show();
