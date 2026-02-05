//<script>
    var store_parent_eduQuarterSummaries = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'quarter_name', 'class_name', 'status'	
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarter_summaries', 'action' => 'list_data', $parent_id)); ?>'	
        })
    });

    function RefreshEduQuarterSummaryData() {
        store_parent_eduQuarterSummaries.reload();
    }
    
    function SummarizeQuarter(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarter_summaries', 'action' => 'summarize_the_quarter')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php echo Inflector::pluralize($term_name); ?> <?php __(' class summarized successfully!'); ?>');
                RefreshEduQuarterSummaryData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', 'Cannot summarize the <?php echo $term_name; ?>. Error code: ' + response.status);
            }
        });
    }

    var quarter_summary_g = new Ext.grid.GridPanel({
        title: '<?php echo $edu_quarter['EduQuarter']['name']; ?>',
        store: store_parent_eduQuarterSummaries,
        loadMask: true,
        stripeRows: true,
        height: 380,
        anchor: '100%',
        //id: 'eduQuarterSummaryGrid2',
        columns: [
            {header: "<?php __('Class/Grade'); ?>", dataIndex: 'class_name', sortable: true},
            {header: "<?php __('Status'); ?>", dataIndex: 'status', width: 50, sortable: true}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            celldblclick: function() {
                SummarizeQuarter(Ext.getCmp('eduQuarterGrid').getSelectionModel().getSelected().data.id);
            }
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Summarize'); ?>',
                    id: 'summarizeQuarterSummary',
                    tooltip:'<b>Summarize <?php echo $term_name; ?></b><br />Click here to summarize the selected <?php echo $term_name; ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = quarter_summary_g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            quarter_summary_g.getTopToolbar().findById('summarizeQuarterSummary').disable();
							eduQuartersSummaryWindow.disable();
							SummarizeQuarter(sel.data.id);
							eduQuartersSummaryWindow.enable();
                        }
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduQuarterSummaries,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    var ac = "<img src='/smis/img/symbol_check.png' alt='' /> <font color='lightgreen'><b> Active / Open</b></font>"; 
    quarter_summary_g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        quarter_summary_g.getTopToolbar().findById('summarizeQuarterSummary').disable();
        
        if(this.getSelections().length == 1){
            record = quarter_summary_g.getStore().getAt(rowIdx);
            
			if(record.get('status') == 'PENDING') {
                quarter_summary_g.getTopToolbar().findById('summarizeQuarterSummary').enable();
            }
        }
    });
        
    quarter_summary_g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        quarter_summary_g.getTopToolbar().findById('summarizeQuarterSummary').disable();
        if(this.getSelections().length == 1){
            record = quarter_summary_g.getStore().getAt(rowIdx);
			if(record.get('status') == 'PENDING') {
                quarter_summary_g.getTopToolbar().findById('summarizeQuarterSummary').enable();
            }
        }
    });

    var eduQuartersSummaryWindow = new Ext.Window({
        title: 'Summarize <?php echo $term_name; ?>',
        width: 800,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            quarter_summary_g
        ],

        buttons: [{
                text: 'Close',
                handler: function(btn){
                    eduQuartersSummaryWindow.close();
                }
            }]
    });

    store_parent_eduQuarterSummaries.load({
        params: {
            start: 0,    
            limit: list_size
        }
    });