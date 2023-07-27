<?php
$module_name = 'CC_Teams';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'TEAMMATE' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_TEAMMATE',
    'id' => 'CC_EMPLOYEE_INFORMATION_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'LEAD_PM' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_LEAD_PM',
    'id' => 'CC_EMPLOYEE_INFORMATION_ID1_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'USER_RELATED' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_USER_RELATED',
    'id' => 'USER_ID_C',
    'link' => true,
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
