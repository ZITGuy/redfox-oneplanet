//<script> 
    var popUpWin_print_payment=0;

    function popUpPrintPaymentWindow(URLStr, left, top, width, height) {
        if(popUpWin_print_payment){
            if(!popUpWin_print_payment.closed) popUpWin_print_payment.close();
        }
        popUpWin_print_payment = open(URLStr, 'popUpPrintPaymentWindow',
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,'+
            'resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+
            ', top='+top+',screenX='+left+',screenY='+top+'');
    }

    function ShowPrintPayment(start_dt, end_dt, opt, ord) {
        url = "<?php echo $this->Html->url(array(
            'controller' => 'edu_payments', 'action' => 'generate_collections_report', 'plugin' => 'edu')); ?>/" +
                start_dt + "/" + end_dt + "/" + opt + "/" + ord;
        popUpPrintPaymentWindow(url, 200, 200, 700, 1000);
    }

    <?php
        $this->ExtForm->create('EduPayment');
        $this->ExtForm->defineFieldFunctions();
    ?>

    var PaymentCollectionsForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 100,
        labelAlign: 'right',
        defaultType: 'textfield',

        items: [
			<?php
				$options = array('xtype' => 'datefield', 'anchor' => '60%', 'format' => 'Y-m-d', 'id' => 'dt_start_date');
				$this->ExtForm->input('start_date', $options);
			?>,
			<?php
				$options = array('xtype' => 'datefield', 'anchor' => '60%', 'format' => 'Y-m-d', 'id' => 'dt_end_date');
				$this->ExtForm->input('end_date', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'combo',
					'anchor' => '80%',
					'id' => 'cbo_option',
					'items' => array(0 => 'Summary Only', 1 => 'Summary with Detail'),
					'fieldLabel' => 'Display Options');
				$this->ExtForm->input('display_option', $options);
			?>,
			<?php
				$options = array(
					'xtype' => 'combo',
					'anchor' => '80%',
					'id' => 'cbo_order_option',
					'items' => array(0 => 'Student Name', 1 => 'Student ID', 2 => 'Date Paid'),
					'fieldLabel' => 'Order By');
				$this->ExtForm->input('order_option', $options);
			?>
        ]
    });

    var PaymentCollectionsWindow = new Ext.Window({
        title: 'Payment Collections',
        width: 400,
        autoHeight: true,
        resizable: true,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [
            PaymentCollectionsForm
        ],
        buttons: [{
            text: 'Preview',
            id: 'btnPrint',
            handler : function(){
				var cbo_option = Ext.getCmp('cbo_option');
				var cbo_option_value = cbo_option.getValue();
				
				var cbo_order_option = Ext.getCmp('cbo_order_option');
				var cbo_order_option_value = cbo_order_option.getValue();
				
				var dt_start_date = Ext.getCmp('dt_start_date');
				var dt_start_date_value = dt_start_date.getValue();
				var dt = new Date(dt_start_date_value);
				var dtf = dt.format('Y-m-d');
		
				var dt_end_date = Ext.getCmp('dt_end_date');
				var dt_end_date_value = dt_end_date.getValue();
				var dt = new Date(dt_end_date_value);
				var dtt = dt.format('Y-m-d');
				
                ShowPrintPayment(dtf, dtt, cbo_option_value, cbo_order_option_value);
            }
        }, {
            text: 'Cancel',
            handler: function(btn){
                PaymentCollectionsWindow.close();
            }
        }]
    });
	
    PaymentCollectionsWindow.show();
