<?php
$dashletData['CC_QualificationDashlet']['searchFields'] = array (
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
$dashletData['CC_QualificationDashlet']['columns'] = array (
  'favourite' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_FAVOURITE',
    'width' => '10%',
    'name' => 'favourite',
  ),
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
    'name' => 'date_entered',
  ),
  'digital_support_required' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_DIGITAL_SUPPORT_REQUIRED',
    'width' => '10%',
    'name' => 'digital_support_required',
  ),
  'mininum_requiered' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MININUM_REQUIERED',
    'width' => '10%',
    'default' => false,
    'name' => 'mininum_requiered',
  ),
  'cc_qualification_cc_qualification_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_QUALIFICATION_CC_QUALIFICATION_FROM_CC_QUALIFICATION_L_TITLE',
    'id' => 'CC_QUALIFICATION_CC_QUALIFICATIONCC_QUALIFICATION_IDA',
    'width' => '10%',
    'default' => false,
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
