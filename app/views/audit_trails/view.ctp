//<script>

    <?php
    $actions_made = array('C' => 'RECORD CREATED', 'U' => 'RECORD UPDATED', 'D' => 'RECORD DELETED');
    $audit_trail_html = "<table cellspacing=3>" . 
            "<tr><th align=right>" . __('Made By', true) . ":</th><td><b>" . strtoupper($audit_trail['User']['username']) . "</b></td></tr>" .
            "<tr><th align=right>" . __('Action Made', true) . ":</th><td><b>" . $actions_made[$audit_trail['AuditTrail']['action_made']] . "</b></td></tr>" .
            "<tr><th align=right>" . __('Date Time', true) . ":</th><td><b>" . $audit_trail['AuditTrail']['created'] . "</b></td></tr>" .
            "</table>";
    
    $audit_data = array();
    if($audit_trail['AuditTrail']['old_value'] == ''){
        // for add audit record
        $new_vals = explode(';', $audit_trail['AuditTrail']['new_value']);
        $audit_data[0] = array();
        $audit_data[1] = array();
        
        foreach($new_vals as $new_val){
            if($new_val == '' || strpos($new_val, "_id|")) continue;
            $fld = explode('|', $new_val);
            $audit_data[0][$fld[0]] = '<font color=gray>(<i>NULL</i>)</font>';
            $audit_data[1][$fld[0]] = $fld[1];
        }
    } elseif($audit_trail['AuditTrail']['old_value'] != '' && $audit_trail['AuditTrail']['new_value'] != '') {
        // for update audit record
        $new_vals = explode(';', $audit_trail['AuditTrail']['new_value']);
        $old_vals = explode(';', $audit_trail['AuditTrail']['old_value']);
        $audit_data[0] = array();
        $audit_data[1] = array();
        for($i = 0; $i < count($new_vals); $i++){
            if($new_vals[$i] == '' || strpos($new_vals[$i], "_id|")) continue;
            $nfld = explode('|', $new_vals[$i]);
            $ofld = explode('|', $old_vals[$i]);
            
            $audit_data[0][$ofld[0]] = $nfld[1] == $ofld[1]? $ofld[1]: '<font color=red>' . $ofld[1] . '</font>';
            $audit_data[1][$nfld[0]] = $nfld[1] == $ofld[1]? $ofld[1]: '<font color=green>' . $nfld[1] . '</font>';
            
        }
    } else { 
        // for detele audit record
        $old_vals = explode(';', $audit_trail['AuditTrail']['old_value']);
        $audit_data[0] = array();
        $audit_data[1] = array();
        foreach($old_vals as $old_val){
            if($old_val == '' || strpos($old_val, "_id|")) continue;
            $fld = explode('|', $old_val);
            $audit_data[0][$fld[0]] = $fld[1];
            $audit_data[1][$fld[0]] = '-';
        }
    }
        
    ?>
    var auditData = [
        [<?php foreach($audit_data[0] as $k => $v) { echo "'$v', "; } ?>'<b>OLD</b>'],
        [<?php foreach($audit_data[1] as $k => $v) { echo "'$v', "; } ?>'<b>NEW</b>']
    ];
    
    // create the data store
    var audit_store = new Ext.data.ArrayStore({
        fields: [
           <?php foreach ($audit_data[0] as $k => $v){ ?>
           {name: '<?php echo $k; ?>'},
           <?php } ?>
           {name: 'remark'}
        ]
    });
    
    audit_store.loadData(auditData);
    
    
    var auditTrail_view_panel_1 = {
        html: '<?php echo $audit_trail_html; ?>',
        frame: true,
        height: 90
    }
    <?php
    $nm = $audit_trail['AuditTrail']['table_name'];
    $ret = strpos($nm, 'Edu');
    if(strpos($nm, 'Edu') !== FALSE && strpos($nm, 'Edu') === 0) {
        $nm = substr($nm, 3) . ' in Euducation Module';
    }
    if(strpos($nm, 'Acct') !== FALSE && strpos($nm, 'Acct') === 0) {
        $nm = substr($nm, 4) . ' in Accounting Module';
    }
    
    ?>
    var auditTrail_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height: 180,
        plain: true,
        defaults: {autoScroll: true},
        items: [{
                xtype: 'grid',
                store: audit_store,
                title: '<?php echo $nm; ?>',
                height: 200,
                width: 962,
                enableColumnMove: false,
                columns: [
                    <?php foreach ($audit_data[0] as $k => $v){ ?>
                    {header: "<?php echo $k; ?>", dataIndex: '<?php echo $k; ?>', sortable: false},
                    <?php } ?>
                    {header: "Remark", dataIndex: 'remark'}
                ],
                viewConfig: {
                    forceFit: true
                }
            }
        ]
    });

    var AuditTrailViewWindow = new Ext.Window({
        title: '<?php __('View AuditTrail'); ?>: <?php echo $audit_trail['AuditTrail']['id']; ?>',
                width: 1000,
                height: 345,
                minWidth: 1000,
                minHeight: 345,
                resizable: false,
                plain: true,
                bodyStyle: 'padding:5px;',
                buttonAlign: 'center',
                modal: true,
                items: [
                    auditTrail_view_panel_1,
                    auditTrail_view_panel_2
                ],
                buttons: [{
                        text: '<?php __('Close'); ?>',
                        handler: function(btn) {
                            AuditTrailViewWindow.close();
                        }
                    }]
            });
