//<script>
    var store_parent_eduEvaluations = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root: 'rows',
            totalProperty: 'results',
            fields: [
                'id', 'edu_class', 'edu_evaluation_area', 'edu_evaluation_category', 'order_level', 'created', 'modified'
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'list_data', $parent_id)); ?>'})
    });


    function AddParentEduEvaluation() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'add', $parent_id)); ?>',
            success: function (response, opts) {
                var parent_eduEvaluation_data = response.responseText;

                eval(parent_eduEvaluation_data);

                EduEvaluationAddWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation add form. Error code'); ?>: ' + response.status);
            }
        });
    }
	
	function AddPlusParentEduEvaluation() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'add_plus', $parent_id)); ?>',
            success: function (response, opts) {
                var parent_eduEvaluation_data = response.responseText;

                eval(parent_eduEvaluation_data);

                EduEvaluationAddPlusWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentEduEvaluation(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'edit')); ?>/' + id + '/<?php echo $parent_id; ?>',
            success: function (response, opts) {
                var parent_eduEvaluation_data = response.responseText;

                eval(parent_eduEvaluation_data);

                EduEvaluationEditWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function ViewEduEvaluation(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'view')); ?>/' + id,
            success: function (response, opts) {
                var eduEvaluation_data = response.responseText;

                eval(eduEvaluation_data);

                EduEvaluationViewWindow.show();
            },
            failure: function (response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Evaluation view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteParentEduEvaluation(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'delete')); ?>/' + id,
            success: function (response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Evaluation(s) successfully deleted!'); ?>');
                RefreshParentEduEvaluationData();
            },
            failure: function (response, opts) {
                var obj = Ext.decode(Ext.decode(JSON.stringify(response)).responseText);
                ShowErrorBox(obj.errormsg, obj.helpcode);
            }
        });
    }

    function SearchByParentEduEvaluationName(value) {
        var conditions = '\'EduEvaluation.name LIKE\' => \'%' + value + '%\'';
        store_parent_eduEvaluations.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
            }
        });
    }

    function RefreshParentEduEvaluationData() {
        store_parent_eduEvaluations.reload();
    }



    var g = new Ext.grid.GridPanel({
        title: '<?php __('Evaluations'); ?>',
        store: store_parent_eduEvaluations,
        loadMask: true,
        stripeRows: true,
        height: 300,
        anchor: '100%',
        id: 'eduEvaluationGrid',
        columns: [
            {header: "<?php __('Class'); ?>", dataIndex: 'edu_class', sortable: true},
            {header: "<?php __('Eval. Area'); ?>", dataIndex: 'edu_evaluation_area', sortable: true},
            {header: "<?php __('Eval. Category'); ?>", dataIndex: 'edu_evaluation_category', sortable: true},
            {header: "<?php __('Order'); ?>", dataIndex: 'order_level', sortable: true},
            {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true, hidden: true},
            {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true, hidden: true}],
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: [/*{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip: '<?php __('<b>Add Evaluation</b><br />Click here to create a new Evaluation'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function (btn) {
                        AddParentEduEvaluation();
                    }
                }, ' ', '-', ' ', */{
                    xtype: 'tbbutton',
                    text: '<?php __('Add+'); ?>',
                    tooltip: '<?php __('<b>Add Multiple Evaluations</b><br />Click here to create Evaluations by selecting category.'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function (btn) {
                        AddPlusParentEduEvaluation();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-eduEvaluation',
                    tooltip: '<?php __('<b>Edit Evaluation</b><br />Click here to modify the selected Evaluation'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function (btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()) {
                            EditParentEduEvaluation(sel.data.id);
                        }
                        ;
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-eduEvaluation',
                    tooltip: '<?php __('<b>Delete Evaluation(s)</b><br />Click here to remove the selected Evaluation(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function (btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()) {
                            if (sel.length == 1) {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Evaluation'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> ' + sel[0].data.name + '?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            DeleteParentEduEvaluation(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove EduEvaluation'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Evaluation'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function (btn) {
                                        if (btn == 'yes') {
                                            var sel_ids = '';
                                            for (i = 0; i < sel.length; i++) {
                                                if (i > 0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentEduEvaluation(sel_ids);
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
                    id: 'parent_eduEvaluation_search_field',
                    listeners: {
                        specialkey: function (field, e) {
                            if (e.getKey() == e.ENTER) {
                                SearchByParentEduEvaluationName(Ext.getCmp('parent_eduEvaluation_search_field').getValue());
                            }
                        }

                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip: '<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_eduEvaluation_go_button',
                    handler: function () {
                        SearchByParentEduEvaluationName(Ext.getCmp('parent_eduEvaluation_search_field').getValue());
                    }
                }, ' '
            ]}),
        bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_eduEvaluations,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
        })
    });
    g.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
        g.getTopToolbar().findById('edit-parent-eduEvaluation').enable();
        g.getTopToolbar().findById('delete-parent-eduEvaluation').enable();
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduEvaluation').disable();
        }
    });
    g.getSelectionModel().on('rowdeselect', function (sm, rowIdx, r) {
        if (this.getSelections().length > 1) {
            g.getTopToolbar().findById('edit-parent-eduEvaluation').disable();
            g.getTopToolbar().findById('delete-parent-eduEvaluation').enable();
        } else if (this.getSelections().length == 1) {
            g.getTopToolbar().findById('edit-parent-eduEvaluation').enable();
            g.getTopToolbar().findById('delete-parent-eduEvaluation').enable();
        } else {
            g.getTopToolbar().findById('edit-parent-eduEvaluation').disable();
            g.getTopToolbar().findById('delete-parent-eduEvaluation').disable();
        }
    });



    var parentEduEvaluationsViewWindow = new Ext.Window({
        title: 'Evaluations Under the selected Class',
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
                    parentEduEvaluationsViewWindow.close();
                }
            }]
    });

    store_parent_eduEvaluations.load({
        params: {
            start: 0,
            limit: list_size
        }
    });