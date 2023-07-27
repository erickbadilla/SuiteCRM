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
            'widget_class' => 'SubPanelEditCC_QualificationCC_Employee_InformationButton',
            'cc_employee_information_cc_qualification_id'=>'cc_employee_information_cc_qualification_id',
            'module' => $module_name,
            'width' => '4%',
        ),

        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'cc_employee_information_cc_qualification_fields' => array(
            'usage' => 'query_only',
        ),
        'cc_employee_information_cc_qualification_id'=>array(
            'usage' => 'query_only',
        ),
        'cc_employee_information_cc_qualification_actual_qualification'=>array(
            'name'=>'cc_employee_information_cc_qualification_actual_qualification',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_CC_QUALIFICATION_ACTUAL_QUALIFICATION',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_employee_information_cc_qualification_has_digital_support'=>array(
            'name'=>'cc_employee_information_cc_qualification_has_digital_support',
            'vname' => 'LBL_EMPLOYEE_INFORMATION_CC_QUALIFICATION_HAS_DIGITAL_SUPPORT',
            'width' => '5%',
            'sortable'=>false,
        ),
        'date_modified' => array(
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '15%',
        ),
    ),
);