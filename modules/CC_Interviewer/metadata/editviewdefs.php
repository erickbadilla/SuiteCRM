<?php
$module_name = 'CC_Interviewer';
$viewdefs [$module_name] = 
array (
  'EditView' => 
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
      'syncDetailEditViews' => true,
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
            'name' => 'legacy_interviewer_number',
            'label' => 'LBL_LEGACY_INTERVIEWER_NUMBER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'cc_interviewer_cc_employee_information_name',
          ),
          1 => 
          array (
            'name' => 'cc_interviewer_cc_job_offer_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
