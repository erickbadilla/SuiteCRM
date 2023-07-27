<?php
$module_name = 'CC_Qualification';
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
            'name' => 'mininum_requiered',
            'studio' => 'visible',
            'label' => 'LBL_MININUM_REQUIERED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
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
            'name' => 'cc_qualification_cc_qualification_name',
            'label' => 'LBL_CC_QUALIFICATION_CC_QUALIFICATION_FROM_CC_QUALIFICATION_L_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
