// <script>
	var store_parent_eduExemptions = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id','edu_student','edu_course','edu_academic_year', 'edu_quarter','created','modified'	
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'list_data', $parent_id)); ?>'	})
	});

	function AddParentEduExemption() {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'add', $parent_id)); ?>',
			success: function(response, opts) {
				var parent_eduExemption_data = response.responseText;
				
				eval(parent_eduExemption_data);
				
				EduExemptionAddWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Exemption add form. Error code'); ?>: ' + response.status);
			}
		});
	}

	function EditParentEduExemption(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
			success: function(response, opts) {
				var parent_eduExemption_data = response.responseText;
				
				eval(parent_eduExemption_data);
				
				EduExemptionEditWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Exemption edit form. Error code'); ?>: ' + response.status);
			}
		});
	}

	function ViewEduExemption(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'view')); ?>/'+id,
			success: function(response, opts) {
				var eduExemption_data = response.responseText;

				eval(eduExemption_data);

				EduExemptionViewWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Exemption view form. Error code'); ?>: ' + response.status);
			}
		});
	}


	function DeleteParentEduExemption(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_exemptions', 'action' => 'delete')); ?>/'+id,
			success: function(response, opts) {
				Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Exemption(s) successfully deleted!'); ?>');
				RefreshParentEduExemptionData();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Exemption to be deleted. Error code'); ?>: ' + response.status);
			}
		});
	}

	function SearchByParentEduExemptionName(value){
		var conditions = '\'EduExemption.name LIKE\' => \'%' + value + '%\'';
		store_parent_eduExemptions.reload({
			params: {
				start: 0,
				limit: list_size,
				conditions: conditions
			}
		});
	}

	function RefreshParentEduExemptionData() {
		store_parent_eduExemptions.reload();
	}



	var g = new Ext.grid.GridPanel({
		title: '<?php __('Course Exemptions'); ?>',
		store: store_parent_eduExemptions,
		loadMask: true,
		stripeRows: true,
		height: 300,
		anchor: '100%',
		id: 'eduExemptionGrid',
		columns: [
			{header: "<?php __('Course'); ?>", dataIndex: 'edu_course', sortable: true},
			{header: "<?php __('Academic Year'); ?>", dataIndex: 'edu_academic_year', sortable: true},
			{header: "<?php __('Term'); ?>", dataIndex: 'edu_quarter', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}	],
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
					tooltip:'<?php __('<b>Add Exemption</b><br />Click here to create a new Exemption'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddParentEduExemption();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-parent-eduExemption',
					tooltip:'<?php __('<b>Delete Exemption(s)</b><br />Click here to remove the selected Exemption(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = g.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Exemption'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.edu_course+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteParentEduExemption(sel[0].data.id);
										}
									}
								});
							} else {
								Ext.Msg.show({
									title: '<?php __('Remove Exemption'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Exemption'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteParentEduExemption(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' '
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_parent_eduExemptions,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		g.getTopToolbar().findById('delete-parent-eduExemption').enable();
	});
	g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length >= 1){
			g.getTopToolbar().findById('delete-parent-eduExemption').enable();
		} else{
			g.getTopToolbar().findById('delete-parent-eduExemption').disable();
		}
	});


	var parentEduExemptionsViewWindow = new Ext.Window({
		title: 'Course Exemptions',
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
				parentEduExemptionsViewWindow.close();
			}
		}]
	});

	store_parent_eduExemptions.load({
		params: {
			start: 0,    
			limit: list_size
		}
	});