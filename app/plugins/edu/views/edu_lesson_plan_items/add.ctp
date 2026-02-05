//<script>
	<?php
		$this->ExtForm->create('EduLessonPlanItem');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduLessonPlanItemAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array(
			'controller' => 'eduLessonPlanItems', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_lesson_plans;
				}
				$this->ExtForm->input('edu_lesson_plan_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_periods;
				$this->ExtForm->input('edu_period_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_days;
				$this->ExtForm->input('edu_day_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_outlines;
				$this->ExtForm->input('edu_outline_id', $options);
			?>
		]
	});
	
	var EduLessonPlanItemAddWindow = new Ext.Window({
		title: '<?php __('Add Lesson Plan Item'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduLessonPlanItemAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduLessonPlanItemAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Lesson Plan Item.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduLessonPlanItemAddWindow.collapsed)
					EduLessonPlanItemAddWindow.expand(true);
				else
					EduLessonPlanItemAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduLessonPlanItemAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduLessonPlanItemAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduLessonPlanItemData();
<?php } else { ?>
						RefreshEduLessonPlanItemData();
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
				EduLessonPlanItemAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduLessonPlanItemAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduLessonPlanItemData();
<?php } else { ?>
						RefreshEduLessonPlanItemData();
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
				EduLessonPlanItemAddWindow.close();
			}
		}]
	});