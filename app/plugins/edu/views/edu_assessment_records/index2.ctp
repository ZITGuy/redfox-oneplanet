//<script>
var store_parent_assessmentRecords = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','student','assessment','rank'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentAssessmentRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_assessmentRecord_data = response.responseText;
			
			eval(parent_assessmentRecord_data);
			
			AssessmentRecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessment Record add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentAssessmentRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_assessmentRecord_data = response.responseText;
			
			eval(parent_assessmentRecord_data);
			
			AssessmentRecordEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessment Record edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewAssessmentRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var assessmentRecord_data = response.responseText;

			eval(assessmentRecord_data);

			AssessmentRecordViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessment Record view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentAssessmentRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_assessment_records', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Assessment Record(s) successfully deleted!'); ?>');
			RefreshParentAssessmentRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessment Record to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentAssessmentRecordName(value){
	var conditions = '\'EduAssessmentRecord.name LIKE\' => \'%' + value + '%\'';
	store_parent_assessmentRecords.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentAssessmentRecordData() {
	store_parent_assessmentRecords.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Assessment Records'); ?>',
	store: store_parent_assessmentRecords,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'assessmentRecordGrid',
	columns: [
		{header: "<?php __('Student'); ?>", dataIndex: 'student', sortable: true},
		{header: "<?php __('Assessment'); ?>", dataIndex: 'assessment', sortable: true},
		{header: "<?php __('Mark'); ?>", dataIndex: 'rank', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewAssessmentRecord(Ext.getCmp('assessmentRecordGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Assessment Record</b><br />Click here to create a new Assessment Record'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentAssessmentRecord();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-assessmentRecord',
				tooltip:'<?php __('<b>Edit AssessmentRecord</b><br />Click here to modify the selected AssessmentRecord'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentAssessmentRecord(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-assessmentRecord',
				tooltip:'<?php __('<b>Delete AssessmentRecord(s)</b><br />Click here to remove the selected AssessmentRecord(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove AssessmentRecord'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
								icon: Ext.MessageBox.QUESTION,
                                fn: function(btn){
									if (btn == 'yes'){
										DeleteParentAssessmentRecord(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove AssessmentRecord'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected AssessmentRecord'); ?>?',
								icon: Ext.MessageBox.QUESTION,
                                fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteParentAssessmentRecord(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', {
				xtype: 'tbsplit',
				text: '<?php __('View AssessmentRecord'); ?>',
				id: 'view-assessmentRecord2',
				tooltip:'<?php __('<b>View AssessmentRecord</b><br />Click here to see details of the selected AssessmentRecord'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewAssessmentRecord(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_assessmentRecord_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentAssessmentRecordName(Ext.getCmp('parent_assessmentRecord_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_assessmentRecord_go_button',
				handler: function(){
					SearchByParentAssessmentRecordName(Ext.getCmp('parent_assessmentRecord_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_assessmentRecords,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-assessmentRecord').enable();
	g.getTopToolbar().findById('delete-parent-assessmentRecord').enable();
        g.getTopToolbar().findById('view-assessmentRecord2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-assessmentRecord').disable();
                g.getTopToolbar().findById('view-assessmentRecord2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-assessmentRecord').disable();
		g.getTopToolbar().findById('delete-parent-assessmentRecord').enable();
                g.getTopToolbar().findById('view-assessmentRecord2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-assessmentRecord').enable();
		g.getTopToolbar().findById('delete-parent-assessmentRecord').enable();
                g.getTopToolbar().findById('view-assessmentRecord2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-assessmentRecord').disable();
		g.getTopToolbar().findById('delete-parent-assessmentRecord').disable();
                g.getTopToolbar().findById('view-assessmentRecord2').disable();
	}
});



var parentEduAssessmentRecordsViewWindow = new Ext.Window({
	title: 'AssessmentRecord Under the selected Item',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
		g
	],

	buttons: [{
		text: 'Close',
		handler: function(btn){
			parentEduAssessmentRecordsViewWindow.close();
		}
	}]
});

store_parent_assessmentRecords.load({
    params: {
        start: 0,    
        limit: list_size
    }
});

parentEduAssessmentRecordsViewWindow.show();