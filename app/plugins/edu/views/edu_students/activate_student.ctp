//<script>
    <?php
        $this->ExtForm->create('EduStudent');
        $this->ExtForm->defineFieldFunctions();
    ?>
    var store_students = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'edu_student_id', 'full_name', 'grade', 'status', 'photo'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'search_students')); ?>'
        }),
        sortInfo:{field: 'full_name', direction: "ASC"}
    });
    
    store_students.load({
        params: {
            start: 0
        }
    });
    
    var EduStudentActivationForm = new Ext.form.FormPanel({
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
        url:'<?php echo $this->Html->url(array('controller' => 'edu_students', 'action' => 'activate_student')); ?>',
        defaultType: 'textfield',

        items: [{
                msgTarget: 'under',
                allowAddNewData: true,
                id:'students',
                height: 300,
                forceSelection: true,
                xtype:'superboxselect',
                fieldLabel: 'Students',
                emptyText: 'Enter or select Student(s)',
                resizable: true,
                name: 'data[EduStudent][students][]',
                anchor:'100%',
                store: store_students,
                mode: 'local',
                displayField: 'full_name',
                valueField: 'user_id',
                tpl: '<tpl for="."><img src="{photo}" style="float:left;height:35px"/>'+
                    '<div ext:qtip="{full_name} . {grade}" class="x-combo-list-item">{full_name} '+
                    '<br><b>{grade} | {status}</b></div></tpl>',
                extraItemCls: 'x-tag',
                listeners: {
                    beforeadditem: function(bs,v){
                    },
                    additem: function(bs,v){
                    },
                    beforeremoveitem: function(bs,v){
                        //console.log('beforeremoveitem:', v);
                        //return false;
                    },
                    removeitem: function(bs,v){
                        //console.log('removeitem:', v);
                    },
                    newitem: function(bs,v){
                        v = v.slice(0,1).toUpperCase() + v.slice(1).toLowerCase();
                        var newObj = {
                            id: v,
                            name: v
                        };
                        bs.addItem(newObj);
                    }
                }
            },
            <?php
                $options = array('xtype' => 'datefield', 'anchor' => '33%', 'format' => 'Y-m-d',
			'value' => date('Y-m-d'));
                $this->ExtForm->input('effective_from', $options);
            ?>,
            <?php
                $options_reason = array('fieldLabel' => 'Activation Reason', 'xtype' => 'textarea');
                $this->ExtForm->input('reason', $options_reason);
            ?>
        ]
    });
		
    var EduStudentActivationWindow = new Ext.Window({
        title: '<?php __('Activate Student(s)'); ?>',
        width: 700,
        minWidth: 700,
        autoHeight: true,
        layout: 'fit',
        modal: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: EduStudentActivationForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduStudentActivationForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to insert a new Student.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduStudentActivationWindow.collapsed)
                        EduStudentActivationWindow.expand(true);
                else
                        EduStudentActivationWindow.collapse(true);
            }
        }],
        buttons: [{
                text: '<?php __('Activate'); ?>',
                handler: function(btn){
                    EduStudentActivationForm.getForm().submit({
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f,a){
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
                            EduStudentActivationWindow.close();
                        },
                        failure: function(f,a){
                            Ext.Msg.show({
                                title: '<?php __('Warning'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
                            });
                        }
                    });
                }
            },{
                text: '<?php __('Cancel'); ?>',
                handler: function(btn){
                    EduStudentActivationWindow.close();
                }
            }]
    });
    
    EduStudentActivationWindow.show();