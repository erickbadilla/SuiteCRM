<?php
$module_name = 'CC_Job_Application_Interviewer';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'INTERVIEW_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_INTERVIEW_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'INTERVIEW_RESULT' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_INTERVIEW_RESULT',
    'width' => '10%',
    'default' => true,
  ),
  'CC_JOB_APPLICATION_INTERVIEWER_CC_INTERVIEWER_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_JOB_APPLICATION_INTERVIEWER_CC_INTERVIEWER_FROM_CC_INTERVIEWER_TITLE',
    'id' => 'CC_JOB_APPLICATION_INTERVIEWER_CC_INTERVIEWERCC_INTERVIEWER_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'CC_JOB_APPLICATION_INTERVIEWER_CC_JOB_APPLICATION_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_JOB_APPLICATION_INTERVIEWER_CC_JOB_APPLICATION_FROM_CC_JOB_APPLICATION_TITLE',
    'id' => 'CC_JOB_APP07EBICATION_IDA',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
