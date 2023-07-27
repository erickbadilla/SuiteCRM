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
        'date_modified' => array(
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '45%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditProjectCC_Employee_InformationButton',
            'cc_employee_information_project_id'=>'cc_employee_information_project_id',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'cc_employee_information_project_fields' => array(
            'usage' => 'query_only',
        ),
        'cc_employee_information_project_id'=>array(
            'usage' => 'query_only',
        ),
        'cc_employee_information_project_start_date'=>array(
            'name'=>'cc_employee_information_project_start_date',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_PROJECT_START_DATE',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_project_end_date'=>array(
            'name'=>'cc_employee_information_project_end_date',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_PROJECT_END_DATE',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_project_description'=>array(
            'name'=>'cc_employee_information_project_description',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_PROJECT_DESCRIPTION',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_project_role'=>array(
            'name'=>'cc_employee_information_project_role',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_PROJECT_ROLE',
            'width' => '10%',
            'sortable'=>false,
        ),
    ),
);