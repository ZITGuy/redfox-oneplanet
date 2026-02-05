//<script>
	<?php
		$this->ExtForm->create('EduReceipt');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduReceiptAddForm = new Ext.form.FormPanel({
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
			'controller' => 'eduReceipts', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array();
				$this->ExtForm->input('invoice_number', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('invoice_date', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('crm_number', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('parent_name', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('parent_address', $options);
			?>,
			<?php
				$options = array();
				if (isset($parent_id)) {
					$options['hidden'] = $parent_id;
				} else {
					$options['items'] = $edu_students;
				}
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('student_name', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('student_number', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('student_class', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('student_section', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('student_academic_year', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('total_before_tax', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('total_after_tax', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('VAT', $options);
			?>,
			<?php
				$options = array();
				$this->ExtForm->input('TOT', $options);
			?>
		]
	});
	
	var EduReceiptAddWindow = new Ext.Window({
		title: '<?php __('Add Receipt'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduReceiptAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduReceiptAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Edu Receipt.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduReceiptAddWindow.collapsed)
					EduReceiptAddWindow.expand(true);
				else
					EduReceiptAddWindow.collapse(true);
			}
		}],
		buttons: [  {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduReceiptAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduReceiptAddForm.getForm().reset();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduReceiptData();
<?php } else { ?>
						RefreshEduReceiptData();
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
				EduReceiptAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduReceiptAddWindow.close();
<?php if (isset($parent_id)) { ?>
						RefreshParentEduReceiptData();
<?php } else { ?>
						RefreshEduReceiptData();
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
				EduReceiptAddWindow.close();
			}
		}]
	});
