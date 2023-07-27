<?php
$popupMeta = array (
    'moduleMain' => 'CC_Note',
    'varName' => 'CC_Note',
    'orderBy' => 'cc_note.name',
    'whereClauses' => array (
  'flag' => 'cc_note.flag',
  'description' => 'cc_note.description',
  'modified_by_name' => 'cc_note.modified_by_name',
  'date_modified' => 'cc_note.date_modified',
),
    'searchInputs' => array (
  4 => 'flag',
  5 => 'description',
  6 => 'modified_by_name',
  7 => 'date_modified',
),
    'searchdefs' => array (
  'flag' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_FLAG',
    'width' => '10%',
    'name' => 'flag',
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'name' => 'description',
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'name' => 'date_modified',
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'name' => 'modified_by_name',
  ),
),
);
