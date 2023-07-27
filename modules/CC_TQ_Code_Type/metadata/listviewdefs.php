<?php
$module_name = 'CC_TQ_Code_Type';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'EXTENSION' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EXTENSION',
    'width' => '10%',
    'default' => true,
  ),
  'EXTERNAL_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EXTERNAL_ID',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
;
?>
