<?php
$module_name = 'CC_Job_Offer';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'EXPIRE_ON' => 
  array (
    'type' => 'date',
    'label' => 'LBL_EXPIRE_ON',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_LOCATION' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ASSIGNED_LOCATION',
    'width' => '10%',
    'default' => true,
  ),
  'CONTRACT_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CONTRACT_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'PUBLISHED' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_PUBLISHED',
    'width' => '10%',
  ),
);
;
?>
