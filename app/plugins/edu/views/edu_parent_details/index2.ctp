//<script>
var store_parent_eduParentDetails = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id', 'short_name', 'first_name', 'middle_name','last_name','residence_address',
			'nationality','relationship','occupation','academic_qualification',
			'employment_status','employer','work_address','work_telephone','mobile',
			'email','photo_file','family_type','edu_parent','created','modified'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentEduParentDetail() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_eduParentDetail_data = response.responseText;
			
			eval(parent_eduParentDetail_data);
			
			EduParentDetailAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Detail add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentEduParentDetail(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_eduParentDetail_data = response.responseText;
			eval(parent_eduParentDetail_data);
			EduParentDetailEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Detail edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduParentDetail(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var eduParentDetail_data = response.responseText;
			eval(eduParentDetail_data);
			EduParentDetailViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Detail view form. Error code'); ?>: ' + response.status);
		}
	});
}

function UploadParentPhoto(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'upload_photo')); ?>/'+id,
		success: function(response, opts) {
			var eduParentDetail_data = response.responseText;
			eval(eduParentDetail_data);
			EduParentDetailUploadPhotoWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Photo Upload form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeleteParentEduParentDetail(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_parent_details', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Parent Detail(s) successfully deleted!'); ?>');
			RefreshParentEduParentDetailData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Parent Detail to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentEduParentDetailName(value){
	var conditions = '\'EduParentDetail.name LIKE\' => \'%' + value + '%\'';
	store_parent_eduParentDetails.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentEduParentDetailData() {
	store_parent_eduParentDetails.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Parent Details'); ?>',
	store: store_parent_eduParentDetails,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentDetailGrid',
	columns: [
		{header: "<?php __('Short Name'); ?>", dataIndex: 'short_name', sortable: true, hidden: true},
		{header: "<?php __('First Name'); ?>", dataIndex: 'first_name', sortable: true},
		{header: "<?php __('Middle Name'); ?>", dataIndex: 'middle_name', sortable: true},
		{header: "<?php __('Last Name'); ?>", dataIndex: 'last_name', sortable: true},
		{header: "<?php __('Residence Address'); ?>", dataIndex: 'residence_address', sortable: true, hidden: true},
		{header: "<?php __('Nationality'); ?>", dataIndex: 'nationality', sortable: true, hidden: true},
		{header: "<?php __('Role'); ?>", dataIndex: 'family_type', sortable: true},
		{header: "<?php __('Occupation'); ?>", dataIndex: 'occupation', sortable: true},
		{header: "<?php __('Academic Qualification'); ?>", dataIndex: 'academic_qualification', sortable: true, hidden: true},
		{header: "<?php __('Employment Status'); ?>", dataIndex: 'employment_status', sortable: true, hidden: true},
		{header: "<?php __('Employer'); ?>", dataIndex: 'employer', sortable: true, hidden: true},
		{header: "<?php __('Work Address'); ?>", dataIndex: 'work_address', sortable: true, hidden: true},
		{header: "<?php __('Work Telephone'); ?>", dataIndex: 'work_telephone', sortable: true, hidden: true},
		{header: "<?php __('Mobile'); ?>", dataIndex: 'mobile', sortable: true},
		{header: "<?php __('Email'); ?>", dataIndex: 'email', sortable: true, hidden: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
		{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewEduParentDetail(Ext.getCmp('eduParentDetailGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Parent Detail</b><br />Click here to create a new Parent Detail'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentEduParentDetail();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-eduParentDetail',
				tooltip:'<?php __('<b>Edit Parent Detail</b><br />Click here to modify the selected Parent Detail'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentEduParentDetail(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduParentDetail',
				tooltip:'<?php __('<b>Delete Parent Detail(s)</b><br />Click here to remove the selected Parent Detail(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Parent Detail'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeleteParentEduParentDetail(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Parent Detail'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Parent Detail'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeleteParentEduParentDetail(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', {
				xtype: 'tbbutton',
				text: '<?php __('View Parent Detail'); ?>',
				id: 'view-eduParentDetail2',
				tooltip:'<?php __('<b>View Parent Detail</b><br />Click here to see details of the selected Parent Detail'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewEduParentDetail(sel.data.id);
					};
				}

            }, ' ','-',' ', {
				xtype: 'tbbutton',
				text: '<?php __('Upload Photo'); ?>',
				id: 'btnUploadPhoto',
				tooltip:'<?php __('<b>Upload Photo</b><br />Click here to upload photo for the selected Parent'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						UploadParentPhoto(sel.data.id);
					};
				}

            }, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_eduParentDetails,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-eduParentDetail').enable();
	g.getTopToolbar().findById('delete-parent-eduParentDetail').enable();
    g.getTopToolbar().findById('view-eduParentDetail2').enable();
    g.getTopToolbar().findById('btnUploadPhoto').enable();
	
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduParentDetail').disable();
        g.getTopToolbar().findById('view-eduParentDetail2').disable();
        g.getTopToolbar().findById('btnUploadPhoto').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-eduParentDetail').disable();
		g.getTopToolbar().findById('delete-parent-eduParentDetail').enable();
        g.getTopToolbar().findById('view-eduParentDetail2').disable();
		g.getTopToolbar().findById('btnUploadPhoto').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-eduParentDetail').enable();
		g.getTopToolbar().findById('delete-parent-eduParentDetail').enable();
        g.getTopToolbar().findById('view-eduParentDetail2').enable();
        g.getTopToolbar().findById('btnUploadPhoto').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-eduParentDetail').disable();
		g.getTopToolbar().findById('delete-parent-eduParentDetail').disable();
        g.getTopToolbar().findById('view-eduParentDetail2').disable();
        g.getTopToolbar().findById('btnUploadPhoto').disable();
	}
});



var parentEduParentDetailsViewWindow = new Ext.Window({
	title: 'Parent Details of the selected Parent',
	width: 800,
	height: 375,
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
			parentEduParentDetailsViewWindow.close();
		}
	}]
});

store_parent_eduParentDetails.load({
    params: {
        start: 0,    
        limit: list_size
    }
});