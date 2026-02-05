//<script> 
    var popUpWin_print_uncollected_payment=0;

    function popUpPrintUncollectedPaymentWindow(URLStr, left, top, width, height) {
        if(popUpWin_print_uncollected_payment){
            if(!popUpWin_print_uncollected_payment.closed) popUpWin_print_uncollected_payment.close();
        }
        popUpWin_print_uncollected_payment = open(URLStr, 'popUpPrintUncollectedPaymentWindow',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,'+
            'resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+
            ', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function ShowPrintUncollectedPayment(class_id, opt, ord) {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_payments', 'action' => 'generate_uncollected_payments_report',
            'plugin' => 'edu')); ?>/" + class_id + "/" + opt + "/" + ord;
        popUpPrintUncollectedPaymentWindow(url, 200, 200, 700, 1000);
    }

    <?php
        $this->ExtForm->create('EduPayment');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var UncollectedPaymentsForm = new Ext.form.FormPanel({
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
				$options = array(
					'xtype' => 'combo',
					'anchor' => '80%',
					'id' => 'cbo_class_id',
					'items' => $edu_classes,
					'fieldLabel' => 'Class');
				$this->ExtForm->input('edu_class_id', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'combo',
					'anchor' => '80%',
					'id' => 'cbo_option',
					'value' => '1',
					'items' => array(0 => 'Summary Only', 1 => 'Summary with Detail'),
					'fieldLabel' => 'Display Options');
				$this->ExtForm->input('display_option', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'combo',
					'anchor' => '80%',
					'id' => 'cbo_order_option',
					'value' => '0',
					'items' => array(0 => 'Student Name', 1 => 'Student ID'),
					'fieldLabel' => 'Order By');
				$this->ExtForm->input('order_option', $options);
			?>
        ]
    });

    var UncollectedPaymentsWindow = new Ext.Window({
        title: 'Uncollected Payments',
        width: 400,
        autoHeight: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            UncollectedPaymentsForm
        ],
        buttons: [{
            text: 'Preview',
            id: 'btnPrint',
            handler : function(){
				var cbo_class_id = Ext.getCmp('cbo_class_id');
				var cbo_class_id_value = cbo_class_id.getValue();
				
				var cbo_option = Ext.getCmp('cbo_option');
				var cbo_option_value = cbo_option.getValue();
				
				var cbo_order_option = Ext.getCmp('cbo_order_option');
				var cbo_order_option_value = cbo_order_option.getValue();
				
                ShowPrintUncollectedPayment(cbo_class_id_value, cbo_option_value, cbo_order_option_value);
            }
        }, {
            text: 'Cancel',
            handler: function(btn){
                UncollectedPaymentsWindow.close();
            }
        }]
    });
	
	
    UncollectedPaymentsWindow.show();