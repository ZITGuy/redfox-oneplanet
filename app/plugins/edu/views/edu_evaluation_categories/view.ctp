
var store_eduEvaluationCategory_eduEvaluationAreas = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','edu_evaluation_category','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluationAreas', 'action' => 'list_data', $edu_evaluation_category['EduEvaluationCategory']['id'])); ?>'	})
});
		
<?php $eduEvaluationCategory_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $edu_evaluation_category['EduEvaluationCategory']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('List Order', true) . ":</th><td><b>" . $edu_evaluation_category['EduEvaluationCategory']['list_order'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduEvaluationCategory_view_panel_1 = {
			html : '<?php echo $eduEvaluationCategory_html; ?>',
			frame : true,
			height: 80
		}
		var eduEvaluationCategory_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduEvaluationCategory_eduEvaluationAreas,
				title: '<?php __('Evaluation Areas'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduEvaluationCategory_eduEvaluationAreas.getCount() == '')
							store_eduEvaluationCategory_eduEvaluationAreas.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Evaluation Category'); ?>", dataIndex: 'edu_evaluation_category', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduEvaluationCategory_eduEvaluationAreas,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduEvaluationCategoryViewWindow = new Ext.Window({
			title: '<?php __('View Evaluation Category'); ?>: <?php echo $edu_evaluation_category['EduEvaluationCategory']['name']; ?>',
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
				eduEvaluationCategory_view_panel_1,
				eduEvaluationCategory_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduEvaluationCategoryViewWindow.close();
				}
			}]
		});
