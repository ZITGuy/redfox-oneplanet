//<script>
    var store_students = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'identity_number', 'gender', 'age', 'section'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_students', 'action' => 'list_data_section',
                $section['EduSection']['id'])); ?>'
        })
    });

    var element = document.createElement('div');
    element.id = "sectionCombo";
    document.body.appendChild(element);
    document.getElementById("sectionCombo").innerHTML=
        '<select name="sections" id="sections" style="display:none;"><option value="None">None</option><?Php
    foreach ($edu_sections as $sec) {
        echo '<option value="'.$sec['EduSection']['name'] . ' - ' .
            $sec['EduClass']['name'].'">'.$sec['EduSection']['name'] . ' - ' .$sec['EduClass']['name'].'</option>';
    }
   ?></select>';
    var fm = Ext.form;
    
    var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [{
            id: 'name',
            header: 'Student Name',
            dataIndex: 'name',
            width: 200,
            sortable: false
        }, {
            header: 'ID Number',
            dataIndex: 'identity_number',
            width: 100,
            sortable: false
        }, {
            header: 'Gender',
            dataIndex: 'gender',
            width: 60,
            sortable: false
        }, {
            header: 'Age (Years)',
            dataIndex: 'age',
            width: 120,
            align: 'right',
            sortable: false
        }, {
            id: 'section',
            header: 'Section',
            dataIndex: 'section',
            width: 100,
            sortable: false,
            editor: new fm.ComboBox({
                triggerAction: 'all',
                forceSelection: true,
                transform: 'sections',
                lazyRender: true,
                listClass: 'x-combo-list-small'
            })
        }]
    });
    
    var students_grid = new Ext.grid.EditorGridPanel({
        store: store_students,
        cm: cm,
        width: 624,
        height: 508,
        frame: true,
        loadMask: true,
        clicksToEdit: 2,
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        tbar: [{
            text: 'Save All',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                var records = store_students.getRange();
                var param = {};
                for(var i = 0; i < records.length; i++) {
                    param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    param['data['+i+'][section]'] = Ext.encode(records[i].get('section'));
                }
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array(
                        'controller' => 'edu_sections', 'action' => 'save_student_section_changes')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>",
                            "<?php __('Section changes updated successfully!'); ?>");
                        RefreshSectionStudentsData();
                        RefreshSectionsData();
                    },
                    failure: function(){
                        alert('Error Saving Changes, Please Try Again!');
                    }
                });
                
            }
        }]
    });
    
    students_grid.on('afteredit', afterEdit, this );

    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            students_grid.getTopToolbar().findById('btnSaveAll').enable();
        }
    }
    

    var EduSectionStudentsWindow = new Ext.Window({
        title: '<?php __('Students in the Section'); ?>: '+
            '<i><font color=green><?php echo $section['EduSection']['name'] . ' - ' .
                $section['EduClass']['name']; ?></font></i>',
        width: 650,
        height: 585,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [
            students_grid
        ],
        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                EduSectionStudentsWindow.close();
            }
        }]
    });

    store_students.load({
        params: {
            start: 0,
            limit: 50
        }
    });
    
    store_students.on('load', function(){
        if(store_students.getRange().length < 1){
            Ext.getCmp('btnSaveAll').disable();
        }
    });
    
    function RefreshSectionStudentsData() {
        store_students.reload();
    }
    
    EduSectionStudentsWindow.show();
