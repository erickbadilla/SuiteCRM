<?php
$module_name = 'CC_Training_Certifications';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'ISSUING_ORGANIZATION' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ISSUING_ORGANIZATION',
    'width' => '10%',
    'default' => true,
  ),
  'TRAINING_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TRAINING_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'LEVEL_REACHED' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LEVEL_REACHED',
    'width' => '10%',
    'default' => true,
  ),
  'EXPIRES_ON_MONTH' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_EXPIRES_ON_MONTH',
    'width' => '10%',
    'default' => true,
  ),
  'EXPIRES_ON_YEAR' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EXPIRES_ON_YEAR',
    'width' => '10%',
    'default' => true,
  ),
  'NEVER_EXPIRE' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_NEVER_EXPIRE',
    'width' => '10%',
  ),
  'START_MONTH' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_START_MONTH',
    'width' => '10%',
    'default' => false,
  ),
  'START_YEAR' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_START_YEAR',
    'width' => '10%',
  ),
  'END_MONTH' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_END_MONTH',
    'width' => '10%',
    'default' => false,
  ),
  'END_YEAR' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_END_YEAR',
    'width' => '10%',
  ),
  'CERTIFICATION_ID' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_CERTIFICATION_ID',
    'width' => '10%',
  ),
  'CERT_URL' => 
  array (
    'type' => 'url',
    'label' => 'LBL_CERT_URL',
    'width' => '10%',
    'default' => false,
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
