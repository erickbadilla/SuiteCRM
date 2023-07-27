<?php
$module_name = 'CC_Endpoint_Event';
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
            'name' => 'external_application_name',
            'label' => 'LBL_EXTERNAL_APPLICATION_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'cc_endpoint_event_cc_external_application_name',
          ),
          1 => 
          array (
            'name' => 'event_name',
            'studio' => 'visible',
            'label' => 'LBL_EVENT_NAME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'allow_html',
            'label' => 'LBL_ALLOW_HTML',
          ),
          1 => 
          array (
            'name' => 'method',
            'studio' => 'visible',
            'label' => 'LBL_METHOD',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'path',
            'label' => 'LBL_PATH',
          ),
          1 => 
          array (
            'name' => 'notify',
            'label' => 'LBL_NOTIFY',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'template_name',
            'label' => 'LBL_TEMPLATE_NAME',
          ),
        ),
      ),
    ),
  ),
);
;
?>
