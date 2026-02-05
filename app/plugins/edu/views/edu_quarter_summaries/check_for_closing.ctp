//<script>
    var store_quarter_sections = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'issue','status'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_quarters', 'action' => 'list_data_check_closing')); ?>'
        }),
        listeners: {
            'load': function (st, records, options){
                Ext.getCmp('btnRunEOQ').enable();
                for(i=0; i<records.length; i++){
                    if(records[i].get('status') ==
                        "<img src='/fredfox/img/symbol_delete.png' title='Not Completed!' alt='' />") {
                        Ext.getCmp('btnRunEOQ').disable();
                        break;
                    }
                }
            }
        }
    });

    function CloseQuarter() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_quarters', 'action' => 'close_quarter')); ?>/',
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Quarter closed successfully!'); ?>');
                RefreshParentEduQuarterData();
                parentEduQuartersCheckForClosingWindow.close();
				location.reload();
            },
            failure: function(response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }
    
    var my_g = new Ext.grid.GridPanel({
        title: '<?php __('Quarters'); ?>',
        store: store_quarter_sections,
        loadMask: true,
        stripeRows: true,
        height: 380,
        anchor: '100%',
        id: 'eduQuarterClosingGrid',
        columns: [
            {header: "<?php __('Checking Issue'); ?>", dataIndex: 'issue', sortable: true, width: '200'},
            {header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true, width: '80'}
        ],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            celldblclick: function(){
                //ViewEduQuarter(Ext.getCmp('eduQuarterGrid').getSelectionModel().getSelected().data.id);
            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_quarter_sections,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });

    var parentEduQuartersCheckForClosingWindow = new Ext.Window({
        title: 'Quarter Checking',
        width: 650,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: [
            my_g
        ],

        buttons: [{
                text: 'Run EOQ',
                id: 'btnRunEOQ',
                handler: function(btn){
                    CloseQuarter();
                }
            }, {
                text: 'Cancel',
                handler: function(btn){
                    parentEduQuartersCheckForClosingWindow.close();
                }
            }]
    });

    store_quarter_sections.load({
        params: {
            start: 0,
            limit: list_size
        }
    });
    
    store_quarter_sections.on({
        'load': onStoreLoaded,
        scope: this
    });
    
    function onStoreLoaded() {
        var all_ok = true;
        for(i = 0; i< store_quarter_sections.data.length; i++) {
            record = store_quarter_sections.getAt(i);
            if(record.get('status') != "<img src='/smis/img/symbol_check.png' title='Completed!' alt='' />"){
                all_ok = false;
                break;
            }
        }
        if(all_ok) {
            my_g.getTopToolbar().findById('close-parent-eduQuarter').enable();
        }
    }