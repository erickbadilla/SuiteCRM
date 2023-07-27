<?php
$module_name = 'CC_Questions';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'CATEGORY' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CATEGORY',
    'width' => '20%',
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'QUESTION_ORDER' => 
  array (
    'type' => 'int',
    'label' => 'LBL_QUESTION_ORDER',
    'width' => '10%',
    'default' => true,
  ),
  'CC_SKILL_CC_QUESTIONS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_SKILL_CC_QUESTIONS_FROM_CC_SKILL_TITLE',
    'id' => 'CC_SKILL_CC_QUESTIONSCC_SKILL_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'CODE_TYPE' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_CODE_TYPE',
    'id' => 'CC_TQ_CODE_TYPE_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
