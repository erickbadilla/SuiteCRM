<?php
$module_name = 'CC_Interviews';
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
          1 => 
          array (
            'name' => 'approved',
            'studio' => 'visible',
            'label' => 'LBL_APPROVED',
          ),
        ),
        1 => 
        array (
          0 => 'description',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'observation',
            'studio' => 'visible',
            'label' => 'LBL_OBSERVATION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'other_position',
            'studio' => 'visible',
            'label' => 'LBL_OTHER_POSITION',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'interview_date',
            'label' => 'LBL_INTERVIEW_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'result',
            'label' => 'LBL_RESULT',
          ),
          1 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'positive_aspects',
            'studio' => 'visible',
            'label' => 'LBL_POSITIVE_ASPECTS',
          ),
          1 => 
          array (
            'name' => 'english_level',
            'label' => 'LBL_ENGLISH_LEVEL',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'what_to_improve',
            'studio' => 'visible',
            'label' => 'LBL_WHAT_TO_IMPROVE',
          ),
          1 => 
          array (
            'name' => 'recommended',
            'studio' => 'visible',
            'label' => 'LBL_RECOMMENDED',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'cc_interviews_cc_candidate_name',
          ),
          1 => 
          array (
            'name' => 'cc_interviews_cc_employee_information_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
