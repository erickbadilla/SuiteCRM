<?php
$module_name = 'CC_Profile';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'includes' => array(
            0 => array(
                'file' => 'custom/include/SugarFields/Fields/SkillRatingExperience/js/rating.js',
            ),
            1 => array(
                'file' => 'custom/include/generic/javascript/chart/chart.js',
            ),
            2 => array(
                'file' => 'custom/include/generic/javascript/matcher/matcher.js',
            ),
            3 => array(
                'file' => 'custom/include/generic/javascript/datatables/jquery.dataTables.min.js',
            ),
            4 => array(
                'file' => 'custom/include/generic/javascript/select2/select2.min.js',
            ),
      ),
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
            'name' => 'systemonly',
            'label' => 'LBL_SYSTEMONLY',
          ),
        ),
      ),
    ),
  ),
);
;
?>
