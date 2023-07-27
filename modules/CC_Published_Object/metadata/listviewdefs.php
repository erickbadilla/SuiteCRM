<?php
$module_name = 'CC_Published_Object';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'RELATED_OBJECT_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RELATED_OBJECT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'CC_PUBLISHED_OBJECT_CC_JOB_OFFER_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_PUBLISHED_OBJECT_CC_JOB_OFFER_FROM_CC_JOB_OFFER_TITLE',
    'id' => 'CC_PUBLISHED_OBJECT_CC_JOB_OFFERCC_JOB_OFFER_IDA',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
