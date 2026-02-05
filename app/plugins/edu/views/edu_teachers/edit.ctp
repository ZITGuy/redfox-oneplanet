//<script>
	<?php
		$this->ExtForm->create('EduTeacher');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduTeacherEditForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: {
            xtype:'tabpanel',
            activeTab: 0,
            height: 475,
            id: 'teacher_tabs',
            tabWidth: 185,
            defaults:{ bodyStyle:'padding:10px'}, 
            items:[{
                title:'Personal Information',
                layout:'form',
                id: 'personal_tab',
                defaultType: 'textfield',
                items: [{
                        xtype: 'compositefield',
                        fieldLabel: 'Full Name',
                        msgTarget: 'qtip',
                        anchor: '-20',
                        defaults: {
                            flex: 1
                        },
                        items: [
                            <?php
                                $options1_1 = array('anchor' => '90%', 
                                    'emptyText' => '[First Name]', 
                                    'id' => 'teacherName1'
                                );
                                $options1_1['listeners'] = "{
                                    scope: this,
                                    'blur': function(textField){
                                        var name = textField.getValue();
                                        textField.setValue(name.toUpperCase());
                                    }
                                }";
                                $this->ExtForm->input('teacher_name1', $options1_1);
                            ?>,
                            <?php
                                $options05_11 = array('anchor' => '90%', 
                                    'emptyText' => '[Middle Name]', 
                                    'id' => 'teacherName2');
                                $options05_11['listeners'] = "{
                                    scope: this,
                                    'blur': function(textField){
                                        var name = textField.getValue();
                                        textField.setValue(name.toUpperCase());
                                    }
                                }";
                                $this->ExtForm->input('teacher_name2', $options05_11);
                            ?>,
                            <?php
                                $options06 = array('anchor' => '90%', 
                                    'emptyText' => '[Last Name]', 
                                    'id' => 'teacherName3');
                                $options06['listeners'] = "{
                                    scope: this,
                                    'blur': function(textField){
                                        var name = textField.getValue();
                                        textField.setValue(name.toUpperCase());
                                    }
                                }";
                                $this->ExtForm->input('teacher_name3', $options06);
                            ?>
                        ]
                    },
                    <?php 
                        $options9 = array('anchor' => '45%');
                        $this->ExtForm->input('date_of_employment', $options9);
                    ?>,
                    <?php
                        $options_id_number = array('anchor' => '90%', 
                            'fieldLabel' => 'ID Number', 'value' => $next_identity, 
                            'id' => 'tidentity_number');
                        $this->ExtForm->input('tidentity_number', $options_id_number);
                    ?>, {
                        xtype: 'fieldset',
                        title: 'Address',
                        collapsible: true,
                        items: [{
                            layout: 'column',
                            labelWidth: 100,
                            items: [{
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options_city = array('anchor' => '90%', 
                                                'fieldLabel' => 'City', 
                                                'id' => 'city');
                                            $this->ExtForm->input('city', $options_city);
                                        ?>,
                                        <?php
                                            $options_subcity = array('anchor' => '90%', 
                                                'fieldLabel' => 'Subcity', 
                                                'id' => 'sub_city');
                                            $this->ExtForm->input('sub_city', $options_subcity);
                                        ?>,
                                        <?php
                                            $options_telephone_home = array('anchor' => '90%', 
                                                'fieldLabel' => 'Home Telephone', 
                                                'id' => 'telephone_home');
                                            $this->ExtForm->input('telephone_home', $options_telephone_home);
                                        ?>
                                    ]
                                }, {
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options_woreda = array('anchor' => '90%',  
                                                'fieldLabel' => 'Woreda', 
                                                'id' => 'woreda');
                                            $this->ExtForm->input('woreda', $options_woreda);
                                        ?>,
                                        <?php
                                            $options_house_number = array('anchor' => '90%', 
                                                'fieldLabel' => 'House Number', 
                                                'id' => 'house_number');
                                            $this->ExtForm->input('house_number', $options_house_number);
                                        ?>,
                                        <?php
                                            $options_telephone_mobile = array('anchor' => '90%', 
                                                'fieldLabel' => 'Mobile', 
                                                'id' => 'telephone_mobile');
                                            $this->ExtForm->input('telephone_mobile', $options_telephone_mobile);
                                        ?>
                                    ]
                                }
                            ]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Credentials',
                        collapsible: true,
                        items: [{
                            layout: 'column',
                            items: [{
                                columnWidth: 1.0,
                                layout: 'form',
                                items: [,
                                    <?php
                                        $options_email = array('anchor' => '95%', 'id' => 'teacherEmail');
                                        $this->ExtForm->input('email', $options_email);
                                    ?>,
                                    <?php 
                                        $options6 = array('anchor' => '80%', 'fieldLabel' => 'Campus');
                                        $options6['items'] = $edu_campuses;
                                        $options6['value'] = 1;
                                        $this->ExtForm->input('edu_campus_id', $options6);
                                    ?>
                                ]
                            }]
                        }]
                    }
                ]
            }, {
                title:'Remarks',
                layout:'form',
                id: 'remarks_tab',
                disabled: true,
                defaultType: 'textfield',
                items: [
                    <?php
						$options_remark = array('anchor' => '95%', 'xtype' => 'textarea', 'height' => '90%');
						$this->ExtForm->input('remark', $options_remark);
					?>
                ]
            }]
        }
	});
		
	var EduTeacherEditWindow = new Ext.Window({
		title: '<?php __('Edit Teacher'); ?>',
		width: 700,
        height: 540,
        layout: 'fit',
        modal: true,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduTeacherEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduTeacherEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Teacher.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduTeacherEditWindow.collapsed)
					EduTeacherEditWindow.expand(true);
				else
					EduTeacherEditWindow.collapse(true);
			}
		}],
		buttons: [{
            text: '<?php __('Back'); ?>',
            disabled: true,
            id: 'back',
            handler: function(btn) {
                if (activetab == 2) {
                    Ext.getCmp('personal_tab').enable();
                    Ext.getCmp('remarks_tab').disable();
                    Ext.getCmp('next').setText('Next');
                    Ext.getCmp('teacher_tabs').setActiveTab(Ext.getCmp('personal_tab'));
                    Ext.getCmp('back').disable();
                    activetab = 1;
                }

            }
        }, {
            text: '<?php __('Next'); ?>',
            id: 'next',
            handler: function(btn) {
                if (activetab == 2) { // if "Finish"
                    if(!EduTeacherEditForm.getForm().isValid()){
                        Ext.Msg.alert(
                            "<?php __('Ooops!'); ?>", 
                            "<?php __('Some of the items should not be left blank'); ?>"
                        );
                        return;
                    }
                    EduTeacherEditForm.getForm().submit({
                        method: 'POST',
                        waitMsg: '<?php __('Submitting your data...'); ?>',
                        waitTitle: '<?php __('Wait Please...'); ?>',
                        success: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Success'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
                            });
							EduTeacherEditWindow.close();
							RefreshEduTeacherData();
                        },
                        failure: function(f, a) {
                            Ext.Msg.show({
                                title: '<?php __('Oooops!'); ?>',
                                buttons: Ext.MessageBox.OK,
                                msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
                            });
                        }
                    });
                }
                if (activetab == 1) {
                    Ext.getCmp('back').enable();
                    Ext.getCmp('personal_tab').disable();
                    Ext.getCmp('remarks_tab').enable();
                    Ext.getCmp('teacher_tabs').setActiveTab(Ext.getCmp('remarks_tab'));
                    Ext.getCmp('next').setText('Finish');
                    activetab = 2;
                }
            }
        }, {
            text: '<?php __('Cancel'); ?>',
            handler: function(btn) {
                EduTeacherEditWindow.close();
            }
        }]
	});
