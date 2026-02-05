//<script>
    var sel_section='';
    <?php
        $this->ExtForm->create('EduSection');
    ?>
	
	var store_assocs = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_course_id', 'edu_section_id', 'edu_teacher_id', 'course', 'teacher'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_sections', 'action' => 'list_data_course_teacher_association')); ?>'
        })
    });
	
	function RefreshAssociationData() {
        if(sel_section != ''){
            store_assocs.reload({
                params: {
                    start: 0,
                    edu_section_id: sel_section
                }
            });
			Ext.getCmp('btnSaveAll').disable();
        }
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
            url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data')); ?>'
        })
    });
    
    var store_course = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id2', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data')); ?>'
        })
    });
    
    var store_teacher = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'id2', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data')); ?>'
        })
    });
    
    var CourseTeacherAssocForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array(
            'controller' => 'edu_sections', 'action' => 'course_teacher_association')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Class', 'anchor' => '45%');
                $options['items'] = $classes;
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        CourseTeacherAssocForm.el.mask('Please wait', 'x-mask-loading');
                        var section = Ext.getCmp('section_id');
                        section.setValue('');
                        section.store.removeAll();
                        section.store.reload({
                            params: {
                                edu_class_id : combo.getValue()
                            }
                        });
                        sel_section='';
                        
                        store_course.reload({
                            params: {
                                edu_class_id: combo.getValue()
                            }
                        });
                        
                        store_teacher.reload({
                            params: {
                                edu_class_id: combo.getValue()
                            }
                        });
						
                        CourseTeacherAssocForm.el.unmask();
                    }
                }";
                $this->ExtForm->input('class_id', $options);
            ?>, {
                xtype: 'combo',
                name: 'section_id',
                id:'section_id',
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
                        sel_section = combo.getValue();
                        RefreshAssociationData();
                    }
                }
            }
        ]
    });
   
    var fm = Ext.form;
    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: false // columns are not sortable by default
        },
        columns: [{
                header: 'Course',
                dataIndex: 'course',
                width: 150,
                sortable: false,
				editor: false
            }, {
                header: 'Teacher',
                dataIndex: 'teacher',
                width: 220,
                sortable: false,
                editor: new fm.ComboBox({
                    id: 'combo_teachers',
                    name : 'combo_teacher',
                    hideLabel:false,
                    xtype: 'combo',
                    valueField : 'id2',
                    displayField : 'name',
                    hiddenName : 'id2',
                    store : store_teacher,
                    triggerAction : 'all',
                    selectOnFocus:true,
                    forceSelection : true,
                    mode : 'local'
                })
            }
        ]
    });
    
    var assocGrid = new Ext.grid.EditorGridPanel({
        title: '<?php __('Teacher Course Association'); ?>',
        id: 'assocGrid',
        cm: cm,
        store: store_assocs,
        frame: true,
        clicksToEdit: 1,
        loadMask: true,
		enableColumnHide: false,
		enableColumnMove: false,
		enableHdMenu: false,
        stripeRows: true,
        height: 279,
        viewConfig: {
            forceFit: true,
			getCellCls: function(value) {
                return 'valueColums';
            }
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        })
    });
    
    assocGrid.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
        }
    }
    
    var CourseTeacherAssocWindow = new Ext.Window({
        title: 'Course Teacher Associaion',
        width: 850,
        height: 435,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            CourseTeacherAssocForm, assocGrid
        ],
        buttons: [{
            text: 'Save',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                assocGrid.el.mask('Please wait', 'x-mask-loading');
                var records = store_assocs.getRange();
                var param = {};
				
                for(var i = 0; i < records.length; i++) {
                    param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    param['data['+i+'][edu_teacher_id]'] = records[i].get('teacher');
                }
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array(
                        'controller' => 'edu_sections', 'action' => 'save_course_teacher_associations')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>",
                            "<?php __('Teacher Course Associated successfully!'); ?>");
                        assocGrid.el.unmask();
                        RefreshAssociationData();
                    },
                    failure: function(){
                        alert('Error Saving Teacher Course Association, Please Try Again!');
                        assocGrid.el.unmask();
                    }
                });
            }
        }, {
            text: 'Close',
            handler: function(btn){
                CourseTeacherAssocWindow.close();
            }
        }]
    });
    CourseTeacherAssocWindow.show();
