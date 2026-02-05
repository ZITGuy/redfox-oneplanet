//<script>
    <?php
        $this->ExtForm->create('EduRegistration');
        $this->ExtForm->defineFieldFunctions();
    ?>
    
    function SearchStudent() {
        EduRegistrationDetainForm.el.mask('Please wait', 'x-mask-loading');
        var search_box = Ext.getCmp('txt_search_student');
        if(search_box.getValue() == '') {
            Ext.Msg.show({
                title: "<?php __('Ooops!'); ?>",
                buttons: Ext.MessageBox.OK,
                msg: "<?php __('You cannot search by empty text'); ?>: ",
                icon: Ext.MessageBox.ERROR
            });
        } else {
            var edu_student = Ext.getCmp('data[EduRegistration][id]');
            edu_student.setValue('');
            edu_student.store.removeAll();
            edu_student.store.reload({
                params: {
                    query: search_box.getValue(),
                    limit: 1000
                }
            });
        }
        EduRegistrationDetainForm.el.unmask();
    }
	
    var store_students = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registrations', 'action' => 'list_detainment_students')); ?>'
        }),
        listeners: {
            'load': function(s, records, options) {
                if(s.getTotalCount() == 0){
                    Ext.Msg.show({
                        title: "<?php __('Ooops!'); ?>",
                        buttons: Ext.MessageBox.OK,
                        msg: "<?php __('No record found'); ?>: ",
                        icon: Ext.MessageBox.ERROR
                    });
                }
                EduRegistrationDetainForm.el.unmask();
            }
        }
    });
	
    var store_classes = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registrations', 'action' => 'list_registration_classes')); ?>'
        }),
        listeners: {
            'load': function(s, records, options) {
                if(s.getTotalCount() == 0) {
                    Ext.Msg.show({
                        title: "<?php __('Ooops!'); ?>",
                        buttons: Ext.MessageBox.OK,
                        msg: "<?php __('No record found'); ?>: ",
                        icon: Ext.MessageBox.ERROR
                    });
                }
                EduRegistrationDetainForm.el.unmask();
            }
        }
    });
	
    var selected_student_id = 0;
	
    var EduRegistrationDetainForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 150,
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
            'controller' => 'edu_registrations', 'action' => 'detain_student')); ?>',
        defaultType: 'textfield',

        items: [{
                xtype: 'compositefield',
                labelWidth: 120,
                fieldLabel: 'Search Student',
                items:[
                    <?php
                        $options = array('emptyText' => 'By Student ID or Name', 'id' => 'txt_search_student');
                        $options['listeners'] = "{
                            specialkey: function(field, e){
                                if (e.getKey() == e.ENTER) {
                                    SearchStudent();
                                }
                            }
                        }";
                        $this->ExtForm->input('search_student', $options);
                    ?>, {
                        xtype: 'button',
                        text: '<?php __('Search...'); ?>',
                        handler: function(btn){
                            SearchStudent();
                        }
                    }
                ]
            }, {
                xtype: 'combo',
                id: 'data[EduRegistration][id]',
                name: 'data[EduRegistration][id]',
                store : store_students,
                displayField : 'name',
                valueField : 'id',
                anchor:'97%',
                fieldLabel: 'Student',
                disableKeyFilter : true,
                allowBlank: false,
                typeAhead: true,
                emptyText: '[Select One]',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        sel_id = combo.getValue();
                        Ext.getCmp('data[EduRegistration][edu_student_id]').setValue(sel_id);

                        Ext.getCmp('btnSave').enable();
                        Ext.getCmp('btnSaveAndClose').enable();
                    }
                }
            },
            <?php
                $options4 = array('anchor' => '50%', 'xtype' => 'combo',
                    'items' => array('P' => 'Promoted', 'N' => 'Detained'),
                    'fieldLabel' => 'Status',
                    'value' => 'N');
                $this->ExtForm->input('status', $options4);
            ?>,
            <?php
                $options = array('id' => 'data[EduRegistration][edu_student_id]');
                $options['hidden'] = 0;
                $this->ExtForm->input('edu_student_id', $options);
            ?>,
            <?php
                $options5 = array('anchor' => '80%',
                    'fieldLabel' => 'Remark',
                    'value' => 'NA');
                $this->ExtForm->input('remark', $options5);
            ?>
		]
	});
		
	var EduRegistrationDetainWindow = new Ext.Window({
		title: '<?php __('Student Detainment Form'); ?>',
		width: 650,
		minWidth: 600,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduRegistrationDetainForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduRegistrationDetainForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is for detaining a student from promotion.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduRegistrationAddWindow.collapsed)
					EduRegistrationDetainWindow.expand(true);
				else
					EduRegistrationDetainWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
            id: 'btnSave',
            disabled: true,
			handler: function(btn){
				EduRegistrationDetainForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});

                        Ext.getCmp('btnSave').disable();
                        Ext.getCmp('btnSaveAndClose').disable();
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
		}, {
			text: '<?php __('Save & Close'); ?>',
            id: 'btnSaveAndClose',
            disabled: true,
			handler: function(btn){
				EduRegistrationDetainForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduRegistrationDetainWindow.close();
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
				EduRegistrationDetainWindow.close();
			}
		}]
	});
	
	EduRegistrationDetainWindow.show();
	