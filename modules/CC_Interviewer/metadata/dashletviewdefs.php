<?php
$dashletData['CC_InterviewerDashlet']['searchFields'] = array (
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
$dashletData['CC_InterviewerDashlet']['columns'] = array (
  'cc_interviewer_cc_employee_information_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_INTERVIEWER_CC_EMPLOYEE_INFORMATION_FROM_CC_EMPLOYEE_INFORMATION_TITLE',
    'id' => 'CC_INTERVI9533RMATION_IDA',
    'width' => '20%',
    'default' => true,
    'name' => 'cc_interviewer_cc_employee_information_name',
  ),
  'cc_interviewer_cc_job_offer_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CC_INTERVIEWER_CC_JOB_OFFER_FROM_CC_JOB_OFFER_TITLE',
    'id' => 'CC_INTERVIEWER_CC_JOB_OFFERCC_JOB_OFFER_IDA',
    'width' => '20%',
    'default' => true,
    'name' => 'cc_interviewer_cc_job_offer_name',
  ),
  'interviewer_role' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_INTERVIEWER_ROLE',
    'width' => '20%',
    'name' => 'interviewer_role',
  ),
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '20%',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
    'name' => 'date_entered',
  ),
);
