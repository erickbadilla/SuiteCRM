<?php
$module_name = 'CC_Job_Offer_Test';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'TEST_TYPE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TEST_TYPE',
    'width' => '10%',
  ),
  'TEST_URL' => 
  array (
    'type' => 'url',
    'label' => 'LBL_TEST_URL',
    'width' => '10%',
    'default' => true,
  ),
  'AVERAGE_NEEDED' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_AVERAGE_NEEDED',
    'width' => '10%',
    'default' => true,
  ),
  'CC_JOB_OFFER_TEST_CC_JOB_OFFER_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_JOB_OFFER_TEST_CC_JOB_OFFER_FROM_CC_JOB_OFFER_TITLE',
    'id' => 'CC_JOB_OFFER_TEST_CC_JOB_OFFERCC_JOB_OFFER_IDA',
    'width' => '15%',
    'default' => true,
  ),
);
;
?>
