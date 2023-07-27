<?php
$module_name = 'CC_Job_Applications';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'CC_JOB_APPLICATIONS_CC_APPLICATION_STAGE_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_JOB_APPLICATIONS_CC_APPLICATION_STAGE_FROM_CC_APPLICATION_STAGE_TITLE',
    'id' => 'CC_JOB_APPLICATIONS_CC_APPLICATION_STAGECC_APPLICATION_STAGE_IDB',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
