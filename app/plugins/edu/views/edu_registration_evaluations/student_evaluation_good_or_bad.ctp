//<script>
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
        if(selected_evaluation !='' && selected_section !=''){
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

	var store_evaluations = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
			totalProperty: 'results',
			fields: [
				'id', 'name'
			]
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?php echo $this->Html->url(array('controller' => 'edu_evaluations', 'action' => 'list_data_for_section')); ?>'
		})
	});
	
	function RefreshEvaluationValueCombo() {
		if(selected_evaluation != '') {
			Ext.Ajax.request({
				url: '<?php echo $this->Html->url(array(
					'controller' => 'edu_registration_evaluations', 'action' => 'get_evaluation_values')); ?>/' + selected_evaluation,
				success: function(response, opts) {
					var content = response.responseText;
					
					var element = document.createElement('div');
					element.id = "EvaluationValueHtmlCombo";
					document.body.appendChild(element);
					
					eval(content);
					
					RefreshParentRegistrationEvaluationData();
				},
				failure: function(response, opts) {
					Ext.Msg.alert('<?php __('Error'); ?>',
						'<?php __('Cannot get the Evaluation Value Combo.'); ?>: ' + response.status);
				}
			});
		}
	}

	var EvaluationSelectionForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		defaultType: 'textfield',

		items: [
                <?php
					$options = array('fieldLabel' => 'Section', 'anchor' => '50%');
					$options['items'] = $sections;
					$options['listeners'] = "{
						scope: this,
						'select': function(combo, record, index){
							var evaluation = Ext.getCmp('evaluation_id');
							evaluation.setValue('');
							evaluation.store.removeAll();
							evaluation.store.reload({
								params: {
									edu_section_id : combo.getValue()
								}
							});
							g.getStore().removeAll();
							selected_section = combo.getValue();
						}
					}";
					$this->ExtForm->input('edu_section_id', $options);
			?>, {
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
                anchor: '100%',
                blankText: 'Your input is invalid.',
                fieldLabel: '<span style="color:red;">*</span> Evaluation',
                id:'evaluation_id',
                store : store_evaluations,
                listeners:{
                    scope: this,
                    'select': function(combo, record, index) {
                        selected_evaluation = combo.getValue();
						RefreshEvaluationValueCombo();
                    }
                }
            }
        ]
    });
	
	var element = document.createElement('div');
	element.id = "EvaluationValueHtmlCombo";
	document.body.appendChild(element);
	
	var cont = '<select name="evaluation_values" id="evaluation_values" style="display:none;">';
	<?php foreach ($edu_evaluation_values as $gl) { ?>
		cont += '<option value="<?php echo $gl['EduEvaluationValue']['description']; ?>">'+
			'<?php echo $gl['EduEvaluationValue']['description']; ?></option>';
	<?php } ?>
	cont += '</select>';
	document.getElementById("EvaluationValueHtmlCombo").innerHTML=cont;
	
	var fm = Ext.form;
	var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true
        },
        columns: [
            {header: "<?php __('Student Name'); ?>", dataIndex: 'student_name', sortable: true},
            {header: "<?php __('Evaluation Value'); ?>", dataIndex: 'evaluation_value', sortable: true,
			editor: new fm.ComboBox({
                triggerAction: 'all',
				forceSelection: true,
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
        height: 350,
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
        listeners: {
            cellclick: function(grid, rowIndex, columnIndex, e){
				if(columnIndex == 1) {
					
				}
			},
			
		}
    });

    var ManageRegistrationEvaluationsWindow = new Ext.Window({
        title: 'Evaluation Management Form',
        width: 700,
        height: 480,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            EvaluationSelectionForm, g
        ],
        buttons: [{
			text: 'Save',
			handler: function(btn){
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
					},
					failure: function(){
						alert('Error Saving Changes, Please Try Again!');
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
