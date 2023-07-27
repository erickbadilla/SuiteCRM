<?php
$module_name = 'CC_External_Employee';
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
            'name' => 'cc_external_employee_cc_job_offer_name',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'cc_external_employee_cc_employee_information_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
