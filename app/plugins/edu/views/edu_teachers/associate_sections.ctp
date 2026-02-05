//<script>
    <?php
		$associated_sections = array();
		foreach($edu_teacher['EduSection'] as $section) {
			$associated_sections[$section['id']] = $section['name'];
		}
        $this->ExtForm->create('EduTeacher');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var EduTeacherSectionAssociateForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 150,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'edu_teachers', 'action' => 'associate_sections')); ?>',
        defaultType: 'textfield',

        items: [<?php $this->ExtForm->input('id', array('hidden' => $edu_teacher['EduTeacher']['id'])); ?>,
				{
					xtype: 'fieldset',
					title: 'Section Association',
					collapsible: false,
					items: [
						new Ext.form.CheckboxGroup({
							id:'teacherSections',
							xtype: 'checkboxgroup',
							fieldLabel: 'Select Sections',
							itemCls: 'x-check-group-alt',
							columns: 4,
							items: [
								
		<?php
							$st = false;
							foreach($sections as $key => $value){
								if($st) echo ",";

								echo "{\n";
								echo "    boxLabel: '" . Inflector::humanize($value) . "',\n"; 
								echo "    name: 'data[EduSection][" . $key . "]'\n";
								echo (isset($associated_sections[$key])? ",    checked: 'true'\n": "");
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
    
    var EduTeacherSectionAssociateWindow = new Ext.Window({
        title: '<?php __('Associate Teacher with Sections'); ?>',
        width: 600,
        height: 250,
        layout: 'fit',
        modal: true,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        items: EduTeacherSectionAssociateForm,
        tools: [{
            id: 'refresh',
            qtip: 'Reset',
            handler: function () {
                EduTeacherSectionAssociateForm.getForm().reset();
            },
            scope: this
        }, {
            id: 'help',
            qtip: 'Help',
            handler: function () {
                Ext.Msg.show({
                    title: 'Help',
                    buttons: Ext.MessageBox.OK,
                    msg: 'This form is used to associate a Teacher with Sections.',
                    icon: Ext.MessageBox.INFO
                });
            }
        }, {
            id: 'toggle',
            qtip: 'Collapse / Expand',
            handler: function () {
                if(EduTeacherSectionAssociateWindow.collapsed)
                    EduTeacherSectionAssociateWindow.expand(true);
                else
                    EduTeacherSectionAssociateWindow.collapse(true);
            }
        }],
        buttons: [{
            text: '<?php __('Save'); ?>',
            id: 'next',
            handler: function(btn) {
                if(!EduTeacherSectionAssociateForm.getForm().isValid()){
					Ext.Msg.alert(
						"<?php __('Oops!'); ?>", 
						"<?php __('Some of the items should not be left blank'); ?>"
					);
					return;
				}
				EduTeacherSectionAssociateForm.getForm().submit({
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
						EduTeacherSectionAssociateWindow.close();
						RefreshEduTeacherData();
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
                EduTeacherSectionAssociateWindow.close();
            }
        }]
    });