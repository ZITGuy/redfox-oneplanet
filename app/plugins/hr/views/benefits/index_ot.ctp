//<script>
    var store_parent_ot_benefits = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','Measurement','amount','grade','start_date','end_date'	
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'benefits', 'action' => 'list_data_ot', $parent_id)); ?>'	
        })
    });

    function AddOTBenefit() {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'benefits', 'action' => 'add_ot', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_benefit_data = response.responseText;

                eval(parent_benefit_data);

                OTBenefitAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the OT benefit add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function DeleteOTBenefit(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'benefits', 'action' => 'remove')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Benefit(s) successfully deleted!'); ?>');
                RefreshParentBenefitData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the benefit to be deleted. Error code'); ?>: ' + response.status);
            }
	});
    }

    function RefreshParentBenefitData() {
	store_parent_ot_benefits.reload();
    }

    var g = new Ext.grid.GridPanel({
	title: '<?php __('Benefits'); ?>',
	store: store_parent_ot_benefits,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
        id: 'benefitGrid',
	columns: [
            {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
            {header: "<?php __('Measurement'); ?>", dataIndex: 'Measurement', sortable: true},
            {header: "<?php __('Amount'); ?>", dataIndex: 'amount', sortable: true},
            {header: "<?php __('Start Date'); ?>", dataIndex: 'start_date', sortable: true},
            {header: "<?php __('End Date'); ?>", dataIndex: 'end_date', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
	}),
	viewConfig: {
            forceFit: true
	},
        listeners: {
            celldblclick: function(){
                ViewBenefit(Ext.getCmp('benefitGrid').getSelectionModel().getSelected().data.id);
            }
        },
	tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip:'<?php __('<b>Add Benefit</b><br />Click here to create a new Benefit'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddOTBenefit();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-benefit',
                    tooltip:'<?php __('<b>Delete Benefit(s)</b><br />Click here to remove the selected Benefit(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()){
                            if(sel.length==1){
                                Ext.Msg.show({
                                    title: '<?php __('Remove Benefit'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            DeleteOTBenefit(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Benefit'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Benefit'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            var sel_ids = '';
                                            for(i=0;i<sel.length;i++){
                                                if(i>0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteOTBenefit(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        };
                    }
                }, ' '
            ]}),
	bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_ot_benefits,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
	})
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('delete-parent-benefit').enable();
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length >= 1){
            g.getTopToolbar().findById('delete-parent-benefit').enable();
        } else {
            g.getTopToolbar().findById('delete-parent-benefit').disable();
        }
    });



    var parentOTBenefitsViewWindow = new Ext.Window({
	title: 'OT Benefit of the selected Employee',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
            g
	],

	buttons: [{
		text: 'Close',
		handler: function(btn){
                    parentOTBenefitsViewWindow.close();
		}
            }]
    });

    store_parent_ot_benefits.load({
        params: {
            start: 0,    
            limit: list_size
        }
    });