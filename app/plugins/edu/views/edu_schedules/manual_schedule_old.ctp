//<script>
    var selsection='';
    
    var store_periods = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','day' <?php for($i = 1; $i <=$num_periods; $i++){ echo ", 'period$i'"; } ?>
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'list_data_periods')); ?>'	
        })
    });
    
    function RefreshPeriodsData() {
        if(selsection != ''){
            store_periods.reload({
                params: {
                    start: 0,    
                    selsection: selsection
                }
            });
        }
    }
    
    <?php
        $this->ExtForm->create('EduSchedule');
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
    store_course.load();
    
    var store_teacher = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id2', 'name'		
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'list_data')); ?>'
        })
    });
    store_teacher.load();
    
    var myApplicationData = [
        ['Academic Year', 'Academic Year']
    ];
    var store_applications = new Ext.data.ArrayStore({
        fields: [
           {name: 'id'},
           {name: 'name'}
        ]
    });
    store_applications.loadData(myApplicationData);
    
    function loadClassCourses(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'get_class_courses')); ?>/'+id,
            success: function(response, opts) {
                var class_courses_data = response.responseText;
                eval(class_courses_data);
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot load the courses. Error code'); ?>: ' + response.status);
            }
	});
    }
    
    var popUpWin_print=0;

    function popUpWindow(URLStr, left, top, width, height) {
        if(popUpWin_print){
            if(!popUpWin_print.closed) popUpWin_print.close();
        }
        popUpWin_print = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function printSchedule() {
        url = "<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'print_schedule', 'plugin' => 'edu')); ?>/"+selsection;
        popUpWindow(url, 200, 200, 700, 1000);
    }
    
    var ManualSchedulerForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'manual_schedule')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Class', 'anchor' => '45%');
                $options['items'] = $classes;
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        ManualSchedulerForm.disable();
                        var section = Ext.getCmp('section_id');
                        section.setValue('');
                        section.store.removeAll();
                        section.store.reload({
                            params: {
                                edu_class_id : combo.getValue()
                            }
                        });
                        selsection='';
                        
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
                        
                        periodsGrid.getStore().removeAll();
                        loadClassCourses(combo.getValue());
                        ManualSchedulerForm.enable();
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
                    'select': function(combo, record, index){ 
                        selsection=combo.getValue();
                        RefreshPeriodsData();
                        Ext.getCmp('btnPrintSchedule').enable();
                    } 
                }
            }, {
                xtype: 'combo',
                name: 'edu_academic_year_id',
                id:'edu_academic_year_id',
                disabled: true,
                store : store_applications,
                displayField : 'name',
                valueField : 'id',
                anchor:'45%',
                fieldLabel: 'Applicable for',
                mode: 'local',
                editable: false,
                lazyRender: true,                       
                emptyText: 'Select Section',
                value: 'Academic Year'
            }
        ]
    });
    
<?php for($i = 1; $i <= $num_periods; $i++) { ?>
    var element<?php echo $i; ?> = document.createElement('div');
    element<?php echo $i; ?>.id = "courseCombo<?php echo $i; ?>";
    document.body.appendChild(element<?php echo $i; ?>);
    document.getElementById("courseCombo<?php echo $i; ?>").innerHTML='<select name="courses<?php echo $i; ?>" id="courses<?php echo $i; ?>" style="display:none;"><option value="-">-</option><?Php 
    foreach($courses as $course){
        $p = $course['EduCourse']['description'];
        echo '<option value="'.$p.'">'.$p.'</option>';
    }
    ?></select>';
<?php } ?>
    
<?php for($i = 1; $i <= $num_periods; $i++) { ?>
    var element<?php echo $i + 5; ?> = document.createElement('div');
    element<?php echo $i + 5; ?>.id = "teacherCombo<?php echo $i + 5; ?>";
    document.body.appendChild(element<?php echo $i + 5; ?>);
    document.getElementById("teacherCombo<?php echo $i + 5; ?>").innerHTML='<select name="teacher<?php echo $i + 5; ?>" id="teacher<?php echo $i + 5; ?>" style="display:none;"><option value="-">-</option><?Php 
    foreach($courses as $course){
        $p = $course['EduCourse']['description'];
        echo '<option value="T'.$p.'">T'.$p.'</option>';
    }
    ?></select>';
<?php } ?>    
   
    var fm = Ext.form;
    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
                header: 'Day',
                dataIndex: 'day',
                width: 150,
                sortable: false
            }<?php for($i = 1; $i <= $num_periods; $i++) { ?>, {
                header: 'Period <?php echo $i; ?>',
                dataIndex: 'period<?php echo $i; ?>',
                width: 220,
                sortable: false,
                editor: new fm.ComboBox({
                    triggerAction: 'all',
                    forceSelection: true,
                    transform: 'courses<?php echo $i; ?>',
                    lazyRender: true,
                    listClass: 'x-combo-list-small'
                })
            }<?php } ?>
        ]
    });
    
    var periodsGrid = new Ext.grid.EditorGridPanel({
        title: '<?php __('Periods'); ?>',
        id: 'periodsGrid',
        cm: cm,
        store: store_periods,
        frame: true,
        clicksToEdit: 1,
        loadMask: true,
        stripeRows: true,
        height: 219,
        viewConfig: {
            forceFit: true
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        listeners: {
            cellclick: function(grid, rowIndex, columnIndex, e){
                var record = grid.getStore().getAt(rowIndex);
                var record_id = record.get('id');
                var colm = grid.getColumnModel();
                var the_combo = new Ext.form.ComboBox({
                    id: 'combo_courses' + columnIndex,
                    name : 'combo_courses' + columnIndex,
                    hideLabel:false,
                    xtype: 'combo',
                    valueField : 'id2',
                    displayField : 'name',
                    hiddenName : 'id2',
                    store : store_course, 
                    triggerAction : 'all',
                    selectOnFocus:true,
                    forceSelection : true,
                    mode : 'local'
                });
                
                if(record_id > 5){
                    the_combo = new Ext.form.ComboBox({
                        id: 'combo_courses' + columnIndex,
                        name : 'combo_courses' + columnIndex,
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
                    });
                }
                colm.setEditor(columnIndex, the_combo);
                
                //alert(record_id + ' ' + columnIndex);
            }
        }
    });
    
    periodsGrid.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
        }
    }
    
    var ManualScheduleWindow = new Ext.Window({
        title: 'Manual Schedule Manager',
        width: 850,
        height: 375,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            ManualSchedulerForm, periodsGrid
        ],
        buttons: [{
            text: 'Save All',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                ManualScheduleWindow.disable();
                var records = store_periods.getRange();
                var param = {};        
                for(var i = 0; i < records.length; i++) {
                    param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    param['data['+i+'][section_id]'] = selsection;
<?php for($i = 1; $i <= $num_periods; $i++) { ?>
                    param['data['+i+'][periods][<?php echo $i; ?>]'] = Ext.encode(records[i].get('period<?php echo $i; ?>'));
<?php } ?>
                }
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'save_manual_schedules')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Manual Sechedules created successfully!'); ?>");
                        ManualScheduleWindow.enable();
                        RefreshPeriodsData();
                    },
                    failure: function(){
                        alert('Error Saving Schedules, Please Try Again!');
                        ManualScheduleWindow.enable();
                    }
                });
            }
        }, {
            text: 'Print',
            id: 'btnPrintSchedule',
            disabled: true,
            handler: function(btn){
                printSchedule();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                ManualScheduleWindow.close();
            }
        }]
    });
    ManualScheduleWindow.show();