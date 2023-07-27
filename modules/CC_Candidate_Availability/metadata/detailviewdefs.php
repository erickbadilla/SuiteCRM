<?php
$module_name = 'CC_Candidate_Availability';
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
            'name' => 'daypick',
            'studio' => 'visible',
            'label' => 'LBL_DAYPICK',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'time_1',
            'label' => 'LBL_TIME_1',
          ),
          1 => 
          array (
            'name' => 'time_2',
            'label' => 'LBL_TIME_2',
          ),
        ),
      ),
    ),
  ),
);
;
?>
