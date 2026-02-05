//<script>
var store_assessmentRecords = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','student','assessment','rank'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'assessmentRecords', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'student_id', direction: "ASC"},
	groupField: 'assessment_id'
});

function ViewAssessmentRecord(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'assessmentRecords', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var assessmentRecord_data = response.responseText;

            eval(assessmentRecord_data);

            AssessmentRecordViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessmentRecord view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ReturnAssessmentRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'assessment_records', 'action' => 'return_assessment_record')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('AssessmentRecord successfully deleted!'); ?>');
			RefreshAssessmentRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the assessmentRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchAssessmentRecord(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'assessmentRecords', 'action' => 'search')); ?>',
		success: function(response, opts){
			var assessmentRecord_data = response.responseText;

			eval(assessmentRecord_data);

			assessmentRecordSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the assessmentRecord search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByAssessmentRecordName(value){
	var conditions = '\'AssessmentRecord.name LIKE\' => \'%' + value + '%\'';
	store_assessmentRecords.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshAssessmentRecordData() {
	store_assessmentRecords.reload();
}


if(center_panel.find('id', 'assessmentRecord-tab') != "") {
	var p = center_panel.findById('assessmentRecord-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Assessment Records'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'assessmentRecord-tab',
		xtype: 'grid',
		store: store_assessmentRecords,
		columns: [
			{header: "<?php __('Student'); ?>", dataIndex: 'student', sortable: true},
			{header: "<?php __('Assessment'); ?>", dataIndex: 'assessment', sortable: true},
			{header: "<?php __('Rank'); ?>", dataIndex: 'rank', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "AssessmentRecords" : "AssessmentRecord"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewAssessmentRecord(Ext.getCmp('assessmentRecord-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add AssessmentRecords</b><br />Click here to create a new AssessmentRecord'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddAssessmentRecord();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-assessmentRecord',
					tooltip:'<?php __('<b>Edit AssessmentRecords</b><br />Click here to modify the selected AssessmentRecord'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditAssessmentRecord(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-assessmentRecord',
					tooltip:'<?php __('<b>Delete AssessmentRecords(s)</b><br />Click here to remove the selected AssessmentRecord(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove AssessmentRecord'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteAssessmentRecord(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove AssessmentRecord'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected AssessmentRecords'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteAssessmentRecord(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: '<?php __('View AssessmentRecord'); ?>',
					id: 'view-assessmentRecord',
					tooltip:'<?php __('<b>View AssessmentRecord</b><br />Click here to see details of the selected AssessmentRecord'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewAssessmentRecord(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('Student'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($students as $item){if($st) echo ",
							";?>['<?php echo $item['Student']['id']; ?>' ,'<?php echo $item['Student']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_assessmentRecords.reload({
								params: {
									start: 0,
									limit: list_size,
									student_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'assessmentRecord_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByAssessmentRecordName(Ext.getCmp('assessmentRecord_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'assessmentRecord_go_button',
					handler: function(){
						SearchByAssessmentRecordName(Ext.getCmp('assessmentRecord_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchAssessmentRecord();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_assessmentRecords,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-assessmentRecord').enable();
		p.getTopToolbar().findById('delete-assessmentRecord').enable();
		p.getTopToolbar().findById('view-assessmentRecord').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-assessmentRecord').disable();
			p.getTopToolbar().findById('view-assessmentRecord').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-assessmentRecord').disable();
			p.getTopToolbar().findById('view-assessmentRecord').disable();
			p.getTopToolbar().findById('delete-assessmentRecord').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-assessmentRecord').enable();
			p.getTopToolbar().findById('view-assessmentRecord').enable();
			p.getTopToolbar().findById('delete-assessmentRecord').enable();
		}
		else{
			p.getTopToolbar().findById('edit-assessmentRecord').disable();
			p.getTopToolbar().findById('view-assessmentRecord').disable();
			p.getTopToolbar().findById('delete-assessmentRecord').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_assessmentRecords.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
