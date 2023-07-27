<?php
$module_name = 'CC_Candidate_Availability';
$listViewDefs [$module_name] = 
array (
  'CC_CANDIDATE_AVAILABILITY_CC_CANDIDATE_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_CANDIDATE_AVAILABILITY_CC_CANDIDATE_FROM_CC_CANDIDATE_TITLE',
    'id' => 'CC_CANDIDATE_AVAILABILITY_CC_CANDIDATECC_CANDIDATE_IDA',
    'width' => '15%',
    'default' => true,
  ),
  'DAYPICK' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_DAYPICK',
    'width' => '10%',
    'default' => true,
  ),
  'TIME_1' => 
  array (
    'type' => 'time',
    'label' => 'LBL_TIME_1',
    'width' => '10%',
    'default' => true,
  ),
  'TIME_2' => 
  array (
    'type' => 'time',
    'label' => 'LBL_TIME_2',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
