<?php
$module_name = 'CT_Activity';
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
          1 => 
          array (
            'name' => 'ct_activity_cc_employee_information_name',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'activity_date',
            'label' => 'LBL_ACTIVITY_DATE',
          ),
          1 => 
          array (
            'name' => 'activity_time',
            'label' => 'LBL_ACTIVITY_TIME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'activity_project',
            'studio' => 'visible',
            'label' => 'LBL_ACTIVITY_PROJECT',
          ),
          1 => 
          array (
            'name' => 'project_module',
            'label' => 'LBL_PROJECT_MODULE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'module_worktype',
            'label' => 'LBL_MODULE_WORKTYPE',
          ),
          1 => 
          array (
            'name' => 'is_billable',
            'label' => 'LBL_IS_BILLABLE',
          ),
        ),
        4 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'invoice_related',
            'studio' => 'visible',
            'label' => 'LBL_INVOICE_RELATED',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
