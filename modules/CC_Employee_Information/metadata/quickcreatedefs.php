<?php
$module_name = 'CC_Employee_Information';
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
        'LBL_QUICKCREATE_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_QUICKCREATE_PANEL2' => 
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
            'name' => 'identity_document',
            'label' => 'LBL_IDENTITY_DOCUMENT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'gender',
            'studio' => 'visible',
            'label' => 'LBL_GENDER',
          ),
          1 => 
          array (
            'name' => 'date_of_birth',
            'label' => 'LBL_DATE_OF_BIRTH',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'current_email',
            'label' => 'LBL_CURRENT_EMAIL',
          ),
          1 => 
          array (
            'name' => 'phone_number',
            'label' => 'LBL_PHONE_NUMBER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'home_address',
            'studio' => 'visible',
            'label' => 'LBL_HOME_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'bank_account',
            'label' => 'LBL_BANK_ACCOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'is_married',
            'label' => 'LBL_IS_MARRIED',
          ),
          1 => 
          array (
            'name' => 'children',
            'label' => 'LBL_CHILDREN',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'has_passport',
            'label' => 'LBL_HAS_PASSPORT',
          ),
          1 => 
          array (
            'name' => 'passport_expiration',
            'label' => 'LBL_PASSPORT_EXPIRATION',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'has_visa',
            'label' => 'LBL_HAS_VISA',
          ),
          1 => 
          array (
            'name' => 'visa_expiration',
            'label' => 'LBL_VISA_EXPIRATION',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'active',
            'label' => 'LBL_ACTIVE',
          ),
          1 => 
          array (
            'name' => 'is_assigned',
            'label' => 'LBL_IS_ASSIGNED',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'car_plate',
            'label' => 'LBL_CAR_PLATE',
          ),
          1 => 
          array (
            'name' => 'is_professional_service',
            'label' => 'LBL_IS_PROFESSIONAL_SERVICE',
          ),
        ),
      ),
      'lbl_quickcreate_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'position',
            'studio' => 'visible',
            'label' => 'LBL_POSITION',
          ),
          1 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'country_law',
            'label' => 'LBL_COUNTRY_LAW',
          ),
          1 => 
          array (
            'name' => 'assigned_role',
            'studio' => 'visible',
            'label' => 'LBL_ASSIGNED_ROLE',
          ),
        ),
      ),
      'lbl_quickcreate_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'emergency_contact',
            'studio' => 'visible',
            'label' => 'LBL_EMERGENCY_CONTACT',
          ),
          1 => 
          array (
            'name' => 'related_contact',
            'studio' => 'visible',
            'label' => 'LBL_RELATED_CONTACT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'timetask_person_id',
            'label' => 'LBL_TIMETASK_PERSON_ID',
          ),
          1 => 
          array (
            'name' => 'timetask_api_token',
            'label' => 'LBL_TIMETASK_API_TOKEN',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'project',
            'studio' => 'visible',
            'label' => 'LBL_PROJECT ',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
