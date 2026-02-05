//<script>
    var store_acctTransaction_acctJournals = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','acct_account',
                {name: 'dr', type: 'float'},{name: 'cr', type: 'float'},{name: 'bbf', type: 'float'}		
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'acctJournals', 'action' => 'list_data', $acct_transaction['AcctTransaction']['id'])); ?>'	})
    });
		
    <?php $acctTransaction_html = "<table cellspacing=3>" . 		
            "<tr><th align=right>" . __('Reference', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['name'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Narrative', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['description'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Cheque Number', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['cheque_number'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Invoice Number', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['invoice_number'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Transaction Date', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['transaction_date'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Fiscal Year', true) . ":</th><td><b>" . $acct_transaction['AcctFiscalYear']['name'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Maker', true) . ":</th><td><b>" . $acct_transaction['User']['username'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['created'] . "</b></td></tr>" . 
            "<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $acct_transaction['AcctTransaction']['modified'] . "</b></td></tr>" . 
    "</table>"; 
    ?>
    var acctTransaction_view_panel_1 = {
        html : '<?php echo $acctTransaction_html; ?>',
        frame : true,
        height: 220
    }
    var acctTransaction_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height:190,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
                xtype: 'grid',
                loadMask: true,
                stripeRows: true,
                store: store_acctTransaction_acctJournals,
                title: '<?php __('Journal Entries'); ?>',
                enableColumnMove: false,
                listeners: {
                    activate: function(){
                        if(store_acctTransaction_acctJournals.getCount() == '')
                            store_acctTransaction_acctJournals.reload();
                    }
                },
                columns: [
                    {header: "<?php __('Account'); ?>", dataIndex: 'acct_account', sortable: true},                   
                    {header: "<?php __('DR'); ?>", dataIndex: 'dr', xtype: 'numbercolumn', align: 'right', sortable: true},
                    {header: "<?php __('CR'); ?>", dataIndex: 'cr', xtype: 'numbercolumn', align: 'right', sortable: true},                   
                    {header: "<?php __('BBF'); ?>", dataIndex: 'bbf', xtype: 'numbercolumn', align: 'right', sortable: true}
                ],
                viewConfig: {
                    forceFit: true
                },
                bbar: new Ext.PagingToolbar({
                    pageSize: view_list_size,
                    store: store_acctTransaction_acctJournals,
                    displayInfo: true,
                    displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                    beforePageText: '<?php __('Page'); ?>',
                    afterPageText: '<?php __('of'); ?> {0}',
                    emptyMsg: '<?php __('No data to display'); ?>'
                })
            }			
        ]
    });

    var AcctTransactionViewWindow = new Ext.Window({
        title: '<?php __('View AcctTransaction'); ?>: <?php echo $acct_transaction['AcctTransaction']['name']; ?>',
        width: 500,
        height: 485,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            acctTransaction_view_panel_1,
            acctTransaction_view_panel_2
        ],

        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                AcctTransactionViewWindow.close();
            }
        }]
    });
