//<script>
    var tree = new Ext.tree.TreePanel({
        title: 'Other Settings',
        height: 170,
        width: '100%',
        useArrows: true,
        autoScroll:true,
        loadMask: true,
        animate: true,
        containerScroll: true,
        rootVisible: false,
        frame: true,
        root: {
            nodeType: 'async'
        },
        
        // auto create TreeLoader
        dataUrl: '<?php echo $this->Html->url(array('controller' => 'sms_preferences', 'action' => 'list_data_2')); ?>'
    });

    var email_tree = new Ext.tree.TreePanel({
        title: 'Other Settings',
        height: 170,
        width: '100%',
        useArrows: true,
        loadMask: true,
        autoScroll:true,
        animate: true,
        containerScroll: true,
        rootVisible: false,
        frame: true,
        root: {
            nodeType: 'async'
        },
        
        // auto create TreeLoader
        dataUrl: '<?php echo $this->Html->url(array('controller' => 'sms_preferences', 'action' => 'list_data_3')); ?>'
    });

    var store_message_templates = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id', 'name','body']
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'list_data')); ?>'
        }),
        sortInfo:{field: 'name', direction: "ASC"},
        groupField: 'body'

    });

    function EditMessageTemplate(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'message_templates', 'action' => 'edit')); ?>/'+id,
            success: function(response, opts) {
                var message_template_data = response.responseText;
                
                eval(message_template_data);
                
                MessageTemplateEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the Message Template edit form. Error code'); ?>: ' + response.status);
            }
        });
    }

    // create the Grid
    var message_templates_grid = new Ext.grid.GridPanel({
        store: store_message_templates,
        columns: [{
                id       :'name',
                header   : 'Title', 
                width    : 160, 
                sortable : true, 
                dataIndex: 'name'
            }, {
                header   : 'Body', 
                width    : 300, 
                sortable : true, 
                dataIndex: 'body'
            }
        ],
        viewConfig: {
            forceFit:true
        },
        listeners: {
            celldblclick: function(){
                EditMessageTemplate(message_templates_grid.getSelectionModel().getSelected().data.id);
            }
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
        }),
        stripeRows: true,
        autoExpandColumn: 'name',
        height: 350,
        width: 600,
        title: ' ',
        // config options for stateful behavior
        stateful: true,
        stateId: 'message_templates_grid'
    });

    store_message_templates.load({
        params: {
            start: 0,          
            limit: 1000
        }
    });

    //tree.getRootNode().expand(true);

    <?php
        $this->ExtForm->create('Setting');
        $this->ExtForm->defineFieldFunctions();
        
        $expense_accounts = array();
        foreach($ex_accounts as $account){
            $expense_accounts[$account['AcctAccount']['code']] = $account['AcctAccount']['name'];
        }
        $asset_accounts = array();
        foreach($as_accounts as $a_account){
            $asset_accounts[$a_account['AcctAccount']['code']] = $a_account['AcctAccount']['name'];
        }
        $income_accounts = array();
        foreach($re_accounts as $r_account){
            $income_accounts[$r_account['AcctAccount']['code']] = $r_account['AcctAccount']['name'];
        }
        
        
        // General->Company Variables
        $company_address = '';
        $company_url = '';
        $company_tin = '';

        // Education->General Variables
        $education_term = 'Quarter';
        $handle_summer_as_term = true;
        $fails_to_dismissal = 3;
        $allowed_absentee = 10;
        $number_of_periods_per_day = 8;
		$migration_after_enrollment = false;

        // Education->Payment Variables
        $payment_schedule_method = 'M';
        $tuition_gl_account = '';
        $cash_gl_account = '';
        $receivable_gl_account = '';
        $discount_gl_account = '';
        $receive_payment_by_cheque = true;

        // DB Backup and Restore Settings
        $db_backup_method = 'Automatic';
        $db_backup_type = 'Incremental';
        $db_backup_location = 'Remote Cloud';
        
        // Accounting
        $accounting_type = '';
        $accounting_fiscal_year = '';
        $allowed_arrears = 3;


        // SMS Settings
        // SMS->SMS Variables
        $sms_server_ip = '127.0.0.1';
        $sms_server_port = '1234';
        $sms_user_id = 'user';
        $sms_password = '';
        $sms_short_code = '';
        $sms_enabled = true;

        // Top Person Address
        $sms_top_person_phone = '';
        $sms_top_person_email = '';


        // EMAIL Settings
        // EMAIL->Email Settings
        $email_server_ip = '127.0.0.1';
        $email_server_port = '1234';
        $email_user_id = 'ophthysoft';
        $email_password = '';
        $email_from_name = 'CHA'; 
        $email_enabled = true;

        
        // set actual variable values
        foreach($settings as $setting) {
            switch ($setting['Setting']['setting_key']) {
                

                case 'COMPANY_ADDRESS':
                    $company_address = $setting['Setting']['setting_value'];
                    break;
                case 'COMPANY_URL':
                    $company_url = $setting['Setting']['setting_value'];
                    break;
                case 'COMPANY_TIN':
                    $company_tin = $setting['Setting']['setting_value'];
                    break;
                case 'EDUCATION_TERM':
                    $education_term = $setting['Setting']['setting_value'];
                    break;
                case 'HANDLE_SUMMER_AS_TERM':
                    $handle_summer_as_term = $setting['Setting']['setting_value'] == 'True'? true: false;
                    break;
				case 'MIGRATION_AFTER_ENROLLMENT':
                    $migration_after_enrollment = $setting['Setting']['setting_value'] == 'True'? true: false;
                    break;
                case 'NUMBER_OF_PERIODS_PER_DAY':
                    $number_of_periods_per_day = $setting['Setting']['setting_value'];
                    break;
                case 'FAILS_TO_DISMISSAL':
                    $fails_to_dismissal = intval($setting['Setting']['setting_value']);
                    break;
                case 'ALLOWED_ABSENTEE':
                    $allowed_absentee = intval($setting['Setting']['setting_value']);
                    break;
                case 'PAYMENT_SCHEDULE_METHOD':
                    $payment_schedule_method = $setting['Setting']['setting_value'];
                    break;
                case 'TUITION_GL_ACCOUNT':
                    $tuition_gl_account = $setting['Setting']['setting_value'];
                    break;
                case 'CASH_GL_ACCOUNT':
                    $cash_gl_account = $setting['Setting']['setting_value'];
                    break;
                case 'RECEIVABLE_GL_ACCOUNT':
                    $receivable_gl_account = $setting['Setting']['setting_value'];
                    break;
                case 'DISCOUNT_GL_ACCOUNT':
                    $discount_gl_account = $setting['Setting']['setting_value'];
                    break;
                case 'RECEIVE_PAYMENT_BY_CHEQUE':
                    $receive_payment_by_cheque = $setting['Setting']['setting_value'] == 'True'? true: false;
                    break;
                case 'DB_BACKUP_METHOD':
                    $db_backup_method = $setting['Setting']['setting_value'];
                    break;
                case 'DB_BACKUP_TYPE':
                    $db_backup_type = $setting['Setting']['setting_value'];
                    break;
                case 'DB_BACKUP_LOCATION':
                    $db_backup_location = $setting['Setting']['setting_value'];
                    break;
                case 'ACCOUNTING_TYPE':
                    $accounting_type = $setting['Setting']['setting_value'];
                    break;
                case 'ACCOUNTING_FISCAL_YEAR':
                    $accounting_fiscal_year = $setting['Setting']['setting_value'];
                    break;
                case 'ALLOWED_ARREARS':
                    $allowed_arrears = $setting['Setting']['setting_value'];
                    break;

                case 'SMS_SERVER_IP':
                    $sms_server_ip = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_SERVER_PORT':
                    $sms_server_port = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_USER_ID':
                    $sms_user_id = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_PASSWORD':
                    $sms_password = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_SHORT_CODE':
                    $sms_short_code = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_ENABLED':
                    $sms_enabled = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_TOP_PERSON_PHONE':
                    $sms_top_person_phone = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_TOP_PERSON_EMAIL':
                    $sms_top_person_email = $setting['Setting']['setting_value'];
                    break;

                case 'EMAIL_SERVER_IP':
                    $email_server_ip = $setting['Setting']['setting_value'];
                    break;
                case 'EMAIL_SERVER_PORT':
                    $email_server_port = $setting['Setting']['setting_value'];
                    break;
                case 'EMAIL_USER_ID':
                    $email_user_id = $setting['Setting']['setting_value'];
                    break;
                case 'EMAIL_PASSWORD':
                    $email_password = $setting['Setting']['setting_value'];
                    break;
                case 'SMS_FROM_NAME':
                    $email_from_name = $setting['Setting']['setting_value'];
                    break;
                case 'EMAIL_ENABLED':
                    $email_enabled = $setting['Setting']['setting_value'];
                    break;

                default:
                    break;
            }

        }
    ?>
    var SystemSettingsForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 130,
        labelAlign: 'right',
        url:'<?php echo $this->Html->url(array('controller' => 'settings', 'action' => 'system_settings')); ?>',
        defaultType: 'textfield',

        items: {
            xtype: 'tabpanel',
            activeTab: 0,
            height: 500,
            id: 'enrollment_tabs',
            tabWidth: 225,
            defaults: {bodyStyle: 'padding:10px'},
            items: [{
                title: 'General',
                layout: 'form',
                defaultType: 'textfield',
                id: 'general_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'School Information',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: 1,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options31 = array('anchor'=>'95%', 'value' => $company_address, 'fieldLabel' => 'Address');
                                            $this->ExtForm->input('company_address', $options31);
                                            echo ",\n";
                                            $options32 = array('anchor'=>'95%', 'value' => $company_url, 'fieldLabel' => 'School URL');
                                            $this->ExtForm->input('company_url', $options32);
                                            echo ",\n";
                                            $options33 = array('anchor'=>'95%', 'value' => $company_tin, 'fieldLabel' => 'TIN Number');
                                            $this->ExtForm->input('company_tin', $options33);
                                        ?>
                                    ]
                                }]
                        }]
                    }
                ]
            }, {
                title: 'Education',
                layout: 'form',
                defaultType: 'textfield',
                id: 'education_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'General Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options41 = array(
                                                'anchor'=>'95%', 
                                                'value' => $education_term,
                                                'xtype' => 'combo',
                                                'fieldLabel' => 'Educ. Term',
                                                'allowBlank' => 'true',
                                                'items' => array('Q'=> 'Quarter', 'T'=>'Term', 'S'=>'Semester')
                                            );
                                            $this->ExtForm->input('education_term', $options41);
                                            echo ",\n";
                                            $options_periods = array(
                                                'anchor'=>'60%', 
                                                'value' => $number_of_periods_per_day,
                                                'xtype' => 'spinnerfield',
                                                'minValue' => 1,
                                                'maxValue' => 12
                                            );
                                            $this->ExtForm->input('number_of_periods_per_day', $options_periods);
                                            echo ",\n";
                                            $options42 = array(
                                                'anchor'=>'95%', 
                                                'value' => $handle_summer_as_term,
                                                'fieldLabel' => 'Include Summer',
                                                'xtype' => 'checkbox'
                                            );
                                            $this->ExtForm->input('handle_summer_as_term', $options42);
                                            
                                            
                                        ?>
                                    ]
                                }, {
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options51 = array(
                                                'anchor'=>'70%', 
                                                'value' => $fails_to_dismissal,
                                                'xtype' => 'spinnerfield',
                                                'minValue' => 1,
                                                'maxValue' => 5
                                            );
                                            $this->ExtForm->input('fails_to_dismissal', $options51);
                                            echo ",\n";
                                            $options52 = array(
                                                'anchor'=>'70%', 
                                                'value' => $allowed_absentee,
                                                'xtype' => 'spinnerfield',
                                                'minValue' => 1,
                                                'maxValue' => 30,
                                                'fieldLabel' => 'Allowed_Absentee'
                                            );
                                            $this->ExtForm->input('allowed_absentee', $options52);
											echo ",\n";
                                            $options53 = array(
                                                'anchor'=>'70%', 
                                                'value' => $migration_after_enrollment,
                                                'fieldLabel' => 'Consider Migration after Enrollment',
                                                'xtype' => 'checkbox'
                                            );
                                            $this->ExtForm->input('migration_after_enrollment', $options53);
                                        ?>
                                    ]
                                }]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Payment Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options61 = array(
                                                'anchor'=>'95%', 
                                                'value' => $payment_schedule_method,
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'fieldLabel' => 'Schedule Method',
                                                'items' => array('T'=>'Term', 'M'=>'Monthly')
                                            );
                                            $this->ExtForm->input('payment_schedule_method', $options61);
                                            echo ",\n";
                                            $options62 = array(
                                                'anchor'=>'95%', 
                                                'value' => $tuition_gl_account,
                                                'fieldLabel' => 'Tuition A/C',
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => $income_accounts
                                            );
                                            $this->ExtForm->input('tuition_gl_account', $options62);
                                            echo ",\n";
                                            $options63 = array(
                                                'anchor'=>'95%', 
                                                'value' => $cash_gl_account,
                                                'fieldLabel' => 'Cash A/C',
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => $asset_accounts
                                            );
                                            $this->ExtForm->input('cash_gl_account', $options63);
                                        ?>
                                    ]
                                }, {
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options71 = array(
                                                'anchor'=>'95%', 
                                                'value' => $receivable_gl_account,
                                                'fieldLabel' => 'Receivable A/C',
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => $asset_accounts
                                            );
                                            $this->ExtForm->input('receivable_gl_account', $options71);
                                            echo ",\n";
                                            $options711 = array(
                                                'anchor'=>'95%', 
                                                'value' => $discount_gl_account,
                                                'xtype' => 'combo',
                                                'fieldLabel' => 'Discount GL A/C',
                                                'allowBlank' => 'true',
                                                'items' => $expense_accounts
                                            );
                                            $this->ExtForm->input('discount_gl_account', $options711);
                                            echo ",\n";
                                            $options72 = array(
                                                'anchor'=>'95%', 
                                                'value' => $receive_payment_by_cheque,
                                                'xtype' => 'checkbox',
                                                'fieldLabel' => 'Cheque Allowed'
                                            );
                                            $this->ExtForm->input('receive_payment_by_cheque', $options72);
                                        ?>
                                    ]
                                }]
                        }]
                    }
                ]
            }, {
                title: 'Accounting',
                layout: 'form',
                defaultType: 'textfield',
                id: 'account_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'General Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: 1,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options91 = array(
                                                'anchor'=>'95%', 
                                                'value' => $accounting_type, 
                                                'fieldLabel' => 'Accounting Type', 
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => array('Accrual'=>'Accrual', 'Deferral'=>'Deferral')
                                            );
                                            $this->ExtForm->input('accounting_type', $options91);
                                            echo ",\n";
                                            $options92 = array(
                                                'anchor'=>'95%', 
                                                'value' => $accounting_fiscal_year, 
                                                'fieldLabel' => 'Fiscal Year', 
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => array('Manually'=>'Open and Close Manually', 'Automatically'=>'Open and Close Automatically')
                                            );
                                            $this->ExtForm->input('accounting_fiscal_year', $options92);
                                        ?>
                                    ]
                                }]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Other Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: 1,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options93 = array(
                                                'anchor'=>'40%', 
                                                'value' => $allowed_arrears,
                                                'xtype' => 'spinnerfield',
                                                'minValue' => 1,
                                                'maxValue' => 30,
                                                'fieldLabel' => 'Allowed Arrears'
                                            );
                                            $this->ExtForm->input('allowed_arrears', $options93);
                                            
                                        ?>
                                    ]
                                }   
                            ]
                        }]
                    }
                ]
            }, {
                title: 'SMS',
                layout: 'form',
                defaultType: 'textfield',
                id: 'sms_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'Server Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options11p = array('id' => 'data[Setting][SmsPreferences]', 'hidden' => true);
                                            $this->ExtForm->input('sms_selected_preferences', $options11p);
                                            echo ",\n";
                                            $options11 = array('anchor'=>'95%', 'value' => $sms_server_ip, 'fieldLabel' => 'Server IP');
                                            $this->ExtForm->input('sms_server_ip', $options11);
                                            echo ",\n";
                                            $options12 = array('anchor'=>'95%', 'value' => $sms_server_port, 'fieldLabel' => 'Server Port');
                                            $this->ExtForm->input('sms_server_port', $options12);
                                            echo ",\n";
                                            $options13 = array('anchor'=>'95%', 'value' => $sms_user_id, 'fieldLabel' => 'User ID');
                                            $this->ExtForm->input('sms_user', $options13);
                                        ?>
                                    ]
                                }, {
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options21 = array('anchor'=>'95%', 'value' => $sms_password, 'fieldLabel' => 'Password');
                                            $this->ExtForm->input('sms_password', $options21);
                                            echo ",\n";
                                            $options22 = array('anchor'=>'95%', 'value' => $sms_short_code, 'fieldLabel' => 'Short Code');
                                            $this->ExtForm->input('sms_short_code', $options22);
                                            echo ",\n";
                                            $options23 = array(
                                                'anchor'=>'95%', 
                                                'value' => $sms_enabled, 
                                                'fieldLabel' => 'Enable SMS',
                                                'xtype' => 'checkbox'
                                            );
                                            $this->ExtForm->input('sms_enabled', $options23);
                                        ?>
                                    ]
                                }]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Top Person Address',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options11 = array('anchor'=>'95%', 'value' => $sms_top_person_phone, 'fieldLabel' => 'Mobile Number');
                                            $this->ExtForm->input('sms_top_person_phone', $options11);
                                        ?>
                                    ]
                                }, {
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options11 = array('anchor'=>'95%', 'value' => $sms_top_person_email, 'fieldLabel' => 'Email');
                                            $this->ExtForm->input('sms_top_person_email', $options11);
                                        ?>
                                    ]
                                }]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Other Settings',
                        collapsible: false,
                        items: [
                            tree
                        ]
                    }]
            }, {
                title: 'Email',
                layout: 'form',
                defaultType: 'textfield',
                id: 'email_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'Email Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options11 = array('anchor'=>'95%', 'value' => $email_server_ip, 'fieldLabel' => 'Server IP');
                                            $this->ExtForm->input('email_server_ip', $options11);
                                            echo ",\n";
                                            $options12 = array('anchor'=>'95%', 'value' => $email_server_port, 'fieldLabel' => 'Server Port');
                                            $this->ExtForm->input('email_server_port', $options12);
                                            echo ",\n";
                                            $options13 = array('anchor'=>'95%', 'value' => $email_user_id, 'fieldLabel' => 'User ID');
                                            $this->ExtForm->input('email_user', $options13);
                                        ?>
                                    ]
                                }, {
                                    columnWidth: .5,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options21 = array('anchor'=>'95%', 'value' => $email_password, 'fieldLabel' => 'Password');
                                            $this->ExtForm->input('email_password', $options21);
                                            echo ",\n";
                                            $options22 = array('anchor'=>'95%', 'value' => $email_from_name, 'fieldLabel' => 'From Name');
                                            $this->ExtForm->input('email_from_name', $options22);
                                            echo ",\n";
                                            $options23 = array(
                                                'anchor'=>'95%', 
                                                'value' => $email_enabled, 
                                                'fieldLabel' => 'Enable E-mail',
                                                'xtype' => 'checkbox'
                                            );
                                            $this->ExtForm->input('email_enabled', $options23);
                                        ?>
                                    ]
                                }]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Other Settings',
                        collapsible: false,
                        items: [
                            email_tree
                        ]
                    }]
            }, {
                title: 'Messages',
                layout: 'form',
                defaultType: 'textfield',
                id: 'messages_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'Messages Templates',
                        collapsible: false,
                        items: [
                            message_templates_grid
                        ]
                    }]
            }, {
                title: 'DB Backup/Restore',
                layout: 'form',
                defaultType: 'textfield',
                id: 'database_tab',
                items: [{
                        xtype: 'fieldset',
                        title: 'Backup Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: [{
                                    columnWidth: 1,
                                    layout: 'form',
                                    items: [
                                        <?php
                                            $options81 = array(
                                                'anchor'=>'95%', 
                                                'value' => $db_backup_method, 
                                                'fieldLabel' => 'Method', 
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => array('Automatic'=>'Automatic', 'Manual'=>'Manual')
                                            );
                                            $this->ExtForm->input('db_backup_method', $options81);
                                            echo ",\n";
                                            $options82 = array(
                                                'anchor'=>'95%', 
                                                'value' => $db_backup_type, 
                                                'fieldLabel' => 'Type', 
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => array('Incremental'=>'Incremental daily and Full Weekly', 'Full'=>'Full Backup')
                                            );
                                            $this->ExtForm->input('db_backup_type', $options82);
                                            echo ",\n";
                                            $options83 = array(
                                                'anchor'=>'95%', 
                                                'value' => $db_backup_location, 
                                                'fieldLabel' => 'Location', 
                                                'xtype' => 'combo',
                                                'allowBlank' => 'true',
                                                'items' => array('Remote'=>'Remote Cloud', 'Local'=>'Local FTP', 'Both' => 'Use Both Locations')
                                            );
                                            $this->ExtForm->input('db_backup_location', $options83);
                                        ?>
                                    ]
                                }]
                        }]
                    }, {
                        xtype: 'fieldset',
                        title: 'Restore Settings',
                        collapsible: false,
                        items: [{
                            layout: 'column',
                            items: []
                        }]
                    }
                ]
            }]
        }
    });

    var SystemSettingsWindow = new Ext.Window({
        title: '<?php __('Edit Setting'); ?>',
        width: 750,
        height: 542,
        layout: 'fit',
        modal: true,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'right',
        items: SystemSettingsForm,

        buttons: [ {
            text: '<?php __('Apply'); ?>',
            handler: function(btn){
                var checked_ids = "";
                var checked_nodes = tree.getChecked(); 
                var checked_nodes2 = email_tree.getChecked(); 
                for(var i = 0; i < checked_nodes.length; i++){
                    checked_ids += Ext.encode(checked_nodes[i].id) + ",";
                }
                for(var i = 0; i < checked_nodes2.length; i++){
                    checked_ids += Ext.encode(checked_nodes2[i].id) + ",";
                }
                Ext.getCmp('data[Setting][SmsPreferences]').setValue(checked_ids);

                SystemSettingsForm.getForm().submit({
                    waitMsg: '<?php __('Submitting your data...'); ?>',
                    waitTitle: '<?php __('Wait Please...'); ?>',
                    success: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Success'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.msg,
                            icon: Ext.MessageBox.INFO
                        });
                        SystemSettingsWindow.close();
                    },
                    failure: function(f,a){
                        Ext.Msg.show({
                            title: '<?php __('Warning'); ?>',
                            buttons: Ext.MessageBox.OK,
                            msg: a.result.errormsg,
                            icon: Ext.MessageBox.ERROR
                        });
                    }
                });
            }
        },{
            text: '<?php __('Cancel'); ?>',
            handler: function(btn){
                SystemSettingsWindow.close();
            }
        }]
    });
    
    SystemSettingsWindow.show();