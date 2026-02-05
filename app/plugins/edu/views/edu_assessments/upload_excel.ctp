//<script>    
    <?php
		$this->ExtForm->create('EduAssessment');
		//$this->ExtForm->defineFieldFunctions();
	?>
	var EduAssessmentUploadExcelForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		fileUpload: true,
		isUpload: true,
		labelWidth: 120,
		labelAlign: 'right',
		autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		url:'<?php echo $this->Html->url(array('controller' => 'edu_assessments', 'action' => 'upload_excel')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array(
					'anchor' => '60%',
					'id' => 'data[EduAssessment][excel_file_name]',
					'xtype' => 'fileuploadfield',
					'fieldLabel' => 'Excel File',
					'buttonText' => '',
					'emptyText' => 'Select an Excel File',
					'buttonCfg' => "{
							iconCls: 'upload-icon'
						}"
					);
				$this->ExtForm->input('excel_file_name', $options);
			?>
		]
	});
	
	var EduAssessmentUploadExcelWindow = new Ext.Window({
		title: '<?php __('Upload Assessments Excel'); ?>',
		width: 650,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain: true,
		bodyStyle: 'padding:5px;',
		buttonAlign: 'right',
		items: EduAssessmentUploadExcelForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduAssessmentUploadExcelForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to upload assessment records.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduAssessmentUploadExcelWindow.collapsed)
					EduAssessmentUploadExcelWindow.expand(true);
				else
					EduAssessmentUploadExcelWindow.collapse(true);
			}
		}],
		buttons: [{
			text: '<?php __('Upload'); ?>',
			handler: function(btn){
				EduAssessmentUploadExcelForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduAssessmentUploadExcelWindow.close();
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
				EduAssessmentUploadExcelWindow.close();
			}
		}]
	});