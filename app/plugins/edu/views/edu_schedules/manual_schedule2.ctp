//<script>
    var selsection='';
    var selteacher = '0';
	
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
                        ManualSchedulerForm.el.mask('Please wait', 'x-mask-loading');
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
						
                        ManualSchedulerForm.el.unmask();
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
                name: 'teacher_id',
                id:'teacher_id',
                store : store_teacher,
                displayField : 'name',
                valueField : 'id2',
                anchor:'45%',
                fieldLabel: '<span style="color:red;">*</span> Uni-Teacher',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Teacher',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function(combo, record, index){ 
                        selteacher = combo.getValue();
                        RefreshPeriodsData();
                        Ext.getCmp('btnPrintSchedule').enable();
                    } 
                }
            }
        ]
    });   
    
    var ManualScheduleWindow = new Ext.Window({
        title: 'Manual Schedule Manager',
        width: 850,
        height: 435,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        layout:'table',
        layoutConfig: {
            columns: 3
        },
        items: [
            ManualSchedulerForm,
            {html:'1,2' },
            {html:'3,2'},
            {html:'1,1',rowspan:3, height: 200}
        ],
        buttons: [{
            text: 'Save All',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                ManualSchedulerForm.el.mask('Please wait', 'x-mask-loading');
                var records = store_periods.getRange();
                var param = {};
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'edu_schedules', 'action' => 'save_manual_schedules')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Manual Sechedules created successfully!'); ?>");
                        ManualSchedulerForm.el.unmask();
                        RefreshPeriodsData();
                    },
                    failure: function(){
                        alert('Error Saving Schedules, Please Try Again!');
                        ManualSchedulerForm.el.unmask();
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