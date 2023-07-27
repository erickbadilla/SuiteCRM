<?php
$module_name = 'CC_Personality_Test';
$listViewDefs [$module_name] = 
array (
  'PATTERN' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PATTERN',
    'width' => '10%',
    'default' => true,
  ),
  'SCORE_INDEX' => 
  array (
    'type' => 'int',
    'label' => 'LBL_SCORE_INDEX',
    'width' => '10%',
    'default' => true,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
  'MODIFY_BY_USER' => 
  array (
    'type' => 'ModifiedByUser',
    'label' => 'LBL_MODIFY_BY_USER',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
