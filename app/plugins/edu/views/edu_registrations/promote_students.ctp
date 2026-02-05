//<script>
    <?php
        $this->ExtForm->create('EduAssessment');
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
			url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data_for_promotion')); ?>'
		})
	});

	var PromoteStudentsForm = new Ext.form.FormPanel({
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
		url: '<?php echo $this->Html->url(array('controller' => 'edu_registrations', 'action' => 'promote_students')); ?>',
		items: [
            <?php
				$options = array('fieldLabel' => 'Class');
				$options['items'] = $edu_classes;
				$options['listeners'] = "{
					scope: this,
					'select': function(combo, record, index){
						var edu_section_id = Ext.getCmp('data[EduRegistration][edu_section_id]');
						edu_section_id.setValue('');
						edu_section_id.store.removeAll();
						edu_section_id.store.reload({
							params: {
								edu_class_id : combo.getValue()
							}
						});

                        Ext.getCmp('btnPromoteAll').disable();
					}
				}";
                $options['anchor'] = '85%';
				$this->ExtForm->input('edu_class_id', $options);
            ?>, {
                xtype: 'combo',
                emptyText: 'All',
                name: 'data[EduRegistration][edu_section_id]',
                hiddenName: 'data[EduRegistration][edu_section_id]',
                id:'data[EduRegistration][edu_section_id]',
                typeAhead: true,
                store : store_sections,
                displayField : 'name',
                valueField : 'id',
                anchor:'85%',
                fieldLabel: '<span style="color:red;">*</span> Section',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Section',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        Ext.getCmp('btnPromoteAll').enable();
                    }
                }
            }
        ]
    });
    
    var PromoteStudentsWindow = new Ext.Window({
        title: 'Promote Evaluation Value Students',
        width: 400,
        autoHeight: true,
        resizable: true,
        plain: true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: [
            PromoteStudentsForm
        ],

        buttons: [{
            text: 'Promote All',
            id: 'btnPromoteAll',
            //disabled: true,
            handler : function(){
                Ext.Msg.show({
                    title: "<?php __('Promote Evaluation Value Students?'); ?>",
                    buttons: Ext.MessageBox.YESNO,
                    msg: "<?php __('Are you sure to promote all Students in the selected section?'); ?>",
                    icon: Ext.MessageBox.QUESTION,
                    fn: function (btn) {
                        if (btn == 'yes') {
                            PromoteStudentsForm.getForm().submit({
                                waitMsg: '<?php __('Submitting your data...'); ?>',
                                waitTitle: '<?php __('Wait Please...'); ?>',
                                success: function(f,a){
                                    Ext.Msg.show({
                                        title: '<?php __('Success'); ?>',
                                        buttons: Ext.MessageBox.OK,
                                        msg: a.result.msg,
                                        icon: Ext.MessageBox.INFO
                                    });
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
                    }
                });
            }
        }, {
            text: 'Close',
            handler: function(btn){
                PromoteStudentsWindow.close();
            }
        }]
    });
    PromoteStudentsWindow.show();
