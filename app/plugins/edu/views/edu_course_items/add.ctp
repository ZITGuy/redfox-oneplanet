//<script>
	<?php
		$this->ExtForm->create('EduCourseItem');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduCourseItemAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduCourseItems', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'htmleditor',
					'height' => 100,
					'anchor' => '95%',
					'enableFont' => false,
					'enableFontSize' => false,
					'enableLinks' => false
				);
				$this->ExtForm->input('description', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_courses;
				}
				$this->ExtForm->input('edu_course_id', $options);
			?>,
			<?php
				$options = array('anchor' => '50%', 'maskRe' => '/[0-9.]/');
				$this->ExtForm->input('max_mark', $options);
			?>
		]
	});
	
	var EduCourseItemAddWindow = new Ext.Window({
		title: '<?php __('Add Course Item'); ?>',
		width: 500,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduCourseItemAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduCourseItemAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Course Item.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduCourseItemAddWindow.collapsed)
					EduCourseItemAddWindow.expand(true);
				else
					EduCourseItemAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduCourseItemAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduCourseItemAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
						RefreshParentEduCourseItemData();
<?php } else { ?>
						RefreshEduCourseItemData();
<?php } ?>
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
			handler: function(btn){
				EduCourseItemAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduCourseItemAddWindow.close();
<?php if(isset($parent_id)){ ?>
						RefreshParentEduCourseItemData();
<?php } else { ?>
						RefreshEduCourseItemData();
<?php } ?>
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
				EduCourseItemAddWindow.close();
			}
		}]
	});
