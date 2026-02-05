//<script>
    var store_eduParents = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
				'id','authorized_person','marital_status','primary_parent','secret_code',
				'sms_phone_number','created','modified'		
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'list_data2')); ?>"
		}),	
        sortInfo: {field: 'authorized_person', direction: "ASC"}
    });

    function ViewEduParent(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_parents', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduParent_data = response.responseText;

                eval(eduParent_data);

                EduParentViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the edu_parents view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduParent() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'eduParents', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduParent_data = response.responseText;

                eval(eduParent_data);

                eduParentSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the eduParent search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduParentName(value) {
        var conditions = '\'EduParent.name LIKE\' => \'%' + value + '%\'';
        store_eduParents.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduParentData() {
        store_eduParents.reload();
    }


    if (center_panel.find('id', 'eduParent-tab_v') != "") {
        var p = center_panel.findById('eduParent-tab_v');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('All Parents'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'eduParent-tab_v',
            xtype: 'grid',
            store: store_eduParents,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'authorized_person', sortable: true},
				{header: "<?php __('Marital Status'); ?>", dataIndex: 'marital_status', sortable: true},
				{header: "<?php __('Primary Parent'); ?>", dataIndex: 'primary_parent', sortable: true},
				{header: "<?php __('Secret Code'); ?>", dataIndex: 'secret_code', sortable: true},
				{header: "<?php __('Phone Number'); ?>", dataIndex: 'sms_phone_number', sortable: true},
				{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
				{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true},
                {
                    header:'<?php __('Actions'); ?>',
                    xtype: 'actioncolumn',
                    width: 20,
                    items: [{
                        icon   : 'img/pdf.png',  // Use a URL in the icon config
                        tooltip: 'View Profile',
                        handler: function(grid, rowIndex, colIndex) {
                            var rec = store_eduParents.getAt(rowIndex);
                            ViewEduarentPDF(rec.get('id'));
                        }
                    }]
                }
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function () {
                    ViewEduParent(Ext.getCmp('eduParent-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View Parent Profile'); ?>",
                        id: 'view-eduParent',
                        tooltip: "<?php __('<b>View Parent Profile</b><br />Click here to see details of the selected Parent'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduParent(sel.data.id);
                            }
                            ;
                        }
                    }, '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduParent_search_field',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduParentName(Ext.getCmp('eduParent_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduParent_go_button",
                        handler: function () {
                            SearchByEduParentName(Ext.getCmp('eduParent_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduParent();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_eduParents,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduParent').enable();
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 0) {
                p.getTopToolbar().findById('view-eduParent').enable();
            } else {
                p.getTopToolbar().findById('view-eduParent').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_eduParents.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }
