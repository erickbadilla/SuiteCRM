<?php
$module_name = 'CC_Candidate_Track_Log';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'CC_CANDIDATE_TRACK_LOG_CC_CANDIDATE_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_CANDIDATE_TRACK_LOG_CC_CANDIDATE_FROM_CC_CANDIDATE_TITLE',
    'id' => 'CC_CANDIDATE_TRACK_LOG_CC_CANDIDATECC_CANDIDATE_IDA',
    'width' => '15%',
    'default' => true,
  ),
  'LOG_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LOG_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'LOG_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_LOG_DATE',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
