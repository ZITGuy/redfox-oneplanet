//<script>
    var selected_section='';
	var selected_evaluation='';

    var store_registration_evaluations = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'student_name', 'evaluation_value'
            ]
		}),
		proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registration_evaluations', 'action' => 'list_data_for_evaluation')); ?>'
		}),
		sortInfo:{field: 'student_name', direction: "ASC"}
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
	
	function EditRegistrationEvaluation(id) {
		Ext.Ajax.request({
			url: '<?php echo $this->Html->url(array(
                'controller' => 'edu_registration_evaluations', 'action' => 'edit')); ?>/'+id,
			success: function(response, opts) {
				var eduRegistrationEvaluation_data = response.responseText;
				
				eval(eduRegistrationEvaluation_data);
				
				EduRegistrationEvaluationEditWindow.show();
			},
			failure: function(response, opts) {
				Ext.Msg.alert('<?php __('Error'); ?>',
                    '<?php __('Cannot get the edit form. Error code'); ?>: ' + response.status);
			}
		});
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

	var EvaluationSelectionForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		defaultType: 'textfield',
        autoHeight: true,
        width: 500,
        resizable: false,
        plain:true,
        modal: true,
        y: 100,
        autoScroll: true,
        closeAction: 'hide',

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
                    'select': function (combo, record, index) {
                        selected_evaluation = combo.getValue();
			            RefreshParentRegistrationEvaluationData();
                    }
                }
            }
        ]
    });
	
	var fm = Ext.form;
	var cm = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true
        },
        columns: [
            {header: "<?php __('Student Name'); ?>", dataIndex: 'student_name', sortable: true},
            {header: "<?php __('Evaluation Value'); ?>", dataIndex: 'evaluation_value', sortable: true}
        ]
    });
	
		
    var g = new Ext.grid.GridPanel({
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
            singleSelect: true
        }),
        viewConfig: {
            forceFit: true
        },
        listeners: {
        	celldblclick: function(){
			EditRegistrationEvaluation(Ext.getCmp('evaluationGrid').getSelectionModel().getSelected().data.id);
		}
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
			text: 'Close',
			handler: function(btn){
				ManageRegistrationEvaluationsWindow.close();
			}
		}]
    });
	
    ManageRegistrationEvaluationsWindow.show();
