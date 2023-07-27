<?php
$module_name = 'CC_Qualification';
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
            'name' => 'mininum_requiered',
            'studio' => 'visible',
            'label' => 'LBL_MININUM_REQUIERED',
          ),
        ),
        1 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'digital_support_required',
            'label' => 'LBL_DIGITAL_SUPPORT_REQUIRED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'favourite',
            'label' => 'LBL_FAVOURITE',
          ),
          1 => 
          array (
            'name' => 'cc_qualification_cc_qualification_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
