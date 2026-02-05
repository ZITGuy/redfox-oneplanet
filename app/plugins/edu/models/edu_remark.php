<?php
class EduRemark extends EduAppModel {
	var $name = 'EduRemark';
	var $validate = array(
		'edu_section_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
        'edu_quarter_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'edu_assessment_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'edu_assessment_record_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'edu_registration_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'mark_old' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'mark_new' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'status' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
	);
}
?>