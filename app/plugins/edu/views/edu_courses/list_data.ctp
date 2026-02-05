{
    success:true,
    results: <?php echo $results; ?>,
    rows: [
<?php $st = false; foreach($edu_courses as $edu_course){ if($st) echo ","; ?>			{
        "id":"<?php echo $edu_course['EduCourse']['id']; ?>",
        "edu_class":"<?php echo $edu_course['EduClass']['name']; ?>",
        "id2":"<?php echo $edu_course['EduSubject']['name']; ?>",
        "name":"<?php echo $edu_course['EduSubject']['name']; ?>",
        "edu_subject":"<?php echo $edu_course['EduSubject']['name']; ?>",
        "description":"<?php echo $edu_course['EduCourse']['description']; ?>",
        "min_for_pass":"<?php echo $edu_course['EduCourse']['min_for_pass']; ?>",
        "is_mandatory":"<?php echo $edu_course['EduCourse']['is_mandatory'] == 1? 'Yes': 'No'; ?>",
        "is_scale_based":"<?php echo $edu_course['EduCourse']['is_scale_based'] == 1? 'Yes': 'No'; ?>",
        "created":"<?php echo $edu_course['EduCourse']['created']; ?>",
        "modified":"<?php echo $edu_course['EduCourse']['modified']; ?>"			}
<?php $st = true; } ?>		
    ]
}