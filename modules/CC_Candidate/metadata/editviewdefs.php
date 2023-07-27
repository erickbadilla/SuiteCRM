<?php
$module_name = 'CC_Candidate';
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
        'LBL_EDITVIEW_PANEL1' => 
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
            'name' => 'first_name',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'document_number',
            'label' => 'LBL_DOCUMENT_NUMBER',
          ),
          1 => 
          array (
            'name' => 'email',
            'label' => 'LBL_EMAIL',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'mobile',
            'label' => 'LBL_MOBILE',
          ),
          1 => 
          array (
            'name' => 'phone',
            'label' => 'LBL_PHONE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'street_address_1',
            'label' => 'LBL_STREET_ADDRESS_1',
          ),
          1 => 
          array (
            'name' => 'street_address_2',
            'label' => 'LBL_STREET_ADDRESS_2',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'has_passport',
            'label' => 'LBL_HAS_PASSPORT',
          ),
          1 => 
          array (
            'name' => 'has_visa',
            'label' => 'LBL_HAS_VISA',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'country',
            'label' => 'LBL_COUNTRY',
          ),
          1 => 
          array (
            'name' => 'city',
            'label' => 'LBL_CITY',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'state_province',
            'label' => 'LBL_STATE_PROVINCE',
          ),
          1 => 
          array (
            'name' => 'zip_postal_code',
            'label' => 'LBL_ZIP_POSTAL_CODE',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'currently_employed',
            'label' => 'LBL_CURRENTLY_EMPLOYED',
          ),
          1 => 
          array (
            'name' => 'current_employer',
            'label' => 'LBL_CURRENT_EMPLOYER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'education',
            'studio' => 'visible',
            'label' => 'LBL_EDUCATION',
          ),
          1 => 
          array (
            'name' => 'years_of_experience',
            'label' => 'LBL_YEARS_OF_EXPERIENCE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
