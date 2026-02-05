//<script>
var store_corrections = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'section', 'term', 'student', 'assessment', 'assessment_out_of',
			'old_value', 'new_value', 'status', 'reason', 'rejection_reason'
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'list_data_m')); ?>'
	}),
	sortInfo:{field: 'student', direction: "ASC"},
	groupField: 'student'
});

function AddCorrection() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var correction_data = response.responseText;
			
			eval(correction_data);
			
			CorrectionAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the correction add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditCorrection(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var correction_data = response.responseText;
			
			eval(correction_data);
			
			CorrectionEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the correction edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function SubmitCorrection(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'submit_correction')); ?>/'+id,
		success: function(response, opts) {
			var correction_data = response.responseText;
			
			eval(correction_data);
			
			CorrectionSubmitWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the correction submit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewCorrection(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var correction_data = response.responseText;

            eval(correction_data);

            CorrectionViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the correction view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteCorrection(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Correction successfully deleted!'); ?>');
			RefreshCorrectionData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the correction add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchCorrection(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_corrections', 'action' => 'search')); ?>',
		success: function(response, opts){
			var correction_data = response.responseText;

			eval(correction_data);

			correctionSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>',
				'<?php __('Cannot get the correction search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByCorrectionName(value){
	var conditions = '\'EduCorrection.name LIKE\' => \'%' + value + '%\'';
	store_corrections.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshCorrectionData() {
	store_corrections.reload();
}


if(center_panel.find('id', 'correction-tab') != "") {
	var p = center_panel.findById('correction-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Corrections'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'correction-tab',
		xtype: 'grid',
		store: store_corrections,
		columns: [
			{header: "<?php __('AY - Term'); ?>", dataIndex: 'term', sortable: true},
			{header: "<?php __('Section - Class'); ?>", dataIndex: 'section', sortable: true},
			{header: "<?php __('Student'); ?>", dataIndex: 'student', sortable: true},
			{header: "<?php __('Assessment'); ?>", dataIndex: 'assessment', sortable: true},
			{header: "<?php __('Out of'); ?>", dataIndex: 'assessment_out_of', sortable: true},
			{header: "<?php __('Old Value'); ?>", dataIndex: 'old_value', sortable: true},
			{header: "<?php __('New Value'); ?>", dataIndex: 'new_value', sortable: true},
			{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
			{header: "<?php __('Reason'); ?>", dataIndex: 'reason', sortable: true},
			{header: "<?php __('Rejection Reason'); ?>", dataIndex: 'rejection_reason', sortable: true}
		],
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Corrections" : "Correction"]})'
        }),
		listeners: {
			celldblclick: function(){
				ViewCorrection(Ext.getCmp('correction-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Correction</b><br />Click here to create a new Correction'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddCorrection();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-correction',
					tooltip:'<?php __('<b>Edit Correction</b><br />Click here to modify the selected Correction'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditCorrection(sel.data.id);
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Submit'); ?>',
					id: 'submit-correction',
					tooltip:'<?php __('<b>Submit Correction</b><br />Click here to submit the selected Correction'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							SubmitCorrection(sel.data.id);
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-correction',
					tooltip:'<?php __('<b>Delete Correction(s)</b><br />Click here to remove the selected Correction(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Correction'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteCorrection(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Correction'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Corrections'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteCorrection(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: '<?php __('View Correction'); ?>',
					id: 'view-correction',
					tooltip:'<?php __('<b>View Correction</b><br />Click here to see details of the selected Correction'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewCorrection(sel.data.id);
						}
					},
					menu : {
						items: [
						]
					}
				}, ' ', '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'correction_search_field',
					listeners: {
						specialkey: function(field, e) {
							if (e.getKey() == e.ENTER) {
								SearchByCorrectionName(Ext.getCmp('correction_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'correction_go_button',
					handler: function(){
						SearchByCorrectionName(Ext.getCmp('correction_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchCorrection();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_corrections,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		record = store_corrections.getAt(rowIdx);

		p.getTopToolbar().findById('edit-correction').enable();
		if(record.get('status') == 'CREATED' || record.get('status') == 'REJECTED') {
			p.getTopToolbar().findById('submit-correction').enable();
		} else {
			p.getTopToolbar().findById('submit-correction').disable();
		}
		p.getTopToolbar().findById('delete-correction').enable();
		p.getTopToolbar().findById('view-correction').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-correction').disable();
			p.getTopToolbar().findById('submit-correction').disable();
			p.getTopToolbar().findById('view-correction').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		record = store_corrections.getAt(rowIdx);
		if(this.getSelections().length > 1) {
			p.getTopToolbar().findById('edit-correction').disable();
			p.getTopToolbar().findById('submit-correction').disable();
			p.getTopToolbar().findById('view-correction').disable();
			p.getTopToolbar().findById('delete-correction').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-correction').enable();
			if(record.get('status') == 'CREATED' || record.get('status') == 'REJECTED') {
				p.getTopToolbar().findById('submit-correction').enable();
			} else {
				p.getTopToolbar().findById('submit-correction').disable();
			}
			p.getTopToolbar().findById('view-correction').enable();
			p.getTopToolbar().findById('delete-correction').enable();
		}
		else{
			p.getTopToolbar().findById('edit-correction').disable();
			p.getTopToolbar().findById('submit-correction').disable();
			p.getTopToolbar().findById('view-correction').disable();
			p.getTopToolbar().findById('delete-correction').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_corrections.load({
		params: {
			start: 0,
			limit: list_size
		}
	});
}
