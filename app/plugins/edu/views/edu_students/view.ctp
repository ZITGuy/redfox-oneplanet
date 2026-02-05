//<script>
<?php 
    //pr($edu_student);
    $pp = $edu_student['EduParent']['primary_parent']; 
    $parent_photo = '';
    $parent_photo2 = '';
    $parent_name = '';
	if(!empty($edu_student['EduParent']) && isset($edu_student['EduParent']['EduParentDetail'])){
		foreach($edu_student['EduParent']['EduParentDetail'] as $pd){
			if($pd['family_type'] == $pp){
				$parent_photo = $pd['photo_file'];
				$parent_name = $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'];
				if($parent_photo2 <> ''){
					break;
				}
			} else {
				if($parent_photo2 == ''){
					$parent_photo2 = $pd['photo_file'];
				}
			}
		}
	}
?>
<?php 
    $eduStudent_html = "<table cellspacing=3 width=100%>" . 		
        "<tr>" . 
            "<td align=left valign=top width=15%>" . 
                $this->Html->image(($edu_student['EduStudent']['photo_file_name'] == 'No file'? 'student-' . strtolower($edu_student['EduStudent']['gender']) . '.jpg': 'students/' . $edu_student['EduStudent']['photo_file_name']), array('width'=>'80px', 'title' => 'Student Photo')) . "</td>" . 
            "<td><center><font color=darkblue size=5em><b>" . $edu_student['EduStudent']['name'] . "</b></font> " . 
                "<br/><font color=blue size=3em><b>(" . $edu_student['EduStudent']['identity_number'] . ") @ " . $edu_student['EduRegistration'][0]['EduCampus']['name'] . "</b></font></center></td>" . 
            "<td align=right valign=top width=35%>" . 
                $this->Html->image(($parent_photo == 'No file' || $parent_photo == ''? 'default-m.jpg': 'parents/' . $parent_photo), array('width'=>'80px', 'title' => 'Primary Parent Photo', 'style' => 'margin-right: 10px;')) . 
                $this->Html->image(($parent_photo2 == 'No file' || $parent_photo2 == ''? 'default-f.jpg': 'parents/' . $parent_photo2), array('width'=>'80px', 'title' => 'Parent Photo')) . 
            "</td>" . 
        "</tr>" . 
    "</table>"; 
    
    //$statuses = array('1'=>'Active', '2' =>'Inactive', '3'=>'Dismissed', '4'=>'Withdrawn', 
	//'5'=>'Transferred', '6'=>'Incomplete','7' => 'Enrolled but not registered', '8' => 'Other', 'A' => 'Active', 'I' => 'Inactive', 'P' => 'Promoted', 'N' => 'Not Promoted');
    
    $personal_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.2em; font-color: darkblue;">';
    $personal_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Date of Birth: </td><td><b>&nbsp;' . date('F d, Y', strtotime($edu_student['EduStudent']['birth_date'])) . '</b></td></tr>';
    $personal_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Date of Enrollment: </td><td><b>&nbsp;' . date('F d, Y', strtotime($edu_student['EduStudent']['registration_date'])) . '</b></td></tr>';
    $personal_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Gender: </td><td><b>&nbsp;' . ($edu_student['EduStudent']['gender'] == 'F'? 'Female': 'Male') . '</b></td></tr>';
    $personal_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Nationality: </td><td><b>&nbsp;' . $edu_student['EduStudent']['nationality'] . '</b></td></tr>';
    $personal_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Student Status: </td><td><b>&nbsp;' . $statuses[$edu_student['EduStudent']['status_id']] . '</b></td></tr>';
    $personal_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Registered By: </td><td><b>&nbsp;' . $edu_student['Maker']['Person']['first_name'] . ' ' . $edu_student['Maker']['Person']['middle_name'] . ' ' . $edu_student['Maker']['Person']['last_name'] . '</b></td></tr>';
    $personal_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Last Modified: </td><td><b>&nbsp;' . date('F d, Y H:i:s A', strtotime($edu_student['EduStudent']['modified'])) . '</b></td></tr>';
    $personal_info .= '</table>';
    
    $parent_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.2em; font-color: darkblue;">';
    $parent_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Marital Status: </td><td><b>&nbsp;' . $edu_student['EduParent']['marital_status'] . '</b></td></tr>';
    $parent_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Primary Parent: </td><td><b>&nbsp;' . $edu_student['EduParent']['primary_parent'] . '</b></td></tr>';
    $parent_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Authorized Person(s): </td><td><b>&nbsp;' . $edu_student['EduParent']['authorized_person'] . '</b></td></tr>';
    $colors = array('eeeeff', 'eeeeee');
	
	if(isset($edu_student['EduParent']['EduParentDetail'])){
		$count = 0;
		foreach($edu_student['EduParent']['EduParentDetail'] as $pd) {
			$parent_info .=  '<tr bgcolor=#' . ($colors[$count%2]) . '><td colspan=2 align=left><b>&nbsp;' . strtoupper($pd['relationship']) . '</b></td></tr>';
			$parent_info .=  '<tr bgcolor=#' . ($colors[$count%2]) . '><td align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Full Name: </td><td><b>&nbsp;' . $pd['first_name'] . ' ' . $pd['middle_name'] . ' ' . $pd['last_name'] . '</b></td></tr>';
			$parent_info .=  '<tr bgcolor=#' . ($colors[$count++%2]) . '><td align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mobile: </td><td><b>&nbsp;' . $pd['mobile'] . '</b></td></tr>';
		}
	}
    $parent_info .= '</table>';
	$student_condition_info = "";
	
    if((isset($edu_student['EduStudentCondition'][0]))){
		$student_condition_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.2em; font-color: darkblue;">';
		$student_condition_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Learning Condition: </td><td><b>&nbsp;' . $edu_student['EduStudentCondition'][0]['learning_condition'] . '</b></td></tr>';
		$student_condition_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Health Condition: </td><td><b>&nbsp;' . $edu_student['EduStudentCondition'][0]['health_condition'] . '</b></td></tr>';
		$student_condition_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Treatment Type: </td><td><b>&nbsp;' . ($edu_student['EduStudentCondition'][0]['treatment_type']==''? 'NA': $edu_student['EduStudentCondition'][0]['treatment_type']) . '</b></td></tr>';
		$student_condition_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Health Care Institute: </td><td><b>&nbsp;' . ($edu_student['EduStudentCondition'][0]['health_care_institute']==''? 'NA': $edu_student['EduStudentCondition'][0]['health_care_institute']) . '</b></td></tr>';
		$student_condition_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Physician: </td><td><b>&nbsp;' . ($edu_student['EduStudentCondition'][0]['physician']==''? 'NA': $edu_student['EduStudentCondition'][0]['physician']) . '</b></td></tr>';
		$student_condition_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Alergy: </td><td><b>&nbsp;' . $edu_student['EduStudentCondition'][0]['alergy'] . '</b></td></tr>';
		$student_condition_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Physical Condition: </td><td><b>&nbsp;' . $edu_student['EduStudentCondition'][0]['physical_condition'] . '</b></td></tr>';
		$student_condition_info .= '</table>';
	}
    
    $reg = $edu_student['EduRegistration'][count($edu_student['EduRegistration']) - 1];
    $academic_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.2em; font-color: darkblue;">';
    $academic_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;Class/Grade: </td><td><b>&nbsp;' . $reg['EduClass']['name'] . '</b></td></tr>';
    $academic_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Section: </td><td><b>&nbsp;' . (isset($reg['EduSection']['name'])? $reg['EduSection']['name']: 'Not Yet Sectioned') . '</b></td></tr>';
    $academic_info .=  '<tr bgcolor=#eeeeee><td align=left>&nbsp;N<u>o</u> of Failures: </td><td><b>&nbsp;' . $reg['failure_count'] . '</b></td></tr>';
    $academic_info .=  '<tr bgcolor=#eeeeff><td align=left>&nbsp;Allowed for Registration: </td><td><b>&nbsp;' . ($reg['allowed'] == 'A'? 'Yes': 'No') . '</b></td></tr>';
    $academic_info .= '</table>';
    $academic_info .= '<fieldset style="margin: 12px; border-color: red;" class=viewtable>';
    $academic_info .= '<legend>Quarters Information</legend>';
    $academic_info .= '<table width=100% class=viewtable style="font-size: 1.2em; font-color: darkblue;">';
    $academic_info .= '<tr bgcolor=#ddeedd><td><b>Quarter</b></td><td><b>Average</b></td><td><b>Rank</b></td><td><b>Class Rank</b></td></tr>';
    
	$count = 0;
    foreach($reg['EduRegistrationQuarter'] as $q) {
        $academic_info .= '<tr bgcolor=#' . ($colors[$count++%2]) . '><td>' . $edu_quarters[$q['edu_quarter_id']] . 
                '</td><td>' . $q['quarter_average'] . 
                '</td><td>' . $q['quarter_rank'] . 
                '</td><td>' . $q['class_rank'] . 
                '</td></tr>';
    }
    $academic_info .= '</table>';
    $academic_info .= '</fieldset>';
    
    $registration_info = '<table width=100% cellspacing=3 class=viewtable style="font-size: 1.2em; font-color: darkblue;">';
    $registration_info .= '<tr bgcolor=#ddeedd><td><b>Academic Year</b></td><td><b>Class/Grade</b></td><td><b>Grand Total Average</b></td><td><b>Rank</b></td><td><b>Class Rank</b></td><td><b>Status</b></td></tr>';
    
	$count = 0;
	foreach($edu_student['EduRegistration'] as $rg) {
        $registration_info .= '<tr bgcolor=#' . ($colors[$count++%2]) . '><td>' . $ays[$rg['EduSection']['edu_academic_year_id']] . 
                '</td><td>' . $rg['EduClass']['name'] . 
                '</td><td>' . (($rg['rank'] > 1)? $rg['grand_total_average']: 'NA') . 
                '</td><td>' . (($rg['rank'] > 1)? $rg['rank']: 'NA') . 
                '</td><td>' . (($rg['rank'] > 1)? $rg['rank']: 'NA') . 
                '</td><td>' . $statuses[$rg['status_id']] . 
                '</td></tr>';
    }
    $registration_info .= '</table>';
    
    
?>
    var eduStudent_view_panel_1 = {
        html : '<?php echo $eduStudent_html; ?>',
        frame : true,
        height: 100
    };
    
    var eduStudent_view_panel_2 = new Ext.TabPanel({
        activeTab: 0,
        anchor: '100%',
        height: 340,
        plain: true,
        defaults:{autoScroll: true},
        items:[{
            title: 'Personal Info',
            html: '<?php echo $personal_info; ?>'
        }, {
            title: 'Student Condition Info',
            html: '<?php echo $student_condition_info; ?>'
        }, {
            title: 'Academic Info',
            html: '<?php echo $academic_info; ?>'
        }, {
            title: 'Parent Info',
            html: '<?php echo $parent_info; ?>'
        }, {
            title: 'Registration Info',
            html: '<?php echo $registration_info; ?>'
        }, {
            title: 'Trend Analysis',
            html: '<h2>Under Development</h2>'
        }]
    });

    var EduStudentViewWindow = new Ext.Window({
        title: '<?php __('Student'); ?>: <?php echo $edu_student['EduStudent']['name']; ?>',
        width: 800,
        height: 515,
        resizable: false,
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        modal: true,
        items: [ 
            eduStudent_view_panel_1,
            eduStudent_view_panel_2
        ],

        buttons: [{
            text: '<?php __('Close'); ?>',
            handler: function(btn){
                EduStudentViewWindow.close();
            }
        }]
    });
