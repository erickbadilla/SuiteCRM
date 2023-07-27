<?php
$module_name = 'CC_Interviewer';
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
          1 => 
          array (
            'name' => 'interviewer_role',
            'studio' => 'visible',
            'label' => 'LBL_INTERVIEWER_ROLE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'cc_interviewer_cc_employee_information_name',
            'label' => 'LBL_CC_INTERVIEWER_CC_EMPLOYEE_INFORMATION_FROM_CC_EMPLOYEE_INFORMATION_TITLE',
          ),
          1 => 
          array (
            'name' => 'cc_interviewer_cc_job_offer_name',
            'label' => 'LBL_CC_INTERVIEWER_CC_JOB_OFFER_FROM_CC_JOB_OFFER_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
