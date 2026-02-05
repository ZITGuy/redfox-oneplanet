//<script>
var store_eduEvaluation_eduRegistrationEvaluations = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration','edu_evaluation','edu_quarter','edu_evaluation_value','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'eduRegistrationEvaluations', 'action' => 'list_data', $eduEvaluation['EduEvaluation']['id'])); ?>'	})
});
		
<?php $eduEvaluation_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Edu Class', true) . ":</th><td><b>" . $eduEvaluation['EduClass']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Edu Evaluation Area', true) . ":</th><td><b>" . $eduEvaluation['EduEvaluationArea']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Order Level', true) . ":</th><td><b>" . $eduEvaluation['EduEvaluation']['order_level'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $eduEvaluation['EduEvaluation']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $eduEvaluation['EduEvaluation']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var eduEvaluation_view_panel_1 = {
			html : '<?php echo $eduEvaluation_html; ?>',
			frame : true,
			height: 80
		}
		var eduEvaluation_view_panel_2 = new Ext.TabPanel({
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
				store: store_eduEvaluation_eduRegistrationEvaluations,
				title: '<?php __('EduRegistrationEvaluations'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_eduEvaluation_eduRegistrationEvaluations.getCount() == '')
							store_eduEvaluation_eduRegistrationEvaluations.reload();
					}
				},
				columns: [
					{header: "<?php __('Registration'); ?>", dataIndex: 'edu_registration', sortable: true},
					{header: "<?php __('Evaluation'); ?>", dataIndex: 'edu_evaluation', sortable: true},
					{header: "<?php __('Quarter'); ?>", dataIndex: 'edu_quarter', sortable: true},
					{header: "<?php __('Evaluation Value'); ?>", dataIndex: 'edu_evaluation_value', sortable: true},
					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
					{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_eduEvaluation_eduRegistrationEvaluations,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}]
		});

		var EduEvaluationViewWindow = new Ext.Window({
			title: '<?php __('View Evaluation'); ?>: <?php echo $eduEvaluation['EduEvaluation']['id']; ?>',
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
				eduEvaluation_view_panel_1,
				eduEvaluation_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduEvaluationViewWindow.close();
				}
			}]
		});
