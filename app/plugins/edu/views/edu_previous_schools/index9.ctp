//<script>
var store_prev_schools= new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','country','year_attended','grade_levels','languages'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_previous_schools', 'action' => 'list_data')); ?>'	})
});


function AddPrevSchool() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_previous_schools', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var parent_eduPreviousSchool_data = response.responseText;
			
			eval(parent_eduPreviousSchool_data);
			
			EduPreviousSchoolAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the add form. Error code'); ?>: ' + response.status);
		}
	});
}

function DeletePrevSchool(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_previous_schools', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Data deleted successfully !'); ?>');
			RefreshEduPreviousSchoolData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot be deleted. Error code'); ?>: ' + response.status);
		}
	});
}


function RefreshEduPreviousSchoolData() {
	store_prev_schools.reload();
}

var g = new Ext.grid.GridPanel({
	title: '<?php __('Previous Schools Attended'); ?>',
	store: store_prev_schools,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'eduParentGrid',
	columns: [
		{header: "<?php __('Country'); ?>", dataIndex: 'country', sortable: true},
		{header: "<?php __('Year Attended'); ?>", dataIndex: 'year_attended', sortable: true},
		{header: "<?php __('Grade Levels'); ?>", dataIndex: 'grade_levels', sortable: true},
		{header: "<?php __('Languages Used'); ?>", dataIndex: 'languages', sortable: true}	
	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Prev School</b><br />Click here to create a new Prev School Attended'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddPrevSchool();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-eduPrevSchool',
				tooltip:'<?php __('<b>Delete Prev School(s)</b><br />Click here to remove the selected Prev School(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
								title: '<?php __('Remove Prev. School'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove'); ?> '+sel[0].data.country+'?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										DeletePrevSchool(sel[0].data.id);
									}
								}
							});
						} else {
							Ext.Msg.show({
								title: '<?php __('Remove Prev. School'); ?>',
								buttons: Ext.MessageBox.YESNOCANCEL,
								msg: '<?php __('Remove the selected Prev. School'); ?>?',
								icon: Ext.MessageBox.QUESTION,
								fn: function(btn){
									if (btn == 'yes'){
										var sel_ids = '';
										for(i=0;i<sel.length;i++){
											if(i>0)
												sel_ids += '_';
											sel_ids += sel[i].data.id;
										}
										DeletePrevSchool(sel_ids);
									}
								}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					}
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_prev_schools,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('delete-parent-eduPrevSchool').enable();
});

g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 0){
		g.getTopToolbar().findById('delete-parent-eduPrevSchool').enable();
    }
	else{
		g.getTopToolbar().findById('delete-parent-eduPrevSchool').disable();
    }
});

var PreviousSchoolWindow = new Ext.Window({
	title: 'Previous School Attended',
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
		text: 'Continue',
		handler: function(btn){
			PreviousSchoolWindow.close();
		}
	}]
});

store_prev_schools.load({
    params: {
        start: 0,    
        limit: list_size
    }
});
