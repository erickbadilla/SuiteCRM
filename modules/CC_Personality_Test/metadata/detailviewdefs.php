<?php
$module_name = 'CC_Personality_Test';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
            'name' => 'modified_by_name',
            'label' => 'LBL_MODIFIED_NAME',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'modify_by_user',
            'label' => 'LBL_MODIFY_BY_USER',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'cc_employee_information_cc_personality_test_name',
            'label' => 'LBL_CC_EMPLOYEE_INFORMATION_CC_PERSONALITY_TEST_FROM_CC_EMPLOYEE_INFORMATION_TITLE',
          ),
          1 => '',
        ),
        4 => 
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
