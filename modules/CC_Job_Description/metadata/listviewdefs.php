<?php
$module_name = 'CC_Job_Description';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'RELATE_ROLE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_RELATE_ROLE',
    'width' => '10%',
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '20%',
    'default' => true,
  ),
  'OBJECTIVES' => 
  array (
    'type' => 'wysiwyg',
    'label' => 'LBL_OBJECTIVES',
    'width' => '60%',
    'default' => false,
  ),
  'RESPONSIBILITIES' => 
  array (
    'type' => 'wysiwyg',
    'label' => 'LBL_RESPONSIBILITIES',
    'width' => '10%',
    'default' => false,
  ),
  'RELATIONSHIPS' => 
  array (
    'type' => 'wysiwyg',
    'label' => 'LBL_RELATIONSHIPS',
    'width' => '10%',
    'default' => false,
  ),
  'REQUIREMENTS' => 
  array (
    'type' => 'wysiwyg',
    'label' => 'LBL_REQUIREMENTS',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
