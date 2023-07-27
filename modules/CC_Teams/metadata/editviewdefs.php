<?php
$module_name = 'CC_Teams';
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
            'name' => 'user_related',
            'studio' => 'visible',
            'label' => 'LBL_USER_RELATED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'teammate',
            'studio' => 'visible',
            'label' => 'LBL_TEAMMATE',
          ),
          1 => 
          array (
            'name' => 'lead_pm',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_PM',
          ),
        ),
        2 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
