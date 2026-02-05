<html>
    <head>
        <style>
            body { font-family: DejaVuSansCondensed; font-size: 11pt;  }
            p { 	text-align: justify; margin-bottom: 4pt; margin-top:0pt;  }

            table {font-family: DejaVuSansCondensed; font-size: 9pt; line-height: 1.4;
                margin-top: 2pt; margin-bottom: 5pt;
                border-collapse: collapse;  }

            thead {	font-weight: bold; vertical-align: bottom; }
            tfoot {	font-weight: bold; vertical-align: top; }
            thead td { font-weight: bold; }
            tfoot td { font-weight: bold; }

            thead td, thead th, tfoot td, tfoot th { font-variant: small-caps; }

            .headerrow td, .headerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }
            .footerrow td, .footerrow th { background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;  }

            th {	font-weight: bold; 
                vertical-align: top; 
                text-align:left; 
                padding-left: 2mm; 
                padding-right: 2mm; 
                padding-top: 0.5mm; 
                padding-bottom: 0.5mm; 
            }

            td {	padding-left: 2mm; 
                vertical-align: top; 
                text-align:left; 
                padding-right: 2mm; 
                padding-top: 0.5mm; 
                padding-bottom: 0.5mm;
            }

            th p { text-align: left; margin:0pt;  }
            td p { text-align: left; margin:0pt;  }

            table.widecells td {
                padding-left: 5mm;
                padding-right: 5mm;
            }
            table.tallcells td {
                padding-top: 3mm;
                padding-bottom: 3mm; 
            }
                
                h1, h2, h3 {
                    font-family: DejaVuSerifCondensed;
                }

            hr {	width: 70%; height: 1px; 
                text-align: center; color: #999999; 
                margin-top: 8pt; margin-bottom: 8pt; }

            a {	color: #000066; font-style: normal; text-decoration: underline; 
                font-weight: normal; }

            ul {	text-indent: 5mm; margin-bottom: 9pt; }
            ol {	text-indent: 5mm; margin-bottom: 9pt; }

            pre { font-family: DejaVuSansMono; font-size: 9pt; margin-top: 5pt; margin-bottom: 5pt; }

            .breadcrumb {
                text-align: right; font-size: 8pt; font-family: DejaVuSerifCondensed; color: #666666;
                font-weight: bold; font-style: normal; margin-bottom: 6pt; }

            .evenrow td, .evenrow th { background-color: #f5f8f5; } 
            .oddrow td, .oddrow th { background-color: #e3ece4; } 

            .bpmTopic {	background-color: #e3ece4; width: 100%; }
            .bpmTopicC { background-color: #e3ece4; width: 100%;}
            .bpmNoLines { background-color: #e3ece4; }
            .bpmNoLinesC { background-color: #e3ece4; }
            .bpmClear {		}
            .bpmClearC { text-align: center; }
            .bpmTopnTail { background-color: #e3ece4; topntail: 0.02cm solid #495b4a;}
            .bpmTopnTailC { background-color: #e3ece4; topntail: 0.02cm solid #495b4a;}
            .bpmTopnTailClear { topntail: 0.02cm solid #495b4a; }
            .bpmTopnTailClearC { topntail: 0.02cm solid #495b4a; }

            .bpmTopicC td, .bpmTopicC td p { text-align: center; }
            .bpmNoLinesC td, .bpmNoLinesC td p { text-align: center; }
            .bpmClearC td, .bpmClearC td p { text-align: center; }
            .bpmTopnTailC td, .bpmTopnTailC td p { text-align: center;  }
            .bpmTopnTailClearC td, .bpmTopnTailClearC td p {  text-align: center;  }

            .pmhMiddleCenter { text-align:center; vertical-align:middle; }
            .pmhMiddleRight {	text-align:right; vertical-align:middle; }
            .pmhBottomCenter { text-align:center; vertical-align:bottom; }
            .pmhBottomRight {	text-align:right; vertical-align:bottom; }
            .pmhTopCenter {	text-align:center; vertical-align:top; }
            .pmhTopRight {	text-align:right; vertical-align:top; }
            .pmhTopLeft {	text-align:left; vertical-align:top; }
            .pmhBottomLeft {	text-align:left; vertical-align:bottom; }
            .pmhMiddleLeft {	text-align:left; vertical-align:middle; }

            .infobox { margin-top:10pt; background-color:#DDDDBB; text-align:center; border:1px solid #880000; }

            .bpmTopic td, .bpmTopic th  {	border-top: 1px solid #FFFFFF; }
            .bpmTopicC td, .bpmTopicC th  {	border-top: 1px solid #FFFFFF; }
            .bpmTopnTail td, .bpmTopnTail th  {	border-top: 1px solid #FFFFFF; }
            .bpmTopnTailC td, .bpmTopnTailC th  {	border: 1px solid #FFFFFF; }
        </style>
    </head>
    <body>
        <?php //pr($week_start); ?>
        <?php //pr($week_end); ?>
        <?php //pr($teacher_user); ?>
        <center>
            <h1>ATTENDANCE SHEET</h1>
            <h3 style="margin-top: -10;"><?php echo date('M d, Y', strtotime($week_start)) . ' - ' . date('M d, Y', strtotime($week_end)); ?><br/>
            Grade: <?php echo $att_records[0]['EduSection']['EduClass']['name']; ?> Section: <?php echo $att_records[0]['EduSection']['name'];  ?></h3>
        </center>

        <h5 align=right><?php echo $teacher_user['Person']['first_name'] . ' ' . $teacher_user['Person']['middle_name'] . ' ' . $teacher_user['Person']['last_name']; ?></h5>
        <table width=100% border=1px class=bpmTopic>
<?php ?>
            <tr>
                <th>Full Name</th>
<?php $days =array(); $d = $week_start; while($d <= $week_end){ ?>
                <th><?php echo date('l', strtotime($d)); $days[$d] = '-'; $d = date('Y-m-d', strtotime($d . ' +1 day')); ?></th>
<?php }  ?>
            </tr>
<?php
    // build the students and their 5 day  
    $students = array();
    $statuses = array('P' => 'Permission', 'A' => '<font color=red>Absent</font>', 'L' => 'Late', 'S' => 'Sick');
    foreach($att_records[0]['EduSection']['EduRegistration'] as $reg) {
        $students[$reg['edu_student_id']] = array(
            'name' => $reg['name'],
            'days' => $days
        );
    }

    $taken_dates = array();
    foreach($att_records as $att_record) {
        $taken_dates[$att_record['EduDay']['date']] = $att_record['EduDay']['date'];
        foreach($att_record['EduAbsentee'] as $absentee){
            if(isset($students[$absentee['edu_student_id']])) {
                $students[$absentee['edu_student_id']]['days'][$att_record['EduDay']['date']] = $statuses[$absentee['status']];
            }
        }
    }

    foreach($students as $student) {
?>
        <tr>
            <td><?php echo $student['name']; ?></td>
<?php
        foreach($student['days'] as $d => $v) {
?>
            <td><?php echo $v == '-'? (isset($taken_dates[$d])? '<font color=green>Present</font>': '-'): $v; ?></td>
<?php
        }
?>
        </tr>
<?php
    }
?>

        </table>
    </body>
</html>


<script> window.print(); </script>

