<?php
$module_name = 'CC_Teams';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'lead_pm' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_LEAD_PM',
        'id' => 'CC_EMPLOYEE_INFORMATION_ID1_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'lead_pm',
      ),
      'user_related' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_USER_RELATED',
        'id' => 'USER_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'user_related',
      ),
      'teammate' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_TEAMMATE',
        'id' => 'CC_EMPLOYEE_INFORMATION_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'teammate',
      ),
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
