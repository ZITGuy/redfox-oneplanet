//<script>
    var store_sections = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'students', 'homeroom', 'homeroom2'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_sections', 'action' => 'list_data2', $class['EduClass']['id'])); ?>'
        })
    });

    var element = document.createElement('div');
    element.id = "teacherCombo";
    document.body.appendChild(element);
    document.getElementById("teacherCombo").innerHTML=
        '<select name="teachers" id="teachers" style="display:none;"><option value="TBA">TBA</option><?Php
        $ts = array();
        foreach ($teachers as $teacher) {
            $p = $teacher['User']['Person'];
            $ts[$p['first_name'].' '.$p['middle_name'].' '.$p['last_name'] . ': '.
                $teacher['EduTeacher']['identity_number']] = $p['first_name'].' '.
                $p['middle_name'].' '.$p['last_name'].': '.$teacher['EduTeacher']['identity_number'];
        }
        ksort($ts);
        foreach ($ts as $k => $v) {
            echo '<option value="' . $k . '">' . $v . '</option>';
        }
    ?></select>';

    var element2 = document.createElement('div');
    element2.id = "teacherCombo2";
    document.body.appendChild(element2);
    document.getElementById("teacherCombo2").innerHTML=
        '<select name="teachers2" id="teachers2" style="display:none;"><option value="TBA">TBA</option><?Php
        $ts = array();
        foreach ($teachers as $teacher) {
            $p = $teacher['User']['Person'];
            $ts[$p['first_name'].' '.$p['middle_name'].' '.$p['last_name'] . ': '.
                $teacher['EduTeacher']['identity_number']] = $p['first_name'].' '.
                $p['middle_name'].' '.$p['last_name'].': '.$teacher['EduTeacher']['identity_number'];
        }
        ksort($ts);
        foreach ($ts as $k => $v) {
            echo '<option value="' . $k . '">' . $v . '</option>';
        }
    ?></select>';

    function AddParentEduSection() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array(
                'controller' => 'eduSections', 'action' => 'add')); ?>/<?php echo $class['EduClass']['id']; ?>",
            success: function(response, opts) {
                var eduSection_data = response.responseText;
                eval(eduSection_data);
                EduSectionAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>",
                    "<?php __('Cannot get the eduSection add form. Error code'); ?>: " + response.status);
            }
        });
    }

    var fm = Ext.form;
    
    var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [{
            id: 'name',
            header: 'Section Name',
            dataIndex: 'name',
            width: 100,
            sortable: false,
            editor: new fm.TextField({
                allowBlank: false
            })
        }, {
            header: '# of Students',
            dataIndex: 'students',
            width: 130,
            align: 'right',
            sortable: false
        }, {
            id: 'homeroom',
            header: 'Homeroom Teacher',
            dataIndex: 'homeroom',
            width: 170,
            sortable: false,
            editor: new fm.ComboBox({
                triggerAction: 'all',
                forceSelection: true,
                transform: 'teachers',
                lazyRender: true,
                listClass: 'x-combo-list-small'
            })
        }, {
            id: 'homeroom2',
            header: 'Homeroom Teacher 2',
            dataIndex: 'homeroom2',
            width: 170,
            sortable: false,
            editor: new fm.ComboBox({
                triggerAction: 'all',
                forceSelection: true,
                transform: 'teachers2',
                lazyRender: true,
                listClass: 'x-combo-list-small'
            })
        }]
    });
    
    var sections_grid = new Ext.grid.EditorGridPanel({
        store: store_sections,
        cm: cm,
        width: 624,
        height: 288,
        frame: true,
        clicksToEdit: 2,
        loadMask: true,
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        tbar: [{
            xtype: 'tbbutton',
            text: '<?php __('Add'); ?>',
            id: 'add-parent-eduSection',
            tooltip:'<?php __('<b>Add Section</b><br />Click here to add a Section'); ?>',
            icon: 'img/table_add.png',
            cls: 'x-btn-text-icon',
            handler: function(btn) {
                AddParentEduSection();
            }
        }, '|', {
            text: 'Save All',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                var records = store_sections.getRange();
                var param = {};
                for(var i = 0; i < records.length; i++) {
                    param['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    param['data['+i+'][name]'] = Ext.encode(records[i].get('name'));
                    param['data['+i+'][homeroom]'] = Ext.encode(records[i].get('homeroom'));
                    param['data['+i+'][homeroom2]'] = Ext.encode(records[i].get('homeroom2'));
                }
                
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array(
                        'controller' => 'edu_sections', 'action' => 'save_changes')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Sections updated successfully!'); ?>");
                        RefreshSectionsData();
                    },
                    failure: function(){
                        alert('Error Saving Changes, Please Try Again!');
                    }
                });
                
            }
        }, ' ', '|', ' ', {
            id: 'btnStudents',
            disabled: true,
            text: 'Students',
            handler : function(){
                var sm = sections_grid.getSelectionModel();
                var sel = sm.getSelected();
                if (sm.hasSelection()) {
                    OpenSectionStudents(sel.data.id);
                }
            }
        }, ' ', '|', ' ', {
            text: 'Remove All',
            id: 'btnRemoveAll',
            handler : function(){
                Ext.Msg.show({
                    title: "<?php __('Confirm'); ?>",
                    buttons: Ext.MessageBox.YESNO,
                    msg: "<?php __('Are you sure to delete the sections all together?'); ?>",
                    icon: Ext.MessageBox.QUESTION,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            DeleteAllSections();
                        }
                    }
                });
            }
        }, ' ', '|', ' ', {
            text: 'Refresh List',
            id: 'btnrRefresh',
            handler : function(){
                RefreshSectionsData();
            }
        }, ' ', '|', ' ', {
            text: 'Exemptions',
            id: 'btnAddExcemption',
            handler : function() {
                var sm = sections_grid.getSelectionModel();
                var sel = sm.getSelected();
                if (sm.hasSelection()) {
                    AddExemption(sel.data.id);
                }
            }
        }]
    });
    
    sections_grid.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        sections_grid.getTopToolbar().findById('btnStudents').enable();
    });
    sections_grid.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        if (this.getSelections().length > 0) {
            sections_grid.getTopToolbar().findById('btnStudents').enable();
        } else {
            sections_grid.getTopToolbar().findById('btnStudents').disable();
        }
    });
    
    sections_grid.on('afteredit', afterEdit, this );

    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            sections_grid.getTopToolbar().findById('btnSaveAll').enable();
        }
    }

    var EduSectionDetailWindow = new Ext.Window({
        title: '<?php __('Sections Detail Maintenance for Grade'); ?>: <i><font color=green><?php echo $class['EduClass']['name']; ?></font></i>',
        width: 650,
        height: 365,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [
            sections_grid
        ],
        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                EduSectionDetailWindow.close();
            }
        }]
    });

    store_sections.load({
        params: {
            start: 0,          
            limit: 50
        }
    });
    
    store_sections.on('load', function(){
        if(store_sections.getRange().length < 1){
            Ext.getCmp('btnSaveAll').disable();
            Ext.getCmp('btnStudents').disable();
            Ext.getCmp('btnRemoveAll').disable();
        }
    });
    
    function DeleteAllSections() {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'delete_all', $class['EduClass']['id'])); ?>",
            success: function(response, opts) {
                Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('Sections successfully deleted!'); ?>");
                RefreshSectionsData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot delete the Sections. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function OpenSectionStudents(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'section_students')); ?>/"+id,
            success: function(response, opts) {
                var eduSectionStudents_data = response.responseText;
                eval(eduSectionStudents_data);
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Section Students form. Error code'); ?>: " + response.status);
            }
        });
    }
 
    function AddExemption(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'add_for_section')); ?>/"+id,
            success: function(response, opts) {
                var eduSectionStudents_data = response.responseText;
                eval(eduSectionStudents_data);

                EduExemptionAddForSectionWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Exemption for Section Students form. Error code'); ?>: " + response.status);
            }
        });
    }
    
    function RefreshSectionsData() {
        store_sections.reload();
    }
    
    EduSectionDetailWindow.show();