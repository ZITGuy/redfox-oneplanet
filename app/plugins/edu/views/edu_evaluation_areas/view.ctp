
var store_eduEvaluationArea_eduEvaluations = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_class','edu_evaluation_area','order_level','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduEvaluations', 'action' => 'list_data', $eduEvaluationArea['EduEvaluationArea']['id'])); ?>'	})
});
		
<?php $eduEvaluationArea_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduEvaluationArea['EduEvaluationArea']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Evaluation Category', true) . ":</th><td><b>" . $eduEvaluationArea['EduEvaluationCategory']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduEvaluationArea['EduEvaluationArea']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduEvaluationArea['EduEvaluationArea']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduEvaluationArea_view_panel_1 = {
			html : '<?php echo $eduEvaluationArea_html; ?>',
			frame : true,
			height: 80
		}
		var eduEvaluationArea_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduEvaluationArea_eduEvaluations,
				title: '<?php __('EduEvaluations'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduEvaluationArea_eduEvaluations.getCount() == '')
							store_eduEvaluationArea_eduEvaluations.reload();
					}
				},
				columns: [
					{header: "<?php __('Edu Class'); ?>", dataIndex: 'edu_class', sortable: true}
,					{header: "<?php __('Edu Evaluation Area'); ?>", dataIndex: 'edu_evaluation_area', sortable: true}
,					{header: "<?php __('Order Level'); ?>", dataIndex: 'order_level', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduEvaluationArea_eduEvaluations,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var EduEvaluationAreaViewWindow = new Ext.Window({
			title: '<?php __('View EduEvaluationArea'); ?>: <?php echo $eduEvaluationArea['EduEvaluationArea']['name']; ?>',
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
				eduEvaluationArea_view_panel_1,
				eduEvaluationArea_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduEvaluationAreaViewWindow.close();
				}
			}]
		});
