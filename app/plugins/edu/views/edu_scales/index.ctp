//<script>
var store_edu_scales = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','min','max','scale', 'remark','created','modified'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: 'min', direction: "ASC"}
});


function AddEduScale() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var edu_scale_data = response.responseText;
			
			eval(edu_scale_data);
			
			EduScaleAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Scale add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditEduScale(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var edu_scale_data = response.responseText;
			
			eval(edu_scale_data);
			
			EduScaleEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Scale edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewEduScale(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var edu_scale_data = response.responseText;

            eval(edu_scale_data);

            EduScaleViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Scale view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteEduScale(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Scale successfully deleted!'); ?>');
			RefreshEduScaleData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Scale add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchEduScale(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_scales', 'action' => 'search')); ?>',
		success: function(response, opts){
			var edu_scale_data = response.responseText;

			eval(edu_scale_data);

			eduScaleSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Scale search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function RefreshEduScaleData() {
	store_edu_scales.reload();
}


if(center_panel.find('id', 'edu_scale_tab') != "") {
	var p = center_panel.findById('edu_scale_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Scales'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'edu_scale_tab',
		xtype: 'grid',
		store: store_edu_scales,
		columns: [
			{header: "<?php __('Minimum (Inclusive)'); ?>", dataIndex: 'min', sortable: true},
			{header: "<?php __('Maximum (Exclusive)'); ?>", dataIndex: 'max', sortable: true},
			{header: "<?php __('Scale'); ?>", dataIndex: 'scale', sortable: true},
			{header: "<?php __('Remark'); ?>", dataIndex: 'remark', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
		],	
		viewConfig: {
            forceFit:true
        },
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Scales</b><br />Click here to create a new Scale'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddEduScale();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_edu_scale',
					tooltip:'<?php __('<b>Edit Scales</b><br />Click here to modify the selected Scale'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditEduScale(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_edu_scale',
					tooltip:'<?php __('<b>Delete Scale(s)</b><br />Click here to remove the selected Scale(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Scale'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteEduScale(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Scale'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Scales'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteEduScale(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_edu_scales,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_edu_scale').enable();
		p.getTopToolbar().findById('delete_edu_scale').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_scale').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_edu_scale').disable();
			p.getTopToolbar().findById('delete_edu_scale').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_edu_scale').enable();
			p.getTopToolbar().findById('delete_edu_scale').enable();
		}
		else{
			p.getTopToolbar().findById('edit_edu_scale').disable();
			p.getTopToolbar().findById('delete_edu_scale').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_edu_scales.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
