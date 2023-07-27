<?php
$module_name = 'CC_Qualification';
$listViewDefs [$module_name] = 
array (
  'FAVOURITE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_FAVOURITE',
    'width' => '10%',
  ),
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DIGITAL_SUPPORT_REQUIRED' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_DIGITAL_SUPPORT_REQUIRED',
    'width' => '10%',
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'CC_QUALIFICATION_CC_QUALIFICATION_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_QUALIFICATION_CC_QUALIFICATION_FROM_CC_QUALIFICATION_L_TITLE',
    'id' => 'CC_QUALIFICATION_CC_QUALIFICATIONCC_QUALIFICATION_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'MININUM_REQUIERED' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MININUM_REQUIERED',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
