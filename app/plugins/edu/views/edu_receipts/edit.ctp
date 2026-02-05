//<script>
	<?php
		$this->ExtForm->create('EduReceipt');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduReceiptEditForm = new Ext.form.FormPanel({
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
			'controller' => 'eduReceipts', 'action' => 'edit')); ?>',
		defaultType: 'textfield',

		items: [
			<?php $this->ExtForm->input('id', array('hidden' => $edu_receipt['EduReceipt']['id'])); ?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['invoice_number'];
				$this->ExtForm->input('invoice_number', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['invoice_date'];
				$this->ExtForm->input('invoice_date', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['crm_number'];
				$this->ExtForm->input('crm_number', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['parent_name'];
				$this->ExtForm->input('parent_name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['parent_address'];
				$this->ExtForm->input('parent_address', $options);
			?>,
			<?php
				$options = array();
				if(isset($parent_id))
					$options['hidden'] = $parent_id;
				else
					$options['items'] = $edu_students;
				$options['value'] = $edu_receipt['EduReceipt']['edu_student_id'];
				$this->ExtForm->input('edu_student_id', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['student_name'];
				$this->ExtForm->input('student_name', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['student_number'];
				$this->ExtForm->input('student_number', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['student_class'];
				$this->ExtForm->input('student_class', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['student_section'];
				$this->ExtForm->input('student_section', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['student_academic_year'];
				$this->ExtForm->input('student_academic_year', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['total_before_tax'];
				$this->ExtForm->input('total_before_tax', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['total_after_tax'];
				$this->ExtForm->input('total_after_tax', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['VAT'];
				$this->ExtForm->input('VAT', $options);
			?>,
			<?php
				$options = array();
				$options['value'] = $edu_receipt['EduReceipt']['TOT'];
				$this->ExtForm->input('TOT', $options);
			?>
		]
	});
	
	var EduReceiptEditWindow = new Ext.Window({
		title: '<?php __('Edit Edu Receipt'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: EduReceiptEditForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduReceiptEditForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to modify an existing Edu Receipt.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduReceiptEditWindow.collapsed)
					EduReceiptEditWindow.expand(true);
				else
					EduReceiptEditWindow.collapse(true);
			}
		}],
		buttons: [ {
			text: '<?php __('Save'); ?>',
			handler: function(btn){
				EduReceiptEditForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduReceiptEditWindow.close();
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
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				EduReceiptEditWindow.close();
			}
		}]
	});
