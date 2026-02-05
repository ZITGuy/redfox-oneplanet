//<script>
var store_edu_parent_details = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','short_name','first_name','middle_name','last_name','residence_address','nationality','relationship','occupation','academic_qualification','employment_status','employer','work_address','work_telephone','mobile','email','photo_file','family_type','edu_parent','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: 'short_name', direction: "ASC"},
	groupField: 'first_name'

});


function AddEduParentDetail() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var edu_parent_detail_data = response.responseText;
			
			eval(edu_parent_detail_data);
			
			EduParentDetailAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Parent Detail add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduParentDetail(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var edu_parent_detail_data = response.responseText;
			
			eval(edu_parent_detail_data);
			
			EduParentDetailEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Parent Detail edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduParentDetail(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var edu_parent_detail_data = response.responseText;

            eval(edu_parent_detail_data);

            EduParentDetailViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Parent Detail view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduParentDetail(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Edu Parent Detail successfully deleted!'); ?>');
			RefreshEduParentDetailData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Edu Parent Detail add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduParentDetail(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'search')); ?>',
		success: function(response, opts){
			var edu_parent_detail_data = response.responseText;

			eval(edu_parent_detail_data);

			eduParentDetailSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Edu Parent Detail search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByEduParentDetailName(value){
	var conditions = '\'EduParentDetail.name LIKE\' => \'%' + value + '%\'';
	store_edu_parent_details.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEduParentDetailData() {
	store_edu_parent_details.reload();
}


if(center_panel.find('id', 'edu_parent_detail_tab') != "") {
	var p = center_panel.findById('edu_parent_detail_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Edu Parent Details'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'edu_parent_detail_tab',
		xtype: 'grid',
		store: store_edu_parent_details,
		columns: [
			{header: "<?php __('Short Name'); ?>", dataIndex: 'short_name', sortable: true},
			{header: "<?php __('First Name'); ?>", dataIndex: 'first_name', sortable: true},
			{header: "<?php __('Middle Name'); ?>", dataIndex: 'middle_name', sortable: true},
			{header: "<?php __('Last Name'); ?>", dataIndex: 'last_name', sortable: true},
			{header: "<?php __('Residence Address'); ?>", dataIndex: 'residence_address', sortable: true},
			{header: "<?php __('Nationality'); ?>", dataIndex: 'nationality', sortable: true},
			{header: "<?php __('Relationship'); ?>", dataIndex: 'relationship', sortable: true},
			{header: "<?php __('Occupation'); ?>", dataIndex: 'occupation', sortable: true},
			{header: "<?php __('Academic Qualification'); ?>", dataIndex: 'academic_qualification', sortable: true},
			{header: "<?php __('Employment Status'); ?>", dataIndex: 'employment_status', sortable: true},
			{header: "<?php __('Employer'); ?>", dataIndex: 'employer', sortable: true},
			{header: "<?php __('Work Address'); ?>", dataIndex: 'work_address', sortable: true},
			{header: "<?php __('Work Telephone'); ?>", dataIndex: 'work_telephone', sortable: true},
			{header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true},
			{header: "<?php __('Email'); ?>", dataIndex: 'email', sortable: true},
			{header: "<?php __('Photo File'); ?>", dataIndex: 'photo_file', sortable: true},
			{header: "<?php __('Family Type'); ?>", dataIndex: 'family_type', sortable: true},
			{header: "<?php __('EduParent'); ?>", dataIndex: 'edu_parent', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Edu Parent Details" : "Edu Parent Detail"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewEduParentDetail(Ext.getCmp('edu_parent_detail_tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Edu Parent Details</b><br />Click here to create a new Edu Parent Detail'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduParentDetail();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_edu_parent_detail',
					tooltip:'<?php __('<b>Edit Edu Parent Details</b><br />Click here to modify the selected Edu Parent Detail'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduParentDetail(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_edu_parent_detail',
					tooltip:'<?php __('<b>Delete Edu Parent Detail(s)</b><br />Click here to remove the selected Edu Parent Detail(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Edu Parent Detail'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduParentDetail(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Edu Parent Detail'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Edu Parent Details'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduParentDetail(sel_ids);
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
					text: '<?php __('View Edu Parent Detail'); ?>',
					id: 'view_edu_parent_detail',
					tooltip:'<?php __('<b>View Edu Parent Detail</b><br />Click here to see details of the selected Edu Parent Detail'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewEduParentDetail(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('EduParent'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($eduparents as $item){if($st) echo ",
							";?>['<?php echo $item['EduParent']['id']; ?>' ,'<?php echo $item['EduParent']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_edu_parent_details.reload({
								params: {
									start: 0,
									limit: list_size,
									eduparent_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'edu_parent_detail_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEduParentDetailName(Ext.getCmp('edu_parent_detail_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'edu_parent_detail_go_button',
					handler: function(){
						SearchByEduParentDetailName(Ext.getCmp('edu_parent_detail_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchEduParentDetail();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_edu_parent_details,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_edu_parent_detail').enable();
		p.getTopToolbar().findById('delete_edu_parent_detail').enable();
		p.getTopToolbar().findById('view_edu_parent_detail').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_parent_detail').disable();
			p.getTopToolbar().findById('view_edu_parent_detail').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_parent_detail').disable();
			p.getTopToolbar().findById('view_edu_parent_detail').disable();
			p.getTopToolbar().findById('delete_edu_parent_detail').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_edu_parent_detail').enable();
			p.getTopToolbar().findById('view_edu_parent_detail').enable();
			p.getTopToolbar().findById('delete_edu_parent_detail').enable();
		}
		else{
			p.getTopToolbar().findById('edit_edu_parent_detail').disable();
			p.getTopToolbar().findById('view_edu_parent_detail').disable();
			p.getTopToolbar().findById('delete_edu_parent_detail').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_edu_parent_details.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
