//<script>
    var selected_class='';
    var selected_section='';
	var selected_evaluation='';

    var store_registration_evaluations = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id','student_name','evaluation_value'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registration_evaluations', 'action' => 'list_data_for_evaluation')); ?>'
		})
    });

    function RefreshParentRegistrationEvaluationData() {
        if(selected_evaluation!='' & selected_section!=''){
            store_registration_evaluations.reload({
				params: {
                    start: 0,
                    selected_section_id: selected_section,
					selected_evaluation_id: selected_evaluation
				}
            });
		}
    }

    <?php
        $this->ExtForm->create('RegistrationEvaluation');
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
	
	var store_evaluations = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'list_data2')); ?>'
		})
	});

	var EvaluationSelectionForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',
		defaultType: 'textfield',

		items: [
                <?php
					$options = array('fieldLabel' => 'Class', 'anchor' => '50%');
					$options['items'] = $classes;
					$options['listeners'] = "{
						scope: this,
						'select': function(combo, record, index){
							var section = Ext.getCmp('section_id');
							section.setValue('');
							section.store.removeAll();
							section.store.reload({
								params: {
									edu_class_id : combo.getValue()
								}
							});
							
							var evaluation = Ext.getCmp('evaluation_id');
							evaluation.setValue('');
							evaluation.store.removeAll();
							evaluation.store.reload({
								params: {
									edu_class_id : combo.getValue()
								}
							});
							g.getStore().removeAll();
						}
					}";
					$this->ExtForm->input('class_id', $options);
			?>, {
                xtype: 'combo',
                emptyText: 'All',
                name: 'section_id',
                hiddenName: 'data[RegistrationEvaluation][section_id]',
                id:'section_id',
                typeAhead: true,
                store : store_sections,
                displayField : 'name',
                valueField : 'id',
                anchor:'50%',
                fieldLabel: '<span style="color:red;">*</span> Section',
                mode: 'local',
                allowBlank: false,
                emptyText: 'Select Section',
                editable: false,
                triggerAction: 'all',
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        selected_section=combo.getValue();
                        RefreshParentRegistrationEvaluationData();
                    }
                }
            }, {
                xtype: 'combo',
                name: 'evaluation_id',
                hiddenName: 'data[RegistrationEvaluation][evaluation_id]',
                typeAhead: true,
                emptyText: 'Select One',
                editable: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'local',
                valueField: 'id',
                displayField: 'name',
                allowBlank: false,
                anchor: '80%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Evaluation',
                id:'evaluation_id',
                store : store_evaluations,
                listeners:{
                    scope: this,
                    'select': function (combo, record, index) {
                        selected_evaluation=combo.getValue();
                        RefreshParentRegistrationEvaluationData();
                    }
                }
            }
        ]
    });
	
	var element = document.createElement('div');
	element.id = "EvaluationValueHtmlCombo";
	document.body.appendChild(element);
	
	var cont='';
	cont='<select name="evaluation_values" id="evaluation_values" style="display:none;">';
	<?php foreach ($edu_evaluation_values as $gl) { ?>
		cont+='<option value="<?php
            echo $gl['EduEvaluationValue']['description']; ?>">'+
                '<?php echo $gl['EduEvaluationValue']['description']; ?></option>';
	<?php } ?>
	cont+='</select>';
	document.getElementById("EvaluationValueHtmlCombo").innerHTML=cont;
	
	var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [
            {header: "<?php __('Student Name'); ?>", dataIndex: 'student_name', sortable: true},
            {header: "<?php __('Evaluation Value'); ?>",
                dataIndex: 'evaluation_value',
                sortable: true,
                editor: new Ext.form.ComboBox({
                    typeAhead: true,
                    triggerAction: 'all',
                    transform: 'evaluation_values',
                    lazyRender: true,
                    listClass: 'x-combo-list-small'
            })}
        ]
    });
	
		
    var g = new Ext.grid.EditorGridPanel({
        title: '<?php __('Student Evaluation Results'); ?>',
        store: store_registration_evaluations,
        loadMask: true,
        stripeRows: true,
        height: 283,
        anchor: '100%',
		clicksToEdit: 1,
        id: 'evaluationGrid',
        cm: cm,
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        viewConfig: {
            forceFit: true
        },
        tbar: new Ext.Toolbar({
            items: []
		})
    });

    g.on('afteredit', afterEdit, this );

    function afterEdit(e) {
        if(!(e.originalValue === e.value)){
            Ext.getCmp('btnSaveChanges').enable();
        }
    }

    var ManageRegistrationEvaluationsWindow = new Ext.Window({
        title: 'Evaluation Management Form',
        width: 700,
        height: 440,
        resizable: false,
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'right',
        modal: true,
        items: [
            EvaluationSelectionForm, g
        ],
        buttons: [{
            text: 'Save Changes',
            id: 'btnSaveChanges',
            disabled: true,
            handler : function(){
                Ext.getCmp('btnSaveChanges').disable();
                //EvaluationSelectionForm.el.mask('Please wait', 'x-mask-loading');
                g.el.mask('Please wait', 'x-mask-loading');
                var records = store_registration_evaluations.getRange(), fields = store_registration_evaluations.fields;
                var param = {};
                for(var i = 0; i < records.length; i++) {
                    for(var j = 0; j < fields.length; j++){
                        param[ 'data['+i + '][' + fields['items'][j]['name'] +']'] =
                            Ext.encode(records[i].get(fields['items'][j]['name']));
                    }
                }
                Ext.Ajax.request({
                    url: '<?php echo $this->Html->url(array(
                        'controller' => 'edu_registration_evaluations', 'action' => 'save_changes')); ?>',
                    params: param,
                    method: 'POST',
                    success: function(){
                        g.getStore().removeAll();
                        g.el.unmask();
                    },
                    failure: function(){
                        alert('Error Saving Changes, Please Try Again!');
                        g.el.unmask();
                    }
                });
            }
        }, {
			text: 'Close',
			handler: function(btn){
				ManageRegistrationEvaluationsWindow.close();
			}
		}]
    });
    ManageRegistrationEvaluationsWindow.show();
