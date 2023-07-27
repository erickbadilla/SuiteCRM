<?php
$module_name = 'CT_Activity';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'ACTIVITY_PROJECT' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_ACTIVITY_PROJECT',
    'id' => 'PROJECT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'PROJECT_MODULE' => 
  array (
    'type' => 'ModuleDropdown',
    'label' => 'LBL_PROJECT_MODULE',
    'width' => '10%',
    'default' => true,
  ),
  'MODULE_WORKTYPE' => 
  array (
    'type' => 'WorktypeDropdown',
    'label' => 'LBL_MODULE_WORKTYPE',
    'width' => '10%',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVITY_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_ACTIVITY_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVITY_TIME' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_ACTIVITY_TIME',
    'width' => '10%',
    'default' => true,
  ),
  'IS_BILLABLE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_IS_BILLABLE',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
;
?>
