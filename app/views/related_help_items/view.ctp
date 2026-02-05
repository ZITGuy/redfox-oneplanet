
var store_relatedHelpItem_relatedHelpItems = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','help_item','related_help_item'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'relatedHelpItems', 'action' => 'list_data', $relatedHelpItem['RelatedHelpItem']['id'])); ?>'	})
});
		
<?php $relatedHelpItem_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Help Item', true) . ":</th><td><b>" . $relatedHelpItem['HelpItem']['title'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Related Help Item', true) . ":</th><td><b>" . $relatedHelpItem['RelatedHelpItem']['id'] . "</b></td></tr>" . 
"</table>"; 
?>
		var relatedHelpItem_view_panel_1 = {
			html : '<?php echo $relatedHelpItem_html; ?>',
			frame : true,
			height: 80
		}
		var relatedHelpItem_view_panel_2 = new Ext.TabPanel({
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
				store: store_relatedHelpItem_relatedHelpItems,
				title: '<?php __('RelatedHelpItems'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_relatedHelpItem_relatedHelpItems.getCount() == '')
							store_relatedHelpItem_relatedHelpItems.reload();
					}
				},
				columns: [
					{header: "<?php __('Help Item'); ?>", dataIndex: 'help_item', sortable: true}
,					{header: "<?php __('Related Help Item'); ?>", dataIndex: 'related_help_item', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_relatedHelpItem_relatedHelpItems,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var RelatedHelpItemViewWindow = new Ext.Window({
			title: '<?php __('View RelatedHelpItem'); ?>: <?php echo $relatedHelpItem['RelatedHelpItem']['id']; ?>',
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
				relatedHelpItem_view_panel_1,
				relatedHelpItem_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					RelatedHelpItemViewWindow.close();
				}
			}]
		});
