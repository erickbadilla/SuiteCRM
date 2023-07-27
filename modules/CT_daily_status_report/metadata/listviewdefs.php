<?php
$module_name = 'CT_daily_status_report';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DATE_REPORTED' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_REPORTED',
    'width' => '10%',
    'default' => true,
  ),
  'PROJECT' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PROJECT',
    'id' => 'PROJECT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'EMPLOYEE' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_EMPLOYEE',
    'id' => 'CC_EMPLOYEE_INFORMATION_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'SCHEDULE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
  ),
  'MOOD' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_MOOD',
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
);
;
?>
