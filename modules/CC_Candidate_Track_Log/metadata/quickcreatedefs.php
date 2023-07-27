<?php
$module_name = 'CC_Candidate_Track_Log';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'log_type',
            'studio' => 'visible',
            'label' => 'LBL_LOG_TYPE',
          ),
          1 => 
          array (
            'name' => 'log_date',
            'label' => 'LBL_LOG_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'cc_candidate_track_log_cc_candidate_name',
            'label' => 'LBL_CC_CANDIDATE_TRACK_LOG_CC_CANDIDATE_FROM_CC_CANDIDATE_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
