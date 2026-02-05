//<script>
    var store_parent_eduOutlines = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name', 'edu_course', 'list_order', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'list_data', $parent_id)); ?>'})
    });
    
    function AddParentEduOutline() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'add', $parent_id)); ?>',
            success: function (response, opts) {
                var parent_eduOutline_data = response.responseText;

                eval(parent_eduOutline_data);

                EduOutlineAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Outline add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentEduOutline(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function (response, opts) {
                var parent_eduOutline_data = response.responseText;

                eval(parent_eduOutline_data);

                EduOutlineEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Outline edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteParentEduOutline(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_outlines', 'action' => 'delete')); ?>/' + id,
            success: function (response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Outline(s) successfully deleted!'); ?>');
                RefreshParentEduOutlineData();
            },
            failure: function (response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function SearchByParentEduOutlineName(value) {
        var conditions = '\'EduOutline.name LIKE\' => \'%' + value + '%\'';
        store_parent_eduOutlines.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshParentEduOutlineData() {
        store_parent_eduOutlines.reload();
    }



    var g = new Ext.grid.GridPanel({
        title: '<?php __('Course Outlines'); ?>',
        store: store_parent_eduOutlines,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduOutlineGrid',
        columns: [
            {header: "<?php __('Description'); ?>", dataIndex: 'name', sortable: true},
            {header: "<?php __('Course'); ?>", dataIndex: 'edu_course', sortable: true},
            {header: "<?php __('Order'); ?>", dataIndex: 'list_order', sortable: true},
            {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
            {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('<b>Add Outline</b><br />Click here to create a new Outline'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function (btn) {
                        AddParentEduOutline();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduOutline',
                    tooltip: '<?php __('<b>Edit Outline</b><br />Click here to modify the selected Outline'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function (btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduOutline(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduOutline',
                    tooltip: '<?php __('<b>Delete Outline(s)</b><br />Click here to remove the selected Outline(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function (btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Outline'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduOutline(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Outline'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Outline'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduOutline(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        }
                        ;
                    }
                }, ' ', '->', {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By Name]'); ?>',
                    id: 'parent_eduOutline_search_field',
                    listeners: {
                        specialkey: function (field, e) {
                            if (e.getKey() == e.ENTER) {
                                SearchByParentEduOutlineName(Ext.getCmp('parent_eduOutline_search_field').getValue());
                            }
                        }

                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_eduOutline_go_button',
                    handler: function () {
                        SearchByParentEduOutlineName(Ext.getCmp('parent_eduOutline_search_field').getValue());
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduOutlines,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-eduOutline').enable();
        g.getTopToolbar().findById('delete-parent-eduOutline').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduOutline').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduOutline').disable();
            g.getTopToolbar().findById('delete-parent-eduOutline').enable();
        } else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduOutline').enable();
            g.getTopToolbar().findById('delete-parent-eduOutline').enable();
        } else {
            g.getTopToolbar().findById('edit-parent-eduOutline').disable();
            g.getTopToolbar().findById('delete-parent-eduOutline').disable();
        }
    });



    var parentEduOutlinesViewWindow = new Ext.Window({
        title: 'Course Outline for the selected Course',
        width: 700,
        height: 375,
        minWidth: 700,
        minHeight: 400,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        modal: true,
        items: [
            g
        ],
        buttons: [{
                text: 'Close',
                handler: function (btn) {
                    parentEduOutlinesViewWindow.close();
                }
            }]
    });

    store_parent_eduOutlines.load({
        params: {
            start: 0,
            limit: list_size
        }
    });
