//<script>
	<?php
		$this->ExtForm->create('EduSchedule');
		$this->ExtForm->defineFieldFunctions();
	?>
	var ds = new Ext.data.ArrayStore({
		data: [<?php foreach ($classes as $class) {
		echo "['".$class['EduSection']['id']."','".$class['EduClass']['name'].' '.$class['EduSection']['name']."'],";
	}?>],
	fields: ['value','text'],
	sortInfo: {
		field: 'text',
		direction: 'ASC'
	}
});
	var ScheduleGenerateForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'generate')); ?>',

		items: [
			<?php $this->ExtForm->input('edu_schedule_id', array('hidden' => $schedule_id)); ?>,
{
		xtype: 'itemselector',
		name: 'data[EduSchedule][list]',
		fieldLabel: 'Classes',
		imagePath: 'img/ux_images',
		initComponent: function() {
		Ext.apply( this, {
		drawUpIcon:false,
		drawDownIcon:false,
		drawLeftIcon:false,
		drawRightIcon:false,
		drawTopIcon:false,
		drawBotIcon:false,
		});
		},
		multiselects: [{
			width: 200,
			height: 200,
			store: ds,
			mode : 'local',
			disableKeyFilter : true,
			triggerAction: 'all',
			displayField: 'text',
			valueField: 'value',
			legend:'All'
		},{
			width: 200,
			height: 200,
			legend:'Selected',
			store: [['','']]
		}]
	}				]
	});
	
	var parentEduScheduleGenerateWindow = new Ext.Window({
		title: '<?php __('Generate Time Table'); ?>',
		width: 535,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: ScheduleGenerateForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				ScheduleGenerateForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Attendance Record.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(parentEduScheduleGenerateWindow.collapsed)
					parentEduScheduleGenerateWindow.expand(true);
				else
					parentEduScheduleGenerateWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Start'); ?>',
			handler: function(btn){
				ScheduleGenerateForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						var aw = new Ext.Window({
							title: 'Message',
							width: 330,
							minWidth: 330,
							autoHeight: true,
							layout: 'fit',
							modal: true,
							resizable: true,
							plain:true,
							buttons: [ {
								text:'Display',
								handler:function(){
									window.open('<?php echo $this->Html->url(array(
										'controller' => 'eduSchedules', 'action' => 'display')); echo '/'; echo $schedule_id; ?>');
								}
								},{
								text:'Apply',
								handler:function(){
									Ext.Ajax.request({
									url: '<?php echo $this->Html->url(array(
										'controller' => 'eduSchedules', 'action' => 'apply')); echo '/'; echo $schedule_id; ?>',
									success: function(response, opts) { },
									failure: function(response, opts) { }
									});
									aw.close();
								}
								},{
								text:'Cancel',
								handler:function(){
										Ext.Ajax.request({
										url: '<?php echo $this->Html->url(array('controller' => 'eduSchedules', 'action' => 'cancel')); ?>',
										success: function(response, opts) { },
										failure: function(response, opts) { }
										});
										aw.close();
									}
								}
							]
						});
						aw.show();
						aw.add({html:a.result.msg});
						aw.doLayout();
						
						parentEduScheduleGenerateWindow.close();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				parentEduScheduleGenerateWindow.close();
			}
		}]
	});
