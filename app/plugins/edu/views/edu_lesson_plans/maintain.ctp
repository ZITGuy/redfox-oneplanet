//<script>
    var selected_section = '';
    var selected_course = '';
    
    var store_lesson_plan_items = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'date', 'period', 'outline', 'activity', 'materials_needed'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_lesson_plans', 'action' => 'list_data_lesson_plan_items')); ?>'
        })
    });

    <?php
        $this->ExtForm->create('EduLessonPlan');
        $this->ExtForm->defineFieldFunctions();
    ?>
    
    var store_outlines = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_outlines', 'action' => 'list_data_for_lesson_plan')); ?>'
        })
    });
    
    function LoadStoreOutline() {
        store_outlines.reload({
            params: {
                edu_course_id: selected_course
            }
        });
    }
    
    var store_courses = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_courses', 'action' => 'list_data')); ?>'
        })
    });
    
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
    
    var EduLessonPlanMaintainForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'eduLessonPlans', 'action' => 'add')); ?>',
        defaultType: 'textfield',

        items: [
            <?php
                $options = array('fieldLabel' => 'Class', 'anchor' => '45%');
                $options['items'] = $edu_classes;
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        EduLessonPlanMaintainForm.disable();
                        var section_combo = Ext.getCmp('edu_section_id');
                        section_combo.setValue('');
                        section_combo.store.removeAll();
                        section_combo.store.reload({
                            params: {
                                edu_class_id : combo.getValue()
                            }
                        });
                        selected_section='';
                        
                        var course_combo = Ext.getCmp('edu_course_id');
                        course_combo.setValue('');
                        course_combo.store.removeAll();
                        course_combo.store.reload({
                            params: {
                                edu_class_id: combo.getValue()
                            }
                        });
                        selected_course='';
                        
                        lessonPlanItemsGrid.getStore().removeAll();
                        EduLessonPlanMaintainForm.enable();
                    }
                }";
                $this->ExtForm->input('class_id', $options);
            ?>, {
                xtype: 'combo',
                name: 'edu_course_id',
                id:'edu_course_id',
                typeAhead: true,
                store : store_courses,
                displayField : 'name',
                valueField : 'id',
                anchor:'45%',
                fieldLabel: '<span style="color:red;">*</span> Course',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Course',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        selected_course=combo.getValue();
                        LoadStoreOutline();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'edu_section_id',
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
                listeners: {
                    scope: this,
                    'select': function(combo, record, index){ 
                        selected_section=combo.getValue();
                        RefreshLessonPlanItemsData();
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
                header: 'Date',
                dataIndex: 'date',
                width: 150,
                sortable: false
            }, {
                header: 'Period',
                dataIndex: 'period',
                width: 150,
                sortable: false
            }, {
                header: 'Outline',
                dataIndex: 'outline',
                width: 220,
                sortable: false,
                editor: new fm.ComboBox({
                    id: 'combo_outlines',
                    name : 'combo_outlines',
                    hideLabel:false,
                    xtype: 'combo',
                    valueField : 'name',
                    displayField : 'name',
                    hiddenName : 'name',
                    store : store_outlines, 
                    triggerAction : 'all',
                    selectOnFocus:true,
                    forceSelection : true,
                    mode : 'local'
                })
            }, {
                header: 'Activity',
                dataIndex: 'activity',
                width: 100,
                sortable: false,
                editor: new fm.TextField({
                    allowBlank: true
                })
            }, {
                header: 'Materials Needed',
                dataIndex: 'materials_needed',
                width: 100,
                sortable: false,
                editor: new fm.TextField({
                    allowBlank: true
                })
            }
        ]
    });
    
    var lessonPlanItemsGrid = new Ext.grid.EditorGridPanel({
        title: '<?php __('Lesson Plan Items'); ?>',
        id: 'lessonPlanItemsGrid',
        cm: cm,
        store: store_lesson_plan_items,
        frame: true,
        clicksToEdit: 1,
        loadMask: true,
        stripeRows: true,
        height: 319,
        viewConfig: {
            forceFit: true
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        })
    });
    
    lessonPlanItemsGrid.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
        }
    }
    
    function RefreshLessonPlanItemsData() {
        if(selected_section != '' && selected_course != ''){
            store_lesson_plan_items.reload({
                params: {
                    start: 0,    
                    selected_section: selected_section,
                    selected_course: selected_course
                }
            });
        }
    }
    
    var EduLessonPlanMaintainWindow = new Ext.Window({
        title: '<?php __('Lesson Plan Maintenance'); ?>',
        width: 850,
        height: 475,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            EduLessonPlanMaintainForm, lessonPlanItemsGrid
        ],
        buttons: [{
            text: 'Save All',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                EduLessonPlanMaintainWindow.disable();
                var records = store_lesson_plan_items.getRange();
                var param = {};        
                for(var i = 0; i < records.length; i++) {
                    param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    param['data['+i+'][course_id]'] = selected_course;
                    param['data['+i+'][section_id]'] = selected_section;
                    param['data['+i+'][date]'] = Ext.encode(records[i].get('date'));
                    param['data['+i+'][period]'] = Ext.encode(records[i].get('period'));
                    param['data['+i+'][outline]'] = Ext.encode(records[i].get('outline'));
                    param['data['+i+'][activity]'] = Ext.encode(records[i].get('activity'));
                    param['data['+i+'][materials_needed]'] = Ext.encode(records[i].get('materials_needed'));
                }
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'edu_lesson_plans', 'action' => 'save_lesson_plan')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Lesson plan maintained successfully!'); ?>");
                        EduLessonPlanMaintainWindow.enable();
                        RefreshLessonPlanItemsData();
                    },
                    failure: function(){
                        alert('Error Saving Schedules, Please Try Again!');
                        EduLessonPlanMaintainWindow.enable();
                    }
                });
            }
        }, {
            text: 'Print',
            id: 'btnPrintLessonPlan',
            disabled: true,
            handler: function(btn){
                printLessonPlan();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                EduLessonPlanMaintainWindow.close();
            }
        }]
    });
    
    function printLessonPlan(){
    
    }