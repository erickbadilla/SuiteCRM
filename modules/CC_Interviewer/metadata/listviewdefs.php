<?php
$module_name = 'CC_Interviewer';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'INTERVIEWER_ROLE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_INTERVIEWER_ROLE',
    'width' => '10%',
  ),
  'LEGACY_INTERVIEWER_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_LEGACY_INTERVIEWER_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'CC_INTERVIEWER_CC_EMPLOYEE_INFORMATION_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_INTERVIEWER_CC_EMPLOYEE_INFORMATION_FROM_CC_EMPLOYEE_INFORMATION_TITLE',
    'id' => 'CC_INTERVI9533RMATION_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'CC_INTERVIEWER_CC_JOB_OFFER_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_INTERVIEWER_CC_JOB_OFFER_FROM_CC_JOB_OFFER_TITLE',
    'id' => 'CC_INTERVIEWER_CC_JOB_OFFERCC_JOB_OFFER_IDA',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
