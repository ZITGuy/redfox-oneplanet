//<script>
	<?php
		$this->ExtForm->create('EduLessonPlanItem');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduLessonPlanItemEditForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain: true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array(
			'controller' => 'eduLessonPlanItems', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_lesson_plan_item['EduLessonPlanItem']['id'])); ?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_lesson_plans;
				}
				$options['value'] = $edu_lesson_plan_item['EduLessonPlanItem']['edu_lesson_plan_id'];
				$this->ExtForm->input('edu_lesson_plan_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_periods;
				$options['value'] = $edu_lesson_plan_item['EduLessonPlanItem']['edu_period_id'];
				$this->ExtForm->input('edu_period_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_days;
				$options['value'] = $edu_lesson_plan_item['EduLessonPlanItem']['edu_day_id'];
				$this->ExtForm->input('edu_day_id', $options);
			?>,
			<?php
				$options = array();
				$options['items'] = $edu_outlines;
				$options['value'] = $edu_lesson_plan_item['EduLessonPlanItem']['edu_outline_id'];
				$this->ExtForm->input('edu_outline_id', $options);
			?>
		]
	});
	
	var EduLessonPlanItemEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Lesson Plan Item'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduLessonPlanItemEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduLessonPlanItemEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Lesson Plan Item.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduLessonPlanItemEditWindow.collapsed)
					EduLessonPlanItemEditWindow.expand(true);
				else
					EduLessonPlanItemEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduLessonPlanItemEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduLessonPlanItemEditWindow.close();
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
				EduLessonPlanItemEditWindow.close();
			}
		}]
	});