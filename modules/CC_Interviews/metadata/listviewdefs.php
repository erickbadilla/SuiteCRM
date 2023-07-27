<?php
$module_name = 'CC_Interviews';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'OBSERVATION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_OBSERVATION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'INTERVIEW_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_INTERVIEW_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'RESULT' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_RESULT',
    'width' => '10%',
    'default' => true,
  ),
  'APPROVED' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_APPROVED',
    'width' => '10%',
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'OTHER_POSITION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_OTHER_POSITION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'RECOMMENDED' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_RECOMMENDED',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'POSITIVE_ASPECTS' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_POSITIVE_ASPECTS',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'WHAT_TO_IMPROVE' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_WHAT_TO_IMPROVE',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'ENGLISH_LEVEL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ENGLISH_LEVEL',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
