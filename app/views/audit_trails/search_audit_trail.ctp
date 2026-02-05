//<script>
	<?php
            $this->ExtForm->create('AuditTrail');
            $this->ExtForm->defineFieldFunctions();
	?>
	
	var popUpWin_1=0;
	
	function popUpWindow(URLStr, left, top, width, height) {
		if(popUpWin_1){
			if(!popUpWin_1.closed) popUpWin_1.close();
		}
		popUpWin_1 = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
	}

	function viewAuditTrails() {
		var user_id = Ext.getCmp('data[AuditTrail][user_id]');
                user_id = user_id.getValue();
                
                var from_date = Ext.getCmp('data[AuditTrail][from_date]');
		from_date = from_date.getValue();
		var dt = new Date(from_date);
		from_date = dt.format('Y-m-d');
                
                var to_date = Ext.getCmp('data[AuditTrail][to_date]');
		to_date = to_date.getValue();
		var dt2 = new Date(to_date);
		to_date = dt2.format('Y-m-d');
                
		url = "<?php echo $this->Html->url(array('controller' => 'audit_trails', 'action' => 'view_audit_trail')); ?>/" + user_id + '/' + from_date + '/' + to_date;
		popUpWindow(url, 0, 0, 1200, 1200);
	}
	
	var SearchAuditTrailForm = new Ext.form.FormPanel({
		baseCls: 'x-plain',
		labelWidth: 100,
		labelAlign: 'right',
		url:'',
		defaultType: 'textfield',
		
		items: [
			<?php 
                            $options = array('value' => 1, 'id' => 'data[AuditTrail][user_id]');
                            $options['items'] = array(0 => 'All', 1 => 'Admin');
                            $this->ExtForm->input('user_id', $options);
			?>,
			<?php 
                            $options = array('id' => 'data[AuditTrail][from_date]');
                            $options['value'] = date('Y-m-d');
                            $options['xtype'] = 'datefield';
                            $options['format'] = 'Y-m-d';
                            $options['fieldLabel'] = 'From Date';
                            $this->ExtForm->input('from_date', $options);
			?>,
			<?php 
                            $options = array('id' => 'data[AuditTrail][to_date]');
                            $options['value'] = date('Y-m-d');
                            $options['xtype'] = 'datefield';
                            $options['format'] = 'Y-m-d';
                            $options['fieldLabel'] = 'To Date';
                            $this->ExtForm->input('to_date', $options);
			?>
		]
	});
	
	
	var SearchAuditTrailWindow = new Ext.Window({
		title: '<?php __('Audit Trail Report'); ?>',
		width: 400,
		minWidth: 400,
		autoHeight: true,
		layout: 'fit',
		modal: true,
		resizable: true,
		plain:true,
		bodyStyle:'padding:5px;',
		buttonAlign:'right',
		items: SearchAuditTrailForm,
		tools: [{
			id: 'refresh',
			qtip: 'Reset',
			handler: function () {
				SearchAuditTrailForm.getForm().reset();
			},
			scope: this
		}, {
			id: 'help',
			qtip: 'Help',
			handler: function () {
                            Ext.Msg.show({
                                title: 'Help',
                                buttons: Ext.MessageBox.OK,
                                msg: 'This form is used to set parameters to the Audit Trail report.',
                                icon: Ext.MessageBox.INFO
                            });
			}
		}, {
			id: 'toggle',
			qtip: 'Collapse / Expand',
			handler: function () {
				if(SearchAuditTrailWindow.collapsed)
					SearchAuditTrailWindow.expand(true);
				else
					SearchAuditTrailWindow.collapse(true);
			}
		}],
		buttons: [{
			text: '<?php __('Show report'); ?>',
			handler: function(btn){
				viewAuditTrails();
			}
		}, {
			text: '<?php __('Cancel'); ?>',
			handler: function(btn){
				SearchAuditTrailWindow.close();
			}
		}]
	});
	
	SearchAuditTrailWindow.show();
		
