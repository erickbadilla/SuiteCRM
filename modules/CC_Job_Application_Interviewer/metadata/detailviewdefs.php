<?php
$module_name = 'CC_Job_Application_Interviewer';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'cc_job_application_interviewer_cc_interviewer_name',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'cc_job_application_interviewer_cc_job_application_name',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'interview_type',
            'studio' => 'visible',
            'label' => 'LBL_INTERVIEW_TYPE',
          ),
          1 => 
          array (
            'name' => 'interview_result',
            'label' => 'LBL_INTERVIEW_RESULT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'cc_job_applications_cc_job_application_interviewer_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
