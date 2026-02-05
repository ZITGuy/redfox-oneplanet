//<script>
var store_report_categories = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id'
Notice: Undefined offset: 2 in C:\wamp\www\etham\cake\console\templates\default\views\index.ctp on line 40
,'name'
Notice: Undefined offset: 2 in C:\wamp\www\etham\cake\console\templates\default\views\index.ctp on line 40
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: 'name', direction: "ASC"}
});


function AddReportCategory() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var report_category_data = response.responseText;
			
			eval(report_category_data);
			
			ReportCategoryAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Report Category add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditReportCategory(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var report_category_data = response.responseText;
			
			eval(report_category_data);
			
			ReportCategoryEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Report Category edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewReportCategory(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var report_category_data = response.responseText;

            eval(report_category_data);

            ReportCategoryViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Report Category view form. Error code'); ?>: ' + response.status);
        }
    });
}
function ViewParentReports(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'reports', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_reports_data = response.responseText;

            eval(parent_reports_data);

            parentReportsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the reports view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteReportCategory(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Report Category successfully deleted!'); ?>');
			RefreshReportCategoryData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Report Category add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchReportCategory(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'report_categories', 'action' => 'search')); ?>',
		success: function(response, opts){
			var report_category_data = response.responseText;

			eval(report_category_data);

			reportCategorySearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the Report Category search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByReportCategoryName(value){
	var conditions = '\'ReportCategory.name LIKE\' => \'%' + value + '%\'';
	store_report_categories.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshReportCategoryData() {
	store_report_categories.reload();
}


if(center_panel.find('id', 'report_category_tab') != "") {
	var p = center_panel.findById('report_category_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Report Categories'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'report_category_tab',
		xtype: 'grid',
		store: store_report_categories,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
		],
		viewConfig: {
			forceFit: true
		}
,
		listeners: {
			celldblclick: function(){
				ViewReportCategory(Ext.getCmp('report_category_tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Report Categories</b><br />Click here to create a new Report Category'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddReportCategory();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit_report_category',
					tooltip:'<?php __('<b>Edit Report Categories</b><br />Click here to modify the selected Report Category'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditReportCategory(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete_report_category',
					tooltip:'<?php __('<b>Delete Report Category(s)</b><br />Click here to remove the selected Report Category(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Report Category'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteReportCategory(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Report Category'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Report Categories'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteReportCategory(sel_ids);
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
					text: '<?php __('View Report Category'); ?>',
					id: 'view_report_category',
					tooltip:'<?php __('<b>View Report Category</b><br />Click here to see details of the selected Report Category'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewReportCategory(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Reports'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentReports(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'report_category_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByReportCategoryName(Ext.getCmp('report_category_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'report_category_go_button',
					handler: function(){
						SearchByReportCategoryName(Ext.getCmp('report_category_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchReportCategory();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_report_categories,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit_report_category').enable();
		p.getTopToolbar().findById('delete_report_category').enable();
		p.getTopToolbar().findById('view_report_category').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_report_category').disable();
			p.getTopToolbar().findById('view_report_category').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit_report_category').disable();
			p.getTopToolbar().findById('view_report_category').disable();
			p.getTopToolbar().findById('delete_report_category').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit_report_category').enable();
			p.getTopToolbar().findById('view_report_category').enable();
			p.getTopToolbar().findById('delete_report_category').enable();
		}
		else{
			p.getTopToolbar().findById('edit_report_category').disable();
			p.getTopToolbar().findById('view_report_category').disable();
			p.getTopToolbar().findById('delete_report_category').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_report_categories.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
