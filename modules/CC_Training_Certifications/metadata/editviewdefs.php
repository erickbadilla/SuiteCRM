<?php
$module_name = 'CC_Training_Certifications';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'javascript' => '{sugar_getscript file="modules/CC_Training_Certifications/js/trainingCertificationFormValidation.js"}',
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
        'LBL_EDITVIEW_PANEL1' => 
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
            'name' => 'issuing_organization',
            'label' => 'LBL_ISSUING_ORGANIZATION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'training_type',
            'studio' => 'visible',
            'label' => 'LBL_TRAINING_TYPE',
          ),
          1 => 
          array (
            'name' => 'level_reached',
            'studio' => 'visible',
            'label' => 'LBL_LEVEL_REACHED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'start_month',
            'studio' => 'visible',
            'label' => 'LBL_START_MONTH',
          ),
          1 => 
          array (
            'name' => 'start_year',
            'label' => 'LBL_START_YEAR',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'end_month',
            'studio' => 'visible',
            'label' => 'LBL_END_MONTH',
          ),
          1 => 
          array (
            'name' => 'end_year',
            'label' => 'LBL_END_YEAR',
          ),
        ),
        4 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'never_expire',
            'label' => 'LBL_NEVER_EXPIRE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'expires_on_month',
            'studio' => 'visible',
            'label' => 'LBL_EXPIRES_ON_MONTH',
          ),
          1 => 
          array (
            'name' => 'expires_on_year',
            'label' => 'LBL_EXPIRES_ON_YEAR',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'certification_id',
            'label' => 'LBL_CERTIFICATION_ID',
          ),
          1 => 
          array (
            'name' => 'cert_url',
            'label' => 'LBL_CERT_URL',
          ),
        ),
      ),
    ),
  ),
);
;
?>
