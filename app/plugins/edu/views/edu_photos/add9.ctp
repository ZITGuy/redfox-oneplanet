//<script>
	<?php
		$this->ExtForm->create('EduPhoto');
		$this->ExtForm->defineFieldFunctions();
	?>
	var EduPhotoAddForm = new Ext.form.FormPanel({
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
		url:'<?php echo $this->Html->url(array('controller' => 'edu_photos', 'action' => 'add')); ?>',
		defaultType: 'textfield',

		items: [
			<?php
				$options = array('anchor' => '90%');
				$this->ExtForm->input('title', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'combo',
					'fieldLabel' => 'Relationship',
					'items' => array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian', 'S' => 'Student'),
					'anchor' => '80%'
				);
				$this->ExtForm->input('relationship', $options);
			?>,
			<?php
				$options = array(
					'anchor' => '60%',
					'id' => 'data[EduPhoto][photo_file]',
					'xtype' => 'fileuploadfield',
					'fieldLabel' => 'Photo File',
					'buttonText' => '',
					'emptyText' => 'Select a Photo File',
					'buttonCfg' => "{
							iconCls: 'upload-icon'
						}"
					);
				$this->ExtForm->input('photo_file', $options);
			?>
		]
	});
	
	var EduPhotoAddWindow = new Ext.Window({
		title: '<?php __('Add Photo'); ?>',
		width: 650,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain: true,
		bodyStyle: 'padding:5px;',
		buttonAlign: 'right',
		items: EduPhotoAddForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				EduPhotoAddForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
				Ext.Msg.show({
					title: 'Help',
					buttons: Ext.MessageBox.OK,
					msg: 'This form is used to insert a new Photo.',
					icon: Ext.MessageBox.INFO
				});
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(EduParentAddWindow.collapsed)
					EduParentAddWindow.expand(true);
				else
					EduParentAddWindow.collapse(true);
			}
		}],
		buttons: [{
			text: '<?php __('Upload'); ?>',
			handler: function(btn){
				EduPhotoAddForm.getForm().submit({
					waitMsg: '<?php __('Submitting your data...'); ?>',
					waitTitle: '<?php __('Wait Please...'); ?>',
					success: function(f,a){
						Ext.Msg.show({
							title: '<?php __('Success'); ?>',
							buttons: Ext.MessageBox.OK,
							msg: a.result.msg,
							icon: Ext.MessageBox.INFO
						});
						EduPhotoAddWindow.close();
						RefreshEduPhotoData();
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
				EduPhotoAddWindow.close();
			}
		}]
	});