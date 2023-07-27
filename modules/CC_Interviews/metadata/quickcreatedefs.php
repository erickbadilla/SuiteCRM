<?php
$module_name = 'CC_Interviews';
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
            'name' => 'approved',
            'studio' => 'visible',
            'label' => 'LBL_APPROVED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'interview_date',
            'label' => 'LBL_INTERVIEW_DATE',
          ),
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
      ),
    ),
  ),
);
;
?>
