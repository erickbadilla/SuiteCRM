<?php
$module_name = 'CC_Employee_Information';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/CC_Employee_Information/js/custom.js',
        ),
        1 => 
        array (
          'file' => 'custom/include/generic/javascript/auditChanges/auditChanges.js',
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
          4 => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup(\'pdf\');" value="{$MOD.LBL_PRINT_AS_PDF}">',
          ),
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
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'collapsed',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL4' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'collapsed',
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
            'name' => 'bank_account',
            'label' => 'LBL_BANK_ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'is_professional_service',
            'label' => 'LBL_IS_PROFESSIONAL_SERVICE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'remind_anniversary',
            'label' => 'LBL_REMIND_ANNIVERSARY',
          ),
          1 => 
          array (
            'name' => 'remind_birthday',
            'label' => 'LBL_REMIND_BIRTHDAY',
          ),
        ),
        5 => 
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
        6 => 
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
        7 => 
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
        8 => 
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
        9 => 
        array (
          0 => 
          array (
            'name' => 'car_plate',
            'label' => 'LBL_CAR_PLATE',
          ),
          1 => 
          array (
            'name' => 'project',
            'studio' => 'visible',
            'label' => 'LBL_PROJECT ',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'education',
            'studio' => 'visible',
            'label' => 'LBL_EDUCATION',
          ),
          1 => 
          array (
            'name' => 'english_level',
            'studio' => 'visible',
            'label' => 'LBL_ENGLISH_LEVEL',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'personal_picture',
            'studio' => 'visible',
            'label' => 'LBL_PERSONAL_PICTURE',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'home_address',
            'studio' => 'visible',
            'label' => 'LBL_HOME_ADDRESS',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'territory',
            'label' => 'LBL_TERRITORY',
          ),
          1 => 
          array (
            'name' => 'state',
            'label' => 'LBL_STATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'city',
            'label' => 'LBL_CITY',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel1' => 
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
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
          ),
          1 => 
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
        ),
        2 => 
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
        3 => 
        array (
          0 => 
          array (
            'name' => 'end_date_reason',
            'studio' => 'visible',
            'label' => 'LBL_END_DATE_REASON',
          ),
        ),
      ),
      'lbl_editview_panel4' => 
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
            'name' => 'emergency_contact_relation',
            'label' => 'LBL_EMERGENCY_CONTACT_RELATION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'related_contact',
            'studio' => 'visible',
            'label' => 'LBL_RELATED_CONTACT',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
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
        1 => 
        array (
          0 => 
          array (
            'name' => 'blood_type',
            'label' => 'LBL_BLOOD_TYPE',
          ),
          1 => 
          array (
            'name' => 'tshirt_size',
            'label' => 'LBL_TSHIRT_SIZE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
