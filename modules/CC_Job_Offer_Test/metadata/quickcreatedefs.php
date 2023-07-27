<?php
$module_name = 'CC_Job_Offer_Test';
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
          1 => 
          array (
            'name' => 'cc_job_offer_test_cc_job_offer_name',
            'label' => 'LBL_CC_JOB_OFFER_TEST_CC_JOB_OFFER_FROM_CC_JOB_OFFER_TITLE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'test_type',
            'studio' => 'visible',
            'label' => 'LBL_TEST_TYPE',
          ),
          1 => 
          array (
            'name' => 'average_needed',
            'label' => 'LBL_AVERAGE_NEEDED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'test_url',
            'label' => 'LBL_TEST_URL',
          ),
        ),
      ),
    ),
  ),
);
;
?>
