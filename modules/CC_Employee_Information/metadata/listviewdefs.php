<?php
$module_name = 'CC_Employee_Information';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'COUNTRY_LAW' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_COUNTRY_LAW',
    'width' => '10%',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'POSITION' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_POSITION',
    'id' => 'CC_JOB_DESCRIPTION_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'ENGLISH_LEVEL' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_ENGLISH_LEVEL',
    'width' => '10%',
  ),
  'PROJECT' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PROJECT ',
    'id' => 'PROJECT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ACTIVE',
    'width' => '10%',
  ),
  'IS_ASSIGNED' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_IS_ASSIGNED',
    'width' => '10%',
  ),
  'HAS_PASSPORT' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_HAS_PASSPORT',
    'width' => '10%',
  ),
  'HAS_VISA' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_HAS_VISA',
    'width' => '10%',
  ),
);
;
?>
