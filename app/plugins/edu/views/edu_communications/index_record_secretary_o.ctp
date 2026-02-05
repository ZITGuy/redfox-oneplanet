//<script>
    var sel_edu_section_id = '';
    
    var store_communication_records = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'student', 'identity_number', 'comment'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'list_data_records')); ?>'	
		})
    });

    function RefreshEduCommunicationRecordsData() {
        if(sel_edu_section_id!=''){
            store_communication_records.reload({
				params: {
                    start: 0,
                    edu_section_id: sel_edu_section_id,
				}
            });
		}
    }
	
	function applyToAll() {
		var txtDefaultMessage = Ext.getCmp('data[EduCommunication][default_comment]');
		var defaultMessage = txtDefaultMessage.getValue();
		
		if(defaultMessage != '') {
			var records = store_communication_records.getRange();     
			var params = {};
			for(var i = 0; i < records.length; i++) {
				records[i].set('comment', defaultMessage);
			}
			Ext.getCmp('btnSaveAll').enable();
		}
	}

    <?php
        $this->ExtForm->create('EduCommunication');
        $this->ExtForm->defineFieldFunctions();
    ?>
	var store_sections = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'		
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_sections', 'action' => 'list_data')); ?>'
		})
	});

	var EduCommunicationTopForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'',
		//defaultType: 'textfield',

		items: [
            <?php
				$options = array('fieldLabel' => 'Class');
				$options['items'] = $edu_classes;
				$options['listeners'] = "{
					scope: this,
					'select': function(combo, record, index){
						var edu_section_id = Ext.getCmp('edu_section_id');
						edu_section_id.setValue('');
						edu_section_id.store.removeAll();
						edu_section_id.store.reload({
							params: {
								edu_class_id : combo.getValue()
							}
						});
						
                        sel_edu_section_id = '';
						g.getStore().removeAll();

                        Ext.getCmp('btnViewDetail').disable();
                        Ext.getCmp('btnSubmitAllCommunication').disable();
					}
				}";
                $options['anchor'] = '45%';
				$this->ExtForm->input('edu_class_id', $options);
            ?>, {
                xtype: 'combo',
                name: 'edu_section_id',
                hiddenName: 'data[EduCommunication][edu_section_id]',
                id:'edu_section_id',
                typeAhead: true,
                store : store_sections,
                displayField : 'name',
                valueField : 'id',
                anchor:'45%',
                fieldLabel: '<span style="color:red;">*</span> Section',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Section',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function(combo, record, index){ 
                        sel_edu_section_id = combo.getValue();
						
						RefreshEduCommunicationRecordsData();

                        Ext.getCmp('btnViewDetail').disable();
                        Ext.getCmp('btnSubmitAllCommunication').disable();
                    }
                }
            },
			<?php 
				$options = array(
					'fieldLabel' => 'Default Message', 
					'id' => 'data[EduCommunication][default_comment]',
					'anchor' => '70%',
					'listeners' => '{
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								applyToAll();
							}
						}
					}'
				);
				$this->ExtForm->input('default_comment', $options);
			?>
        ]
    });

    var fm = Ext.form;
    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
                header: 'Student',
                dataIndex: 'student',
                width: 150,
                sortable: false
            }, {
                header: 'Student ID',
                dataIndex: 'identity_number',
                width: 150,
                sortable: false
            }, {
                header: 'Comment',
                dataIndex: 'comment',
                width: 150,
                sortable: false,
                align: 'left',
                editor: new Ext.form.TextField({
					allowBlank: false
				})
            }
        ]
    });
	
    var g = new Ext.grid.EditorGridPanel({
        title: '<?php __('PTC Communication'); ?>',
        cm: cm,
        store: store_communication_records,
        loadMask: true,
        stripeRows: true,
        clicksToEdit: 1,
        height: 295,
        anchor: '100%',
        id: 'communicationGrid',
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
            cellclick: function(grid, rowIndex, columnIndex, e){
                
            }
        }
    });
    
    g.on('afteredit', afterEdit, this );
    
    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveAll').enable();
        }
    }
    
    var EduCommunicationWindow = new Ext.Window({
        title: 'PTC Communication',
        width: 700,
        height: 455,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        modal: true,
        items: [
            EduCommunicationTopForm, g
        ],

        buttons: [{
            text: 'Save',
            id: 'btnSaveAll',
            disabled: true,
            handler : function(){
                EduCommunicationTopForm.disable();
                g.disable();
                var records = store_communication_records.getRange();     
                var params = {};
                for(var i = 0; i < records.length; i++) {
                    params['data['+i+'][id]'] = Ext.encode(records[i].get('id'));
                    params['data['+i+'][comment]'] = Ext.encode(records[i].get('comment'));
                }
                        
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array('controller' => 'edu_communications', 'action' => 'save_communication_records')); ?>',
                    params: params,
                    method: 'POST',
                    success: function(){
                        Ext.Msg.alert("<?php __('Success'); ?>", "<?php __('PTC Records created/updated successfully!'); ?>");
                        EduCommunicationTopForm.enable();
                        g.enable();
                        RefreshEduCommunicationRecordsData();
                    },
                    failure: function(){
                        alert('Error Saving PTC, Please Try Again!');
                        EduCommunicationTopForm.enable();
                        g.enable();
                    }
                });
            }
        }, {
            text: 'Close',
            handler: function(btn){
                EduCommunicationWindow.close();
            }
        }]
    });
    EduCommunicationWindow.show();
