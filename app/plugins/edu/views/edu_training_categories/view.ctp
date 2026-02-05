
var store_eduTrainingCategory_eduTrainings = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_training_category','deleted','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduTrainings', 'action' => 'list_data', $eduTrainingCategory['EduTrainingCategory']['id'])); ?>'	})
});
		
<?php $eduTrainingCategory_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduTrainingCategory['EduTrainingCategory']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Deleted', true) . ":</th><td><b>" . $eduTrainingCategory['EduTrainingCategory']['deleted'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduTrainingCategory['EduTrainingCategory']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduTrainingCategory['EduTrainingCategory']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduTrainingCategory_view_panel_1 = {
			html : '<?php echo $eduTrainingCategory_html; ?>',
			frame : true,
			height: 80
		}
		var eduTrainingCategory_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_eduTrainingCategory_eduTrainings,
				title: '<?php __('EduTrainings'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduTrainingCategory_eduTrainings.getCount() == '')
							store_eduTrainingCategory_eduTrainings.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Edu Training Category'); ?>", dataIndex: 'edu_training_category', sortable: true}
,					{header: "<?php __('Deleted'); ?>", dataIndex: 'deleted', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduTrainingCategory_eduTrainings,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduTrainingCategoryViewWindow = new Ext.Window({
			title: '<?php __('View EduTrainingCategory'); ?>: <?php echo $eduTrainingCategory['EduTrainingCategory']['name']; ?>',
			width: 500,
			height:345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				eduTrainingCategory_view_panel_1,
				eduTrainingCategory_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduTrainingCategoryViewWindow.close();
				}
			}]
		});
