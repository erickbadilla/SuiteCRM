<?php
$module_name = 'CC_Questions';
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
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
        ),
        1 => 
        array (
          0 => '',
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'answer_options',
            'studio' => 'visible',
            'label' => 'LBL_ANSWER_OPTIONS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'category',
            'studio' => 'visible',
            'label' => 'LBL_CATEGORY',
          ),
          1 => 
          array (
            'name' => 'code_type',
            'studio' => 'visible',
            'label' => 'LBL_CODE_TYPE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'question_order',
            'label' => 'LBL_QUESTION_ORDER',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 'description',
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'cc_skill_cc_questions_name',
            'label' => 'LBL_CC_SKILL_CC_QUESTIONS_FROM_CC_SKILL_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
