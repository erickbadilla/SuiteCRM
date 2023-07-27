<?php
$module_name = 'CC_Job_Application_Interviewer';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
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
        2 => 
        array (
          0 => 
          array (
            'name' => 'cc_job_application_interviewer_cc_interviewer_name',
            'label' => 'LBL_CC_JOB_APPLICATION_INTERVIEWER_CC_INTERVIEWER_FROM_CC_INTERVIEWER_TITLE',
          ),
          1 => 
          array (
            'name' => 'cc_job_application_interviewer_cc_job_application_name',
            'label' => 'LBL_CC_JOB_APPLICATION_INTERVIEWER_CC_JOB_APPLICATION_FROM_CC_JOB_APPLICATION_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
