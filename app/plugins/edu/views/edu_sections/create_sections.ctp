//<script>
	<?php
		$this->ExtForm->create('EduSection');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduSectionCreateForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 160,
		labelAlign: 'right',
		url:'<?php echo $this->Html->url(array('controller' => 'eduSections', 'action' => 'create_sections')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('fieldLabel' => 'Class/Grade', 'id' => 'data[EduSection][edu_class_id]');
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_classes;
				}
				$options['listeners'] = '{
					select: function(fld){
						var r = CheckValidity();
						if(r){
							Ext.getCmp(\'btnCreate\').enable();
						} else {
							Ext.getCmp(\'btnCreate\').disable();
						}
					}
				}';
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Section Size', 'anchor'=>'60%', 'triggerAction' => 'all');
				$options['listeners'] = '{
					blur: function(fld){
						//alert(new_v);
						/*var number_of_sections = Ext.getCmp(\'data[EduSection][edu_number_of_sections]\');
						if(new_v > 0){
							number_of_sections.disable();
						} else {
							number_of_sections.enable();
						}*/
					}
				}';
				$this->ExtForm->input('edu_section_size', $options);
			?>,
			<?php
				$options = array('fieldLabel' => 'Number of Sections',
					'anchor'=>'60%', 'id' => 'data[EduSection][edu_number_of_sections]');
				$this->ExtForm->input('edu_number_of_sections', $options);
			?>
		]
	});

	
	var EduSectionCreateWindow = new Ext.Window({
		title: '<?php __('Create Section'); ?>',
		width: 550,
		minWidth: 500,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduSectionCreateForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduSectionCreateForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to create Sections for a class of students in given academic year.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduSectionCreateWindow.collapsed)
					EduSectionCreateWindow.expand(true);
				else
					EduSectionCreateWindow.collapse(true);
			}
		}],
		buttons: [{
				text: '<?php __('Create'); ?>',
				disabled: true,
				id: 'btnCreate',
				handler: function(btn){
					EduSectionCreateForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
								icon: Ext.MessageBox.INFO
							});
							EduSectionCreateWindow.close();
							// open Sections detail form window.
							// to edit the created sections
							
							OpenSectionsDetail();
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
					EduSectionCreateWindow.close();
				}
		}]
	});
	
	EduSectionCreateWindow.show();
			
	function OpenSectionsDetail() {
		Ext.Ajax.request({
			url: "<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'sections_detail')); ?>",
			success: function(response, opts) {
				var eduSection_data = response.responseText;
				eval(eduSection_data);
			},
			failure: function(response, opts) {
				Ext.Msg.alert("<?php __('Error'); ?>",
					"<?php __('Cannot get the Section Detail form. Error code'); ?>: " + response.status);
			}
		});
	}

	function CheckValidity(){
		var class_selected = Ext.getCmp('data[EduSection][edu_class_id]').getValue();

		switch(class_selected) {
			<?php
			foreach ($messages as $k => $m) {
				echo "case '$k':\n";
				echo "\tShowErrorBox('$m', 'ERR-0001');\n";
				echo "\treturn false;\n";
			}
			?>
			default:
				return true;
		}
		
	}
