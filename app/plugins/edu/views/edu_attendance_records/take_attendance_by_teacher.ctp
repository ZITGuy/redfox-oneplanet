//<script>
    <?php
        $this->ExtForm->create('EduAttendanceRecord');
    ?>
    var sel_section = '';
    var sel_date = '';

    var store_students = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'student', 'status', 'remark'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_attendance_records', 'action' => 'list_data_students')); ?>'
        })
    });
    
    function RefreshStudentAttendanceData() {
        if(sel_section != '' && sel_date != ''){
            store_students.reload({
                params: {
                    start: 0,
                    selsection: sel_section,
                    seldate: sel_date
                }
            });
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
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_sections', 'action' => 'list_data_for_teacher')); ?>'
        })
    });
 
    var store_dates = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_attendance_records', 'action' => 'list_of_dates')); ?>'
        })
    });

    
    var status_data = [
        ['Present', 'Present'],
        ['Absent', 'Absent'],
        ['Late Comer', 'Late Comer'],
        ['Sick', 'Sick'],
        ['Permission', 'Permission']
    ];
    var store_statuses = new Ext.data.ArrayStore({
        fields: [
           {name: 'id'},
           {name: 'name'}
        ]
    });
    store_statuses.loadData(status_data);
    
    var TakeAttendanceForm = new Ext.form.FormPanel({
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
            'controller' => 'edu_attendance_records', 'action' => 'take_attendance_by_teacher')); ?>',
        defaultType: 'textfield',
        items: [
            <?php
                $options = array('fieldLabel' => 'Class', 'anchor' => '35%');
                $options['items'] = $classes;
                $options['listeners'] = "{
                    scope: this,
                    'select': function(combo, record, index){
                        TakeAttendanceForm.disable();
                        var section = Ext.getCmp('section_id');
                        section.setValue('');
                        section.store.removeAll();
                        section.store.reload({
                            params: {
                                edu_class_id : combo.getValue()
                            }
                        });
                        sel_section='';
                        
                        studentAttendanceGrid.getStore().removeAll();
                        TakeAttendanceForm.enable();
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
                anchor:'35%',
                fieldLabel: '<span style="color:red;">*</span> Section',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Section',
                editable: false,
                triggerAction: 'all',
                listeners: {
                    scope: this,
                    'select': function(combo, record, index) {
                        sel_section=combo.getValue();

                        var cboDate = Ext.getCmp('cboDate');
                        cboDate.setValue('');
                        cboDate.store.removeAll();
                        cboDate.store.reload({
                            params: {
                                edu_section_id : combo.getValue()
                            }
                        });
                        sel_date='';
                    }
                }
            }, {
                xtype: 'combo',
                name: 'cboDate',
                id:'cboDate',
                typeAhead: true,
                store : store_dates,
                displayField : 'name',
                valueField : 'id',
                anchor:'35%',
                fieldLabel: '<span style="color:red;">*</span> Date',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Date',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        sel_date=combo.getValue();

                        RefreshStudentAttendanceData();

                        Ext.getCmp('btnSaveAll').enable();
                        Ext.getCmp('btnSubmit').enable();
                        Ext.getCmp('btnSubmitAll').enable();
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
                width: 200,
                sortable: false
            }, {
                header: 'Status',
                dataIndex: 'status',
                width: 60,
                sortable: false,
                editor: new fm.ComboBox({
                    id: 'combo_courses',
                    name : 'combo_courses',
                    hideLabel:false,
                    xtype: 'combo',
                    valueField : 'id2',
                    displayField : 'name',
                    hiddenName : 'id2',
                    store : store_statuses,
                    triggerAction : 'all',
                    selectOnFocus:true,
                    forceSelection : true,
                    mode : 'local',
                    typeAhead: true,
                    allowBlank: false,
                    emptyText: 'Select One',
                    editable: false
                })
            }, {
                header: 'Remark',
                dataIndex: 'remark',
                width: 200,
                sortable: false,
                editor: new fm.TextField()
            }
        ]
    });
    
    var studentAttendanceGrid = new Ext.grid.EditorGridPanel({
        title: '<?php __('Attendance Sheet'); ?>',
        id: 'studentAttendanceGrid',
        cm: cm,
        store: store_students,
        frame: true,
        clicksToEdit: 1,
        loadMask: true,
        stripeRows: true,
        height: 320,
        viewConfig: {
            forceFit: true
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        listeners: {
            cellclick: function(grid, rowIndex, columnIndex, e){
                if(columnIndex == 1){
                    var colm = grid.getColumnModel();
                    var the_combo = new Ext.form.ComboBox({
                        id: 'combo_statuses',
                        name: 'combo_statuses',
                        hideLabel: false,
                        xtype: 'combo',
                        valueField: 'id',
                        displayField: 'name',
                        hiddenName: 'id',
                        store: store_statuses,
                        triggerAction: 'all',
                        selectOnFocus: true,
                        forceSelection: true,
                        mode: 'local',
                        typeAhead: false,
                        allowBlank: false,
                        emptyText: 'Select One'
                    });
                    colm.setEditor(columnIndex, the_combo);
                }
            }
        }
    });
    
    studentAttendanceGrid.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
            Ext.getCmp('btnSubmit').enable();
            Ext.getCmp('btnSubmitAll').enable();
        }
    }
    
    var TakeAttendanceWindow = new Ext.Window({
        title: 'Take Attendance',
        width: 850,
        height: 450,
        resizable: false,
        plain: true,
        bodyStyle:'padding:5px;',
        buttonAlign: 'right',
        modal: true,
        items: [
            TakeAttendanceForm, studentAttendanceGrid
        ],
        buttons: [{
            text: 'Save',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                saveOrSubmitAttendance('N');
            }
        }, {
            text: 'Save & Submit',
            id: 'btnSubmit',
            disabled: true,
            handler : function(){
                saveOrSubmitAttendance('S');
                store_students.removeAll();
                store_dates.removeAll();
                sel_date = '';
                var cboDate = Ext.getCmp('cboDate');
                cboDate.setValue('');

                Ext.getCmp('btnSaveAll').disable();
                Ext.getCmp('btnSubmit').disable();
                Ext.getCmp('btnSubmitAll').disable();
            }
        }, {
            text: 'Submit All',
            id: 'btnSubmitAll',
            disabled: true,
            handler : function(){
                saveOrSubmitAttendance('SA');
                store_students.removeAll();
                store_dates.removeAll();
                sel_date = '';
                var cboDate = Ext.getCmp('cboDate');
                cboDate.setValue('');

                Ext.getCmp('btnSaveAll').disable();
                Ext.getCmp('btnSubmit').disable();
                Ext.getCmp('btnSubmitAll').disable();
            }
        }, {
            text: 'Close',
            handler: function(btn){
                TakeAttendanceWindow.close();
            }
        }]
    });
    TakeAttendanceWindow.show();
    
    function saveOrSubmitAttendance(btn) {
        TakeAttendanceForm.disable();
        studentAttendanceGrid.disable();
        var records = store_students.getRange();
        var edu_day_id = sel_date;
        var param = {};
        for(var i = 0; i < records.length; i++) {
            param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
            param['data['+i+'][section_id]'] = sel_section;
            param['data['+i+'][status]'] = Ext.encode(records[i].get('status'));
            param['data['+i+'][remark]'] = Ext.encode(records[i].get('remark'));
            param['data['+i+'][action]'] = btn;
            param['data['+i+'][edu_day_id]'] = edu_day_id;
        }
        
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_attendance_records', 'action' => 'save_attendance')); ?>',
            params: param,
            method: 'POST',
            success: function(){
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Attendance taken successfully!'); ?>");
                TakeAttendanceForm.enable();
                studentAttendanceGrid.enable();
                RefreshStudentAttendanceData();
            },
            failure: function(){
                alert('Error Saving Attendance data, Please Try Again!');
                TakeAttendanceForm.enable();
                studentAttendanceGrid.enable();
            }
        });
    }
    