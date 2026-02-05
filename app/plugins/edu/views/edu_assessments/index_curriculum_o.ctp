//<script>
var store_assessments = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'detail', 'course', 'edu_course_id', 'teacher', 'section',
			'edu_section_id', 'status', 'quarter'
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array(
            'controller' => 'edu_assessments', 'action' => 'list_data_for_curriculum_manager')); ?>'
	}),
	sortInfo:{field: 'section', direction: "ASC"},
	groupField: 'section'
});

function ViewAssessment(section_id, course_id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array(
			'controller' => 'edu_assessments', 'action' => 'view_detail')); ?>/'+section_id+'/'+course_id,
        success: function(response, opts) {
            var assessment_data = response.responseText;

            eval(assessment_data);

            AssessmentRecordDetailViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the assessment view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ReturnAssessment(section_id, course_id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array(
			'controller' => 'edu_assessments', 'action' => 'return_assessment_curriculum')); ?>/'+section_id+'/'+course_id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Assessment returned successfully!'); ?>');
			RefreshAssessmentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot return the selected assessment. Error code'); ?>: ' + response.status);
		}
	});
}

function ApproveAssessment(section_id, course_id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array(
			'controller' => 'edu_assessments', 'action' => 'make_assessments_approved')); ?>/'+section_id+'/'+course_id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Assessment returned successfully!'); ?>');
			RefreshAssessmentData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot return the selected assessment. Error code'); ?>: ' + response.status);
		}
	});
}

function RefreshAssessmentData() {
	store_assessments.reload();

    p.getTopToolbar().findById('view-assessment').disable();
    p.getTopToolbar().findById('return-assessment').disable();
	p.getTopToolbar().findById('approve-assessment').disable();
}

if(center_panel.find('id', 'assessment-curriculumu-tab') != "") {
	var p = center_panel.findById('assessment-curriculumu-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Assessments'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'assessment-curriculumu-tab',
		xtype: 'grid',
		store: store_assessments,
		columns: [
			{header: "<?php __('Section'); ?>", dataIndex: 'section', sortable: true},
			{header: "<?php __('Course'); ?>", dataIndex: 'course', sortable: true},
			{header: "<?php __('Term'); ?>", dataIndex: 'quarter', sortable: true},
			{header: "<?php __('Teacher'); ?>", dataIndex: 'teacher', sortable: true},
			{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Assessments" : "Assessment"]})'
        }),
		listeners: {
			celldblclick: function() {
				var ssm = Ext.getCmp('assessment-tab').getSelectionModel().getSelected();
				ViewAssessment(ssm.data.edu_section_id, ssm.data.edu_course_id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		}),
		tbar: new Ext.Toolbar({
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('View'); ?>',
					id: 'view-assessment',
					tooltip:'<?php __('<b>View Assessment</b><br />Click here to see details of the selected Assessment'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()) {
							ViewAssessment(sel.data.edu_section_id, sel.data.edu_course_id);
						}
					}
				}, ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Return'); ?>',
					id: 'return-assessment',
					tooltip:'<?php __('<b>Return Assessment</b><br />Click here to return the selected Assessment'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							Ext.MessageBox.buttonText.yes = "<b>Yes</b>";
							Ext.MessageBox.buttonText.no = "No";
							Ext.Msg.show({
								title: "<?php __('Are you sure?'); ?>",
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: "<?php __('Are you sure want to return the selected Assessment to its maker?'); ?> ",
								icon: Ext.MessageBox.QUESTION,
								fn: function (btn) {
									if (btn == 'yes') {
										ReturnAssessment(sel.data.edu_section_id, sel.data.edu_course_id);
									}
								}
							});
						}
					}
				}, ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Approve'); ?>',
					id: 'approve-assessment',
					tooltip:'<?php __('<b>Approve Assessments</b><br />Click here to approve the selected Assessments'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()) {
							Ext.MessageBox.buttonText.yes = "<b>Yes</b>";
							Ext.MessageBox.buttonText.no = "No";
							Ext.Msg.show({
								title: "<?php __('Are you sure?'); ?>",
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: "<?php __('Are you sure want to Approve the selected Assessments?'); ?> ",
								icon: Ext.MessageBox.QUESTION,
								fn: function (btn) {
									if (btn == 'yes') {
										ApproveAssessment(sel.data.edu_section_id, sel.data.edu_course_id);
									}
								}
							});
						}
					}
				}, ' ', '-',  '<?php __('Course'); ?>: ', {
					xtype : 'combo',
					emptyText: '[Select One]',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							<?php $st = false; foreach ($courses as $item) {
								if ($st) { echo ", "; } ?>
							['<?php echo $item['EduCourse']['id']; ?>',
								'<?php echo $item['EduCourse']['description']; ?>']
							<?php $st = true;
							}
							if (count($courses) > 0) { echo ',';} ?>
                            ['991', 'Unsubmitted'],
                            ['992', 'Submitted'],
                            ['993', 'Checked'],
                            ['994', 'Approved'],
							['999', 'Unapproved']
						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '[Select One]',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_assessments.reload({
								params: {
									start: 0,
									limit: list_size,
									edu_course_id : combo.getValue()
								}
							});

                            p.getTopToolbar().findById('view-assessment').disable();
			                p.getTopToolbar().findById('approve-assessment').disable();
							p.getTopToolbar().findById('return-assessment').disable();
						}
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: 1000,
			store: store_assessments,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('view-assessment').enable();
		p.getTopToolbar().findById('return-assessment').disable();
		p.getTopToolbar().findById('approve-assessment').disable();
		record = p.getStore().getAt(rowIdx);
		if (record.get('status') == 'Checked') {
			p.getTopToolbar().findById('return-assessment').enable();
			p.getTopToolbar().findById('approve-assessment').enable();
		}
		if (record.get('status') == 'Approved') {
			p.getTopToolbar().findById('return-assessment').enable();
		}
		if (this.getSelections().length > 1) {
			p.getTopToolbar().findById('view-assessment').disable();
			p.getTopToolbar().findById('return-assessment').disable();
			p.getTopToolbar().findById('approve-assessment').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('view-assessment').enable();
		p.getTopToolbar().findById('return-assessment').disable();
		p.getTopToolbar().findById('approve-assessment').disable();
		if(this.getSelections().length == 1) {
			record = p.getStore().getAt(rowIdx);
			if (record.get('status') == 'Checked') {
				p.getTopToolbar().findById('return-assessment').enable();
				p.getTopToolbar().findById('approve-assessment').enable();
			}
			if (record.get('status') == 'Approved') {
				p.getTopToolbar().findById('return-assessment').enable();
			}
		}
		if (this.getSelections().length > 1 || this.getSelections().length < 1) {
			p.getTopToolbar().findById('view-assessment').disable();
			p.getTopToolbar().findById('return-assessment').disable();
			p.getTopToolbar().findById('approve-assessment').disable();
		}
	});
	center_panel.setActiveTab(p);
}
