<?php
$popupMeta = array (
    'moduleMain' => 'CC_Job_Description',
    'varName' => 'CC_Job_Description',
    'orderBy' => 'cc_job_description.name',
    'whereClauses' => array (
  'name' => 'cc_job_description.name',
),
    'searchInputs' => array (
  0 => 'cc_job_description_number',
  1 => 'name',
  2 => 'priority',
  3 => 'status',
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'RELATE_ROLE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_RELATE_ROLE',
    'width' => '10%',
    'name' => 'relate_role',
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '20%',
    'default' => true,
    'name' => 'description',
  ),
),
);
