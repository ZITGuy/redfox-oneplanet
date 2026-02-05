//<script>
    var store_edu_campuses = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'address', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'list_data')); ?>"
        }),
        sortInfo: {field: 'name', direction: "ASC"},
        groupField: 'address'
    });

    function ViewEduCampus(id) {
        Ext.Ajax.request({
            url: "<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'view')); ?>/" + id,
            success: function (response, opts) {
                var eduCampus_data = response.responseText;

                eval(eduCampus_data);

                EduCampusViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus view form. Error code'); ?>: " + response.status);
            }
        });
    }

    function SearchEduCampus() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_campuses', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var eduCampus_data = response.responseText;

                eval(eduCampus_data);

                eduCampusSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert("<?php __('Error'); ?>", "<?php __('Cannot get the Campus search form. Error Code'); ?>: " + response.status);
            }
        });
    }

    function SearchByEduCampusName(value) {
        var conditions = '\'EduCampus.name LIKE\' => \'%' + value + '%\'';
        store_edu_campuses.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshEduCampusData() {
        store_edu_campuses.reload();
    }


    if (center_panel.find('id', 'edu_campus_tab_v') != "") {
        var p = center_panel.findById('edu_campus_tab_v');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Campuses'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'edu_campus_tab_v',
            xtype: 'grid',
            store: store_edu_campuses,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Address'); ?>", dataIndex: 'address', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Campuses" : "Campus"]})'
            }),
            listeners: {
                celldblclick: function () {
                    ViewEduCampus(Ext.getCmp('edu_campus_tab_v').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: "<?php __('View Campus'); ?>",
                        id: 'view-eduCampus_v',
                        tooltip: "<?php __('<b>View Campus</b><br />Click here to see details of the selected Campus'); ?>",
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewEduCampus(sel.data.id);
                            }
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: "<?php __('[Search By Name]'); ?>",
                        id: 'eduCampus_search_field_v',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByEduCampusName(Ext.getCmp('eduCampus_search_field_v').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('GO'); ?>",
                        tooltip: "<?php __('<b>GO</b><br />Click here to get search results'); ?>",
                        id: "eduCampus_go_button_v",
                        handler: function () {
                            SearchByEduCampusName(Ext.getCmp('eduCampus_search_field_v').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: "<?php __('Advanced Search'); ?>",
                        tooltip: "<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>",
                        handler: function () {
                            SearchEduCampus();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_edu_campuses,
                displayInfo: true,
                displayMsg: "<?php __('Displaying {0} - {1} of {2}'); ?>",
                beforePageText: "<?php __('Page'); ?>",
                afterPageText: "<?php __('of {0}'); ?>",
                emptyMsg: "<?php __('No data to display'); ?>"
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('view-eduCampus_v').enable();
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 0) {
                p.getTopToolbar().findById('view-eduCampus_v').disable();
            } else {
                p.getTopToolbar().findById('view-eduCampus_v').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_edu_campuses.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }