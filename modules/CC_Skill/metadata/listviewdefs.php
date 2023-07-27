<?php
$module_name = 'CC_Skill';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'SKILL_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SKILL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'CC_SKILL_CC_SKILL_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_SKILL_CC_SKILL_FROM_CC_SKILL_L_TITLE',
    'id' => 'CC_SKILL_CC_SKILLCC_SKILL_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'PUBLISHED' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_PUBLISHED',
    'width' => '10%',
  ),
);
;
?>
