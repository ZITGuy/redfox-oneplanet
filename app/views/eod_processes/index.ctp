//<script>
var store_eod_processes = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','process_date','user','task1_backup_taken','task2_portal_updated','task3_ftp_sent','backup_type','incremental_count','backup_incremental_file','backup_full_file','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eod_processes', 'action' => 'list_data')); ?>'
	}),
	sortInfo:{field: 'name', direction: "DESC"}
});


function RunEodProcess() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'eod_processes', 'action' => 'run_eod')); ?>',
		success: function(response, opts) {
			var eod_process_data = response.responseText;
			
			eval(eod_process_data);
			
			EodProcessRunWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Eod Process Run form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByEodProcessName(value){
	var conditions = '\'EodProcess.name LIKE\' => \'%' + value + '%\'';
	store_eod_processes.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshEodProcessData() {
	store_eod_processes.reload();
}


if(center_panel.find('id', 'eod_process_tab') != "") {
	var p = center_panel.findById('eod_process_tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Eod Processes'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'eod_process_tab',
		xtype: 'grid',
		store: store_eod_processes,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Process Date'); ?>", dataIndex: 'process_date', sortable: true},
			{header: "<?php __('Backup Taken'); ?>", dataIndex: 'task1_backup_taken', sortable: true},
			{header: "<?php __('Portal Updated'); ?>", dataIndex: 'task2_portal_updated', sortable: true},
			{header: "<?php __('FTP Sent'); ?>", dataIndex: 'task3_ftp_sent', sortable: true},
			{header: "<?php __('Backup Type'); ?>", dataIndex: 'backup_type', sortable: true},
			{header: "<?php __('Incremental Count'); ?>", dataIndex: 'incremental_count', sortable: true, hidden: true},
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
					text: '<?php __('Run EoD'); ?>',
					tooltip:'<?php __('<b>Run EoD Processes</b><br />Click here to create a new EoD Process'); ?>',
					icon: 'img/table_add.png',
					disabled: <?php echo $disable_run; ?>,
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						if(<?php echo "'" . $process_date . "'=='" . $eoq_date . "'" ?>) {
							Ext.Msg.show({
								title: '<?php __('Oooops'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: 'Today is End of Quarter, please run EoQ instead.',
								icon: Ext.MessageBox.ERROR
							});
						} else {
							RunEodProcess();
						}
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Synchronize'); ?>',
					tooltip:'<?php __('<b>Synchronize EoD Processes</b><br />Click here to sync EoD Process data'); ?>',
					icon: 'img/table_import.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						Syncronize();
					}
				}, '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'eod_process_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByEodProcessName(Ext.getCmp('eod_process_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'eod_process_go_button',
					handler: function(){
						SearchByEodProcessName(Ext.getCmp('eod_process_search_field').getValue());
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_eod_processes,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	
	center_panel.setActiveTab(p);
	
	store_eod_processes.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
