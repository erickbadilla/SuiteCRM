<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'CC_Employee_Information';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),

    'where' => '',

    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '45%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditCC_SkillCC_Employee_InformationButton',
            'cc_employee_information_cc_skill_id'=>'cc_employee_information_cc_skill_id',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'cc_employee_information_cc_skill_amount'=>array(
            'name'=>'cc_employee_information_cc_skill_amount',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_SKILL_AMOUNT',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_cc_skill_rating'=>array(
            'name'=>'cc_employee_information_cc_skill_rating',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_SKILL_RATING',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_cc_skill_years'=>array(
            'name'=>'cc_employee_information_cc_skill_years',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_SKILL_YEARS',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_cc_skill_fields' => array(
            'usage' => 'query_only',
        ),
        'cc_employee_information_cc_skill_id'=>array(
            'usage' => 'query_only',
        ),
        'cc_employee_information_cc_skill_date_modified'=>array(
            'name'=>'cc_employee_information_cc_skill_date_modified',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_SKILL_DATE_MODIFIED',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_cc_skill_modified_user_id'=>array(
            'name'=>'cc_employee_information_cc_skill_modified_user_id',
            'vname' => 'LBL_USER_MODIFIED',
            'width' => '10%',
            'sortable'=>false,
        ),
    ),
);
