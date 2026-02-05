//<script>
var store_eduEvaluationValue_eduRegistrationEvaluations = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','edu_registration','edu_evaluation','edu_quarter','edu_evaluation_value','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'edu_registration_evaluations', 'action' => 'list_data', $eduEvaluationValue['EduEvaluationValue']['id'])); ?>'	})
});
		
<?php $eduEvaluationValue_html = "<table cellspacing=3>" . 		
		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $eduEvaluationValue['EduEvaluationValue']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Description', true) . ":</th><td><b>" . $eduEvaluationValue['EduEvaluationValue']['description'] . "</b></td></tr>" . 
"</table>";
?>
		var eduEvaluationValue_view_panel_1 = {
			html : '<?php echo $eduEvaluationValue_html; ?>',
			frame : true,
			height: 80
		}
		var eduEvaluationValue_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_eduEvaluationValue_eduRegistrationEvaluations,
				title: '<?php __('Registration Evaluations'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function() {
						if(store_eduEvaluationValue_eduRegistrationEvaluations.getCount() == '')
							store_eduEvaluationValue_eduRegistrationEvaluations.reload();
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
					store: store_eduEvaluationValue_eduRegistrationEvaluations,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}]
		});

		var EduEvaluationValueViewWindow = new Ext.Window({
			title: '<?php __('View Evaluation Value'); ?>: <?php echo $eduEvaluationValue['EduEvaluationValue']['name']; ?>',
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
				eduEvaluationValue_view_panel_1,
				eduEvaluationValue_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					EduEvaluationValueViewWindow.close();
				}
			}]
		});
