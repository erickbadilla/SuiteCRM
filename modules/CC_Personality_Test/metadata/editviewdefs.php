<?php
$module_name = 'CC_Personality_Test';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'javascript' => '{sugar_getscript file="custom/modules/CC_Personality_Test/js/customPersonalityTestValidation.js"}',
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
          0 => 
          array (
            'name' => 'pattern',
            'studio' => 'visible',
            'label' => 'LBL_PATTERN',
          ),
          1 => 
          array (
            'name' => 'score_index',
            'label' => 'LBL_SCORE_INDEX',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'cc_employee_information_cc_personality_test_name',
            'label' => 'LBL_CC_EMPLOYEE_INFORMATION_CC_PERSONALITY_TEST_FROM_CC_EMPLOYEE_INFORMATION_TITLE',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'cc_candidate_cc_personality_test_name',
            'label' => 'LBL_CC_CANDIDATE_CC_PERSONALITY_TEST_FROM_CC_CANDIDATE_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
