<?php
$module_name = 'CC_Extranet_Feedback';
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
        ),
        1 => 
        array (
          0 => 'description',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'rating',
            'label' => 'LBL_RATING',
          ),
          1 => 
          array (
            'name' => 'other_value',
            'label' => 'LBL_OTHER_VALUE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'cc_extranet_feedback_cc_extranet_users_name',
            'label' => 'LBL_CC_EXTRANET_FEEDBACK_CC_EXTRANET_USERS_FROM_CC_EXTRANET_USERS_TITLE',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
