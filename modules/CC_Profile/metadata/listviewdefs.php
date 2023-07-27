<?php
$module_name = 'CC_Profile';
$listViewDefs [$module_name] = 
array (
  'SYSTEMONLY' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_SYSTEMONLY',
    'width' => '10%',
  ),
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
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
