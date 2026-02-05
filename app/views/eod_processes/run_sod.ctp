//<script>
	<?php
		$this->ExtForm->create('EodProcess');
		$this->ExtForm->defineFieldFunctions();
	?>
		
	var SodProcessRunForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'<?php echo $this->Html->url(array('controller' => 'eod_processes', 'action' => 'run_sod')); ?>',
		defaultType: 'textfield',

		items: [
			<?php 
				$options = array('fieldLabel' => 'Last EoD');
				$options['value'] = $name;
				$options['readOnly'] = true;
				$this->ExtForm->input('name', $options);
			?>,
			<?php
				$options = array(
                    'anchor' => '70%', 
                    'xtype' => 'datefield',
                    'disabledDays' => '[0, 6]',
					'disabledDaysText' => 'This is weekend',
					'format' => 'Y-m-d',
					'minValue' => "'" . $today_date . "'",
					'maxValue' => "'" . $quarter['EduQuarter']['end_date'] . "'",
                    'fieldLabel' => 'Today\\\'s Date',
                    'value' => $today_date
                );
				if($holidays != '') {
					$options['disabledDates'] = '[' . $holidays . ']';
				}
				$this->ExtForm->input('todays_date', $options);
			?>,
			<?php 
				$options = array('xtype' => 'textarea', 'fieldLabel' => 'Remark');
				$options['value'] = 'NA';
				$options['disabled'] = true;
				$this->ExtForm->input('remark', $options);
			?>
		]
	});
	
	var SodProcessRunWindow = new Ext.Window({
		title: '<?php __('Start of Day Process'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: SodProcessRunForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				SodProcessRunForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Eod Process.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(SodProcessRunWindow.collapsed)
					SodProcessRunWindow.expand(true);
				else
					SodProcessRunWindow.collapse(true);
			}
		}],
		buttons: [{
			text: '<?php __('Run'); ?>',
			handler: function(btn) {
				Ext.MessageBox.confirm(
					'Confirm', 
					'Are you sure to run SoD?', 
					function(btn){
						if(btn == 'yes') {
							SodProcessRunForm.getForm().submit({
								waitMsg: '<?php __('Submitting your data...'); ?>',
								waitTitle: '<?php __('Wait Please...'); ?>',
								success: function(f,a){
									Ext.Msg.show({
										title: '<?php __('Success'); ?>',
										buttons: Ext.MessageBox.OK,
										msg: a.result.msg,
										icon: Ext.MessageBox.INFO,
										fn: function(btn){
											if (btn == 'ok'){
												location.reload();
											}
										}
									});
									SodProcessRunWindow.close();
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
				);
			}
		}, {
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				SodProcessRunWindow.close();
			}
		}]
	});
	
	SodProcessRunWindow.show();