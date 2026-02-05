<?php

$html = 
'
	<div style="position: absolute; top: 60; left: 57; width: 680px; height: 200px;">
			<table border="0" width="100%">
				<tr>
					<td align="center">
						<center>
							<h1>' . $company_name . '</h1>
							<h1>Enrollment Certificate</h1>
						</center>
					</td>
				</tr>
				<tr>
					<td align="center">' . $company_address . '</td>
				</tr>
				<tr>
					<td align="center">TIN: ' . $company_tin . '</td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center"><h3>' . $certificate['Student']['name'] . ' - ' . $certificate['Student']['identity_number'] . '</h3></td>
				</tr>
			</table>
		</div>
		<div style="position: absolute; top: 70; left: 67; width: 100px; height: 100px;">
			<img src="http://' . Configure::read('domain') . Configure::read('localhost_string') . '/img/logo_cha.png" width=100px>
		</div>
		';

// About the Record Summary
$html .= '
<div style="position: absolute; top: 270; left: 57; width: 680px; height: 200px; background-color: #fafafa">
<h3>General Information</h3>
<table class="bpmTopic" width="60%">
    <tbody>
        <tr>
            <th width="40%">Student Status</th>
            <td>' . $certificate['Student']['status'] . '</td>
        </tr>
        <tr>
            <th>Enrollment Date</th>
            <td>' . $certificate['Student']['enrollment_date'] . '</td>
        </tr>
        <tr>
            <th>Last Modified Date</th>
            <td>' . $certificate['Student']['last_modified'] . '</td>
        </tr>
        <tr>
            <th width="40%">Primary Parent</th>
            <td>' . $certificate['Student']['primary_parent'] . '</td>
        </tr>
        <tr>
            <th>Relationship</th>
            <td>' . strtoupper($certificate['Student']['relationship']) . '</td>
        </tr>
        <tr>
            <th>Authorized Persons</th>
            <td>' . $certificate['Student']['authorized_person'] . '</td>
        </tr>
    </tbody>
</table>';

// About the Record Summary
$html .= 
'<h3>Parental Information</h3>
<table class="bpmTopic" width="100%">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Mother</td>
            <th>Father</td>
            <th>Guardian</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Full Name:</th>
            <td>' . $certificate['Parents']['M']['full_name'] . '</td>
            <td>' . $certificate['Parents']['F']['full_name'] . '</td>
            <td>' . $certificate['Parents']['G']['full_name'] . '</td>
        </tr>
        <tr>
            <th>Address:</th>
            <td>' . strtoupper($certificate['Parents']['M']['address']) . '</td>
            <td>' . strtoupper($certificate['Parents']['F']['address']) . '</td>
            <td>' . strtoupper($certificate['Parents']['G']['address']) . '</td>
        </tr>
        <tr>
            <th>Telephone:</th>
            <td>' . ($certificate['Parents']['M']['telephone']=='NA'? '-': $certificate['Parents']['M']['telephone']) . '</td>
            <td>' . ($certificate['Parents']['F']['telephone']=='NA'? '-': $certificate['Parents']['F']['telephone']) . '</td>
            <td>' . ($certificate['Parents']['G']['telephone']=='NA'? '-': $certificate['Parents']['G']['telephone']) . '</td>
        </tr>
    </tbody>
</table>';

// About the Record Summary
$emergency_contact_detail = '';
foreach($emergency_contacts as $ec) {
	$ee = $ec['EduEmergencyContact'];
	$emergency_contact_detail .= '
		<tr>
				<td>' . $ee['first_name'] . ' ' . $ee['middle_name'] . ' ' . $ee['last_name'] . '</td>
				<td>' . $ee['relationship'] . '</td>
				<td>' . $ee['phone_number'] . '</td>
		</tr>
	';
}
if($emergency_contact_detail != '') {
	$html .= 
	'<h3>Emergency Contact Information</h3>
	<table class="bpmTopic" width="100%">
		<thead>
			<tr>
				<th>Full Name</td>
				<th>Relationship</td>
				<th>Phone</td>
			</tr>
		</thead>
		<tbody>
			' . $emergency_contact_detail . '
		</tbody>
	</table>';
}
// About the Student Detail
$html .= 
'<h3>Student Detail</h3>
<table class="bpmTopic" width="100%">
    <thead>
        <tr>
            <th colspan="2">Personal Info</th>
            <th colspan="2">Academic Info</th>
            <th colspan="2">Student Condition</th>
            <th colspan="2">Documents Presented</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Name:</th>
            <td>' . $certificate['Student']['name'] . '</td>
            <th>Grade:</th>
            <td>' . $certificate['Student']['grade'] . '</td>
            <th>Learning:</th>
            <td>' . $certificate['Student']['learning_condition'] . '</td>
            <th>' . $certificate['Student']['docs']['vaccination'] . '</th>
            <td>Vaccination</td>
        </tr>
        <tr>
            <th>Birth Date:</th>
            <td>' . $certificate['Student']['birth_date'] . '</td>
            <th>Section:</th>
            <td>' . $certificate['Student']['section'] . '</td>
            <th>Health:</th>
            <td>' . $certificate['Student']['health_condition'] . '</td>
            <th>' . $certificate['Student']['docs']['birth_certificate'] . ':</th><td>Birth Certificate</td>
        </tr>
        <tr>
            <th>Gender:</th>
            <td>' . $certificate['Student']['gender'] . '</td>
            <th>&nbsp;</th>
            <td>&nbsp;</td>
            <th>Physical:</th>
            <td>' . $certificate['Student']['physical_condition'] . '</td>
            <th>' . $certificate['Student']['docs']['report_card'] . ':</th><td>Report Card</td>
        </tr>
        <tr>
            <th>ID Number:</th>
            <td>' . $certificate['Student']['identity_number'] . '</td>
            <th>&nbsp;</th>
            <td>&nbsp;</td>
            <th>&nbsp;</th>
            <td>&nbsp;</td>
            <th>' . $certificate['Student']['docs']['clearance'] . ':</th><td>Clearance</td>
        </tr>
    </tbody>
</table>';

// About the Credential
$html .= 
'<h3>Secret Code</h3>
<table class="bpmTopic" width="100%">
    <tbody>
        <tr>
            <th>Please use this secret code to register yourself on the Redfox portal:</th>
            <td>' . $certificate['Parent']['secret_code'] . '</td>
        </tr>
    </tbody>
</table>
</div>
';

echo $html;