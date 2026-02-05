//<script>
    <?php
		$associated_subjects = array();
		foreach ($edu_department['EduSubject'] as $subject) {
			$associated_subjects[$subject['id']] = $subject['name'];
		}
		$associated_classes = array();
		foreach ($edu_department['EduClass'] as $class) {
			$associated_classes[$class['id']] = $class['name'];
		}
        $this->ExtForm->create('EduDepartment');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var EduDepartmentAssociateForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 150,
        isUpload: true,
        fileUpload: true,
        labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
        url: '<?php echo $this->Html->url(array(
			'controller' => 'edu_departments', 'action' => 'associate')); ?>',
        defaultType: 'textfield',

        items: [<?php $this->ExtForm->input('id', array('hidden' => $edu_department['EduDepartment']['id'])); ?>,
				{
					xtype: 'fieldset',
					title: 'Class Association',
					collapsible: false,
					items: [
						new Ext.form.CheckboxGroup({
							id:'departmentClasses',
							xtype: 'checkboxgroup',
							fieldLabel: 'Select Classes',
							itemCls: 'x-check-group-alt',
							columns: 4,
							items: [
								
<?php
							$st = false;
							foreach ($classes as $key => $value) {
								if ($st) { echo ","; }

								echo "{\n";
								echo "    boxLabel: '" . Inflector::humanize($value) . "',\n";
								echo "    name: 'data[EduClass][" . $key . "]'\n";
								echo isset($associated_classes[$key])? ",    checked: 'true'\n": "";
								echo "}\n";

								$st = true;
							}
?>
							]
						})
					]
				}, {
                        xtype: 'fieldset',
                        title: 'Subject Association',
                        collapsible: false,
                        items: [
							new Ext.form.CheckboxGroup({
								id:'teacherSubjects',
								xtype: 'checkboxgroup',
								fieldLabel: 'Select Subjects',
								itemCls: 'x-check-group-alt',
								columns: 4,
								items: [
<?php
									echo "{\n";
									echo "    boxLabel: 'None',\n";
									echo "    name: 'data[EduSubject][None]',\n";
									echo "    checked: 'true'\n";
									echo "},\n";
								
								$st = false;
								foreach ($subjects as $key => $value) {
									if ($st) { echo ","; }

									echo "{\n";
									echo "    boxLabel: '" . Inflector::humanize($value) . "',\n";
									echo "    name: 'data[EduSubject][" . $key . "]'\n";
									echo isset($associated_subjects[$key])? ",    checked: 'true'\n": "";
									echo "}\n";

									$st = true;
								}
?>
								]
							})
						]
				}
			]
    });
    
    var activetab = 1;
    
    var EduDepartmentAssociateWindow = new Ext.Window({
        title: '<?php __('Associate Department'); ?>',
        width: 800,
        height: 500,
        layout: 'fit',
        modal: true,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduDepartmentAssociateForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduDepartmentAssociateForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to associate a Department with classes and subjects.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduDepartmentAssociateWindow.collapsed)
                    EduDepartmentAssociateWindow.expand(true);
                else
                    EduDepartmentAssociateWindow.collapse(true);
            }
        }],
        buttons: [{
            text: '<?php __('Save'); ?>',
            id: 'next',
            handler: function(btn) {
                if(!EduDepartmentAssociateForm.getForm().isValid()){
					Ext.Msg.alert(
						"<?php __('Oops!'); ?>", 
						"<?php __('Some of the items should not be left blank'); ?>"
					);
					return;
				}
				EduDepartmentAssociateForm.getForm().submit({
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
						EduDepartmentAssociateWindow.close();
						RefreshEduDepartmentData();
					},
					failure: function(f, a) {
						Ext.Msg.show({
							title: '<?php __('Oops!'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.errormsg,
							icon: Ext.MessageBox.ERROR
						});
					}
				});
            }
        }, {
            text: '<?php __('Close'); ?>',
            handler: function(btn) {
                EduDepartmentAssociateWindow.close();
            }
        }]
    });