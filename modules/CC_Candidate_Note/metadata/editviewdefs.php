<?php
$module_name = 'CC_Candidate_Note';
$viewdefs [$module_name] = 
array (
  'EditView' => 
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
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'flag',
            'studio' => 'visible',
            'label' => 'LBL_FLAG',
          ),
        ),
        1 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'cc_candidate_cc_candidate_note_name',
            'label' => 'LBL_CC_CANDIDATE_CC_CANDIDATE_NOTE_FROM_CC_CANDIDATE_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
