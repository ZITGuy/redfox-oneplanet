//<script>
var marital_status = Ext.getCmp('marital_status');
var primaryParentBox = Ext.getCmp('primaryParentBox');
var authorized_person = Ext.getCmp('authorized_person');
var phone_for_sms = Ext.getCmp('phone_for_sms');

var motherName1 = Ext.getCmp('motherName1');
var motherName2 = Ext.getCmp('motherName2');
var motherName3 = Ext.getCmp('motherName3');
var mother_residence_address = Ext.getCmp('mother_residence_address');
var mother_nationality = Ext.getCmp('mother_nationality');
var mother_country_of_birth = Ext.getCmp('mother_country_of_birth');
var mother_occupation = Ext.getCmp('mother_occupation');
var mother_academic_qualification = Ext.getCmp('mother_academic_qualification');
var mother_employment = Ext.getCmp('mother_employment');
var mother_employment_organization = Ext.getCmp('mother_employment_organization');
var motherMobile = Ext.getCmp('motherMobile');
var motherWorkAddress = Ext.getCmp('motherWorkAddress');
var motherWorkTelephone = Ext.getCmp('motherWorkTelephone');
var motherEmail = Ext.getCmp('motherEmail');
var motherPobox = Ext.getCmp('motherPobox');

var fatherName1 = Ext.getCmp('fatherName1');
var fatherName2 = Ext.getCmp('fatherName2');
var fatherName3 = Ext.getCmp('fatherName3');
var father_residence_address = Ext.getCmp('father_residence_address');
var father_nationality = Ext.getCmp('father_nationality');
var father_country_of_birth = Ext.getCmp('father_country_of_birth');
var father_occupation = Ext.getCmp('father_occupation');
var father_academic_qualification = Ext.getCmp('father_academic_qualification');
var father_employment = Ext.getCmp('father_employment');
var father_employment_organization = Ext.getCmp('father_employment_organization');
var fatherMobile = Ext.getCmp('fatherMobile');
var fatherWorkAddress = Ext.getCmp('fatherWorkAddress');
var fatherWorkTelephone = Ext.getCmp('fatherWorkTelephone');
var fatherEmail = Ext.getCmp('fatherEmail');
var fatherPobox = Ext.getCmp('fatherPobox');

var guardianName1 = Ext.getCmp('guardianName1');
var guardianName2 = Ext.getCmp('guardianName2');
var guardianName3 = Ext.getCmp('guardianName3');
var guardian_residence_address = Ext.getCmp('guardian_residence_address');
var guardian_nationality = Ext.getCmp('guardian_nationality');
var guardian_country_of_birth = Ext.getCmp('guardian_country_of_birth');
var guardian_relationship = Ext.getCmp('guardian_relationship');
var guardian_relationship_other = Ext.getCmp('guardian_relationship_other');
var guardian_occupation = Ext.getCmp('guardian_occupation');
var guardian_academic_qualification = Ext.getCmp('guardian_academic_qualification');
var guardian_employment = Ext.getCmp('guardian_employment');
var guardian_employment_organization = Ext.getCmp('guardian_employment_organization');
var guardianMobile = Ext.getCmp('guardianMobile');
var guardianWorkAddress = Ext.getCmp('guardianWorkAddress');
var guardianWorkTelephone = Ext.getCmp('guardianWorkTelephone');
var guardianEmail = Ext.getCmp('guardianEmail');
var guardianPobox = Ext.getCmp('guardianPobox');


marital_status.setValue('<?php echo $edu_parent['EduParent']['marital_status'];  ?>');
primaryParentBox.setValue('<?php echo $edu_parent['EduParent']['primary_parent'];  ?>');
authorized_person.setValue('<?php echo $edu_parent['EduParent']['authorized_person'];  ?>');
phone_for_sms.setValue('<?php echo $edu_parent['EduParent']['sms_phone_number'];  ?>');

motherName1.setValue('');
motherName2.setValue('');
motherName3.setValue('');
mother_residence_address.setValue('');
mother_nationality.setValue('Ethiopian');
mother_country_of_birth.setValue('Ethiopia');
mother_occupation.setValue('');
mother_academic_qualification.setValue('NA');
mother_employment.setValue('NA');
mother_employment_organization.setValue('');
motherMobile.setValue('');
motherWorkAddress.setValue('');
motherWorkTelephone.setValue('');
motherEmail.setValue('');
motherPobox.setValue('');

fatherName1.setValue('');
fatherName2.setValue('');
fatherName3.setValue('');
father_residence_address.setValue('');
father_nationality.setValue('Ethiopian');
father_country_of_birth.setValue('Ethiopia');
father_occupation.setValue('');
father_academic_qualification.setValue('NA');
father_employment.setValue('NA');
father_employment_organization.setValue('');
fatherMobile.setValue('');
fatherWorkAddress.setValue('');
fatherWorkTelephone.setValue('');
fatherEmail.setValue('');
fatherPobox.setValue('');

guardianName1.setValue('');
guardianName2.setValue('');
guardianName3.setValue('');
guardian_residence_address.setValue('');
guardian_nationality.setValue('Ethiopian');
guardian_country_of_birth.setValue('Ethiopia');
guardian_relationship.setValue('NA');
guardian_relationship_other.setValue('');
guardian_occupation.setValue('');
guardian_academic_qualification.setValue('NA');
guardian_employment.setValue('NA');
guardian_employment_organization.setValue('');
guardianMobile.setValue('');
guardianWorkAddress.setValue('');
guardianWorkTelephone.setValue('');
guardianEmail.setValue('');
guardianPobox.setValue('');

<?php foreach ($edu_parent['EduParentDetail'] as $parent) { ?>
<?php    if($parent['family_type'] == 'M') {   ?>
motherName1.setValue('<?php echo $parent['first_name'];  ?>');
motherName2.setValue('<?php echo $parent['middle_name'];  ?>');
motherName3.setValue('<?php echo $parent['last_name'] == 'NA'? '': $parent['last_name'];  ?>');
mother_residence_address.setValue('<?php echo $parent['residence_address'];  ?>');
mother_nationality.setValue('<?php echo $parent['nationality'];  ?>');
mother_country_of_birth.setValue('<?php echo $parent['country_of_birth'];  ?>');
mother_occupation.setValue('<?php echo $parent['occupation'];  ?>');
mother_academic_qualification.setValue('<?php echo $parent['academic_qualification'];  ?>');
mother_employment.setValue('<?php echo $parent['employment_status'];  ?>');
mother_employment_organization.setValue('<?php echo $parent['employer'];  ?>');
motherMobile.setValue('<?php echo $parent['mobile'] == 'NA'? '': $parent['mobile'];  ?>');
motherWorkAddress.setValue('<?php echo $parent['work_address'] == 'NA'? '': $parent['work_address'];  ?>');
motherWorkTelephone.setValue('<?php echo $parent['work_telephone'] == 'NA'? '': $parent['work_telephone'];  ?>');
motherEmail.setValue('<?php echo $parent['email'] <> 'NA'? $parent['email']: '';  ?>');
motherPobox.setValue('<?php echo $parent['pobox'] <> 'NA'? $parent['pobox']: '';  ?>');
<?php    } elseif($parent['family_type'] == 'F') {   ?>
fatherName1.setValue('<?php echo $parent['first_name'];  ?>');
fatherName2.setValue('<?php echo $parent['middle_name'];  ?>');
fatherName3.setValue('<?php echo $parent['last_name'] == 'NA'? '': $parent['last_name'];  ?>');
father_residence_address.setValue('<?php echo $parent['residence_address'];  ?>');
father_nationality.setValue('<?php echo $parent['nationality'];  ?>');
father_country_of_birth.setValue('<?php echo $parent['country_of_birth'];  ?>');
father_occupation.setValue('<?php echo $parent['occupation'];  ?>');
father_academic_qualification.setValue('<?php echo $parent['academic_qualification'];  ?>');
father_employment.setValue('<?php echo $parent['employment_status'];  ?>');
father_employment_organization.setValue('<?php echo $parent['employer'];  ?>');
fatherMobile.setValue('<?php echo $parent['mobile'] == 'NA'? '': $parent['mobile'];  ?>');
fatherWorkAddress.setValue('<?php echo $parent['work_address'] == 'NA'? '': $parent['work_address'];  ?>');
fatherWorkTelephone.setValue('<?php echo $parent['work_telephone'] == 'NA'? '': $parent['work_telephone'];  ?>');
fatherEmail.setValue('<?php echo $parent['email'] <> 'NA'? $parent['email']: '';  ?>');
fatherPobox.setValue('<?php echo $parent['pobox'] <> 'NA'? $parent['pobox']: '';  ?>');
<?php    } elseif($parent['family_type'] == 'G') {   ?>
guardianName1.setValue('<?php echo $parent['first_name'];  ?>');
guardianName2.setValue('<?php echo $parent['middle_name'];  ?>');
guardianName3.setValue('<?php echo $parent['last_name'] == 'NA'? '': $parent['last_name'];  ?>');
guardian_residence_address.setValue('<?php echo $parent['residence_address'];  ?>');
guardian_nationality.setValue('<?php echo $parent['nationality'];  ?>');
guardian_country_of_birth.setValue('<?php echo $parent['country_of_birth'];  ?>');
guardian_relationship.setValue('<?php echo $parent['relationship'];  ?>');
guardian_relationship_other.setValue('<?php echo $parent['relationship_other'];  ?>');
guardian_occupation.setValue('<?php echo $parent['occupation'];  ?>');
guardian_academic_qualification.setValue('<?php echo $parent['academic_qualification'];  ?>');
guardian_employment.setValue('<?php echo $parent['employment_status'];  ?>');
guardian_employment_organization.setValue('<?php echo $parent['employer'];  ?>');
guardianMobile.setValue('<?php echo $parent['mobile'] == 'NA'? '': $parent['mobile'];  ?>');
guardianWorkAddress.setValue('<?php echo $parent['work_address'] == 'NA'? '': $parent['work_address'];  ?>');
guardianWorkTelephone.setValue('<?php echo $parent['work_telephone'] == 'NA'? '': $parent['work_telephone'];  ?>');
guardianEmail.setValue('<?php echo $parent['email'] <> 'NA'? $parent['email']: '';  ?>');
guardianPobox.setValue('<?php echo $parent['pobox'] <> 'NA'? $parent['pobox']: '';  ?>');
<?php    }  ?>
<?php } ?>