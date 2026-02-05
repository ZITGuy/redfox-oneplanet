//<script>

<?php 
    $primary_image = '';
    $other_image1 = '';
    $other_name1 = '';
    $other_image2 = '';
    $other_name2 = '';
    $parent_name = '';
	$primary = 'm';
    $pps = array('M' => 'Mother', 'F' => 'Father', 'G' => 'Guardian');
    $statuses = array('1' => 'Active', '2'  => 'Inactive', '3' => 'Dismissed', '4' => 'Withdrawn', '5' => 'Transferred', '6' => 'Incomplete', '7' => 'Enrolled but not registered', '8' => 'Other', 'A' => 'Active', 'I' => 'Inactive', 'P' => 'Promoted', 'N' => 'Not Promoted', 'S' => 'Single', 'M' => 'Married', 'D' => 'Divorced');
    $primary = strtolower($edu_parent['EduParent']['primary_parent']);
	$non_primary = ($primary == 'm')? 'f': 'm';
    foreach($edu_parent['EduParentDetail'] as $pd){
        //echo $pd['family_type'] . ' === ' . $pps[$edu_parent['EduParent']['primary_parent']];
		
        if($pd['family_type'] == $edu_parent['EduParent']['primary_parent']){
            $primary_image = $pd['photo_file'];
            $parent_name = $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
        } else {
            if($other_image1 == ''){
                $other_image1 = $pd['photo_file'];
                $other_name1 = $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
            } else {
                $other_image2 = $pd['photo_file'];
                $other_name2 = $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
            }
        }
    }
    
    $eduParent_html = "<table cellspacing=3 width=100%>" . 		
        "<tr>" . 
            "<td align=left valign=top width=15%>" . 
                $this->Html->image((($primary_image == 'No file')? 'default-'.$primary.'.jpg': 'parents/' . $primary_image), array('width'=>'80px', 'title' => 'Primary Parent: ' . $parent_name)) . "</td>" . 
            "<td><center><font color=darkblue size=3em><b>Primary: " . $parent_name . "</b></font> " . 
                "<br/><font color=darkblue size=3em><b>Marital Status: " . $statuses[$edu_parent['EduParent']['marital_status']] . "</b></font>" . 
                "<br/><font color=darkblue size=3em><b>Authorized Person: " . $edu_parent['EduParent']['authorized_person'] . "</b></font></center></td>" . 
            "<td align=right valign=top width=15%>" . 
                ($other_image1 == ''? $this->Html->image('default-'.$non_primary.'.jpg', array('width'=>'80px', 'title' => 'Parent: ' . $other_name1)): $this->Html->image('parents/' . $other_image1, array('width'=>'80px', 'title' => 'Parent: ' . $other_name1))) . 
                ($other_image2 == ''? $this->Html->image('default-'.$non_primary.'.jpg', array('width'=>'80px', 'title' => 'Parent: ' . $other_name2)): $this->Html->image('parents/' . $other_image2, array('width'=>'80px', 'title' => 'Parent: ' . $other_name2))) . 
            "</td>" . 
        "</tr>" . 
    "</table>"; 
    
    $personal_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.4em; font-color: darkblue;">';
    
    $personal_info .= '<tr bgcolor=#ccccdd>';
        $personal_info .= '<td>&nbsp;</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pps[$pd['family_type']] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ddddee>';
        $personal_info .= '<td>Full Name:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ccccdd>';
        $personal_info .= '<td>Residence Address:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['residence_address'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ddddee>';
        $personal_info .= '<td>Nationality:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['nationality'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ccccdd>';
        $personal_info .= '<td>Occupation:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['occupation'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ddddee>';
        $personal_info .= '<td>Academic Qualification:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['academic_qualification'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ccccdd>';
        $personal_info .= '<td>Employment Status:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['employment_status'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ddddee>';
        $personal_info .= '<td>Employer:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['employer'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ccccdd>';
        $personal_info .= '<td>Work Address:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['work_address'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ddddee>';
        $personal_info .= '<td>Work Telephone:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['work_telephone'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ccccdd>';
        $personal_info .= '<td>Mobile:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['mobile'] . '</td>';
    }
    $personal_info .= '</tr>';
    
    $personal_info .= '<tr bgcolor=#ddddee>';
        $personal_info .= '<td>E-mail:</td>';
    foreach($edu_parent['EduParentDetail'] as $pd){
        $personal_info .= '<td>' . $pd['email'] . '</td>';
    }
    $personal_info .= '</tr>';
    $personal_info .= '</table>';
    
    $yes_image = $this->Html->image('checkmark.gif');
    $no_image = $this->Html->image('b_drop.png');
    
    $subscription_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.4em; font-color: darkblue;">';
    $subscription_info .= '<tr bgcolor=#ddddee>';
    $subscription_info .= '<td><b>Subscription</b></td>';
    $subscription_info .= '<td><b>By SMS</b></td>';
    $subscription_info .= '<td><b>By E-Mail</b></td>';
    $subscription_info .= '<td><b>By Portal</b></td>';
    $subscription_info .= '</tr>';
    
    foreach($edu_parent['EduSubscription'] as $scr){
        $subscription_info .= '<tr bgcolor=#ddddee>';
        $subscription_info .= '<td>' . $scr['name'] . '</td>';
        $subscription_info .= '<td>' . ($scr['sms']? $yes_image: $no_image) . '</td>';
        $subscription_info .= '<td>' . ($scr['email']? $yes_image: $no_image) . '</td>';
        $subscription_info .= '<td>' . ($scr['portal']? $yes_image: $no_image) . '</td>';
        $subscription_info .= '</tr>';
    }
    $subscription_info .= '</table>';
    
    $family_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.4em; font-color: darkblue;">';
    $family_info .= '<tr bgcolor=#ddddee>';
    $family_info .= '<td><b>#</b></td>';
    $family_info .= '<td><b>Name</b></td>';
    $family_info .= '<td><b>ID Number</b></td>';
    $family_info .= '</tr>';
    
    foreach($edu_parent['EduStudent'] as $student){
        $family_info .= '<tr bgcolor=#ddddee>';
        $family_info .= '<td><center>' . $this->Html->image('students/' . $student['photo_file_name'], array('width' => '50px')) . '</center></td>';
        $family_info .= '<td>' . $student['name'] . '</td>';
        $family_info .= '<td>' . $student['identity_number'] . '</td>';
        $family_info .= '</tr>';
    }
    $family_info .= '</table>';
    //pr($edu_parent);
?>
    var eduParent_view_panel_1 = {
        html : '<?php echo $eduParent_html; ?>',
        frame : true,
        height: 100
    };
    
    var eduParent_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height:325,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
                title: 'Personal Info',
                html: '<?php echo $personal_info; ?>'
            }, {
                title: 'Subscription Info',
                html: '<?php echo $subscription_info; ?>'
            }, {
                title: 'Family Info',
                html: '<?php echo $family_info; ?>'
            }, {
                title: 'Payments Info',
                html: 'Payments Info'
            }
        ]
    });

    var EduParentViewWindow = new Ext.Window({
        title: '<?php __('View Parent'); ?>: <?php echo $edu_parent['EduParent']['authorized_person']; ?>',
        width: 800,
        height: 500,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            eduParent_view_panel_1,
            eduParent_view_panel_2
        ],

        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                EduParentViewWindow.close();
            }
        }]
    });
