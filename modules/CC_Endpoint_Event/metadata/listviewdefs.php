<?php
$module_name = 'CC_Endpoint_Event';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'EXTERNAL_APPLICATION_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EXTERNAL_APPLICATION_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'ALLOW_HTML' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ALLOW_HTML',
    'width' => '10%',
  ),
  'EVENT_NAME' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_EVENT_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'METHOD' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_METHOD',
    'width' => '10%',
    'default' => true,
  ),
  'NOTIFY' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_NOTIFY',
    'width' => '10%',
  ),
  'PATH' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PATH',
    'width' => '10%',
    'default' => true,
  ),
  'TEMPLATE_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_TEMPLATE_NAME',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
