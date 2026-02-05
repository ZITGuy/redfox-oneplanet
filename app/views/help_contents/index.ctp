//<script>
    var store_help_contents = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'code', 'content', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'list_data')); ?>'
        }),
        sortInfo: {field: 'name', direction: "ASC"}

    });

    function AddHelpContent() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'add')); ?>',
            success: function (response, opts) {
                var help_content_data = response.responseText;

                eval(help_content_data);

                HelpContentAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Content add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditHelpContent(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'edit')); ?>/' + id,
            success: function (response, opts) {
                var help_content_data = response.responseText;

                eval(help_content_data);

                HelpContentEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Content edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewHelpContent(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'view')); ?>/' + id,
            success: function (response, opts) {
                var help_content_data = response.responseText;

                eval(help_content_data);

                HelpContentViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Content view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteHelpContent(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'delete')); ?>/' + id,
            success: function (response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Help Content successfully deleted!'); ?>');
                RefreshHelpContentData();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Content add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function SearchHelpContent() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'help_contents', 'action' => 'search')); ?>',
            success: function (response, opts) {
                var help_content_data = response.responseText;

                eval(help_content_data);

                helpContentSearchWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Help Content search form. Error Code'); ?>: ' + response.status);
            }
        });
    }

    function SearchByHelpContentName(value) {
        var conditions = '\'HelpContent.name LIKE\' => \'%' + value + '%\'';
        store_help_contents.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshHelpContentData() {
        store_help_contents.reload();
    }

	treePanel.enable();
    if (center_panel.find('id', 'help_content_tab') != "") {
        var p = center_panel.findById('help_content_tab');
        center_panel.setActiveTab(p);
    } else {
        var p = center_panel.add({
            title: '<?php __('Help Contents'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'help_content_tab',
            xtype: 'grid',
            store: store_help_contents,
            columns: [
                {header: "<?php __('Title'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Code'); ?>", dataIndex: 'code', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}
            ],
            viewConfig: {
                forceFit: true
            },
            listeners: {
                celldblclick: function () {
                    ViewHelpContent(Ext.getCmp('help_content_tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip: '<?php __('<b>Add Help Contents</b><br />Click here to create a new Help Content'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function (btn) {
                            AddHelpContent();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit_help_content',
                        tooltip: '<?php __('<b>Edit Help Contents</b><br />Click here to modify the selected Help Content'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                EditHelpContent(sel.data.id);
                            }
                            ;
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete_help_content',
                        tooltip: '<?php __('<b>Delete Help Content(s)</b><br />Click here to remove the selected Help Content(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()) {
                                if (sel.length == 1) {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Help Content'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                DeleteHelpContent(sel[0].data.id);
                                            }
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Help Content'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected Help Contents'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function (btn) {
                                            if (btn == 'yes') {
                                                var sel_ids = '';
                                                for (i = 0; i < sel.length; i++) {
                                                    if (i > 0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteHelpContent(sel_ids);
                                            }
                                        }
                                    });
                                }
                            } else {
                                Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                            }
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbsplit',
                        text: '<?php __('View Help Content'); ?>',
                        id: 'view_help_content',
                        tooltip: '<?php __('<b>View Help Content</b><br />Click here to see details of the selected Help Content'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function (btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()) {
                                ViewHelpContent(sel.data.id);
                            }
                            ;
                        },
                        menu: {
                            items: [
                            ]
                        }
                    }, ' ', '-', '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'help_content_search_field',
                        listeners: {
                            specialkey: function (field, e) {
                                if (e.getKey() == e.ENTER) {
                                    SearchByHelpContentName(Ext.getCmp('help_content_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'help_content_go_button',
                        handler: function () {
                            SearchByHelpContentName(Ext.getCmp('help_content_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip: '<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function () {
                            SearchHelpContent();
                        }
                    }
                ]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_help_contents,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
            p.getTopToolbar().findById('edit_help_content').enable();
            p.getTopToolbar().findById('delete_help_content').enable();
            p.getTopToolbar().findById('view_help_content').enable();
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit_help_content').disable();
                p.getTopToolbar().findById('view_help_content').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
            if (this.getSelections().length > 1) {
                p.getTopToolbar().findById('edit_help_content').disable();
                p.getTopToolbar().findById('view_help_content').disable();
                p.getTopToolbar().findById('delete_help_content').enable();
            } else if (this.getSelections().length == 1) {
                p.getTopToolbar().findById('edit_help_content').enable();
                p.getTopToolbar().findById('view_help_content').enable();
                p.getTopToolbar().findById('delete_help_content').enable();
            } else {
                p.getTopToolbar().findById('edit_help_content').disable();
                p.getTopToolbar().findById('view_help_content').disable();
                p.getTopToolbar().findById('delete_help_content').disable();
            }
        });
        center_panel.setActiveTab(p);

        store_help_contents.load({
            params: {
                start: 0,
                limit: list_size
            }
        });

    }
