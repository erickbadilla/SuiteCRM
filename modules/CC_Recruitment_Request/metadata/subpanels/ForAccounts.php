<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'CC_Recruitment_Request';
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
        'date' => array(
            'vname' => 'LBL_DATE_CREATED',
            'width' => '45%',
        ),
        'assigned_user' => array(
            'vname' => 'LBL_ASSIGNED_USER',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '45%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditCC_QualificationCC_CandidateButton',
            'cc_candidate_cc_qualification_id'=>'cc_candidate_cc_qualification_id',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        )
    ),
);
