<?php
$dashletData['CT_daily_status_reportDashlet']['searchFields'] = array (
  'date_entered' => 
  array (
    'default' => '',
  ),
  'date_modified' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'type' => 'assigned_user_name',
    'default' => 'Administrator',
  ),
);
$dashletData['CT_daily_status_reportDashlet']['columns'] = array (
  'date_reported' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_REPORTED',
    'width' => '10%',
    'default' => true,
    'name' => 'date_reported',
  ),
  'employee' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_EMPLOYEE',
    'id' => 'CC_EMPLOYEE_INFORMATION_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
    'name' => 'employee',
  ),
  'project' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PROJECT',
    'id' => 'PROJECT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
    'name' => 'project',
  ),
  'schedule' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
    'name' => 'schedule',
  ),
  'mood' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_MOOD',
    'width' => '10%',
    'name' => 'mood',
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
);
