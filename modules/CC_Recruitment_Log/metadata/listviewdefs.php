<?php
$module_name = 'CC_Recruitment_Log';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'METHOD' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_METHOD',
    'width' => '10%',
    'default' => true,
  ),
  'ENDPOINT' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ENDPOINT',
    'width' => '10%',
    'default' => true,
  ),
  'RESPONSE_STATUS' => 
  array (
    'type' => 'int',
    'label' => 'LBL_RESPONSE_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'RESPONSE' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_RESPONSE',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
