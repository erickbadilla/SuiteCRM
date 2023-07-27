<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'CC_Profile';
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
            'widget_class' => 'SubPanelEditCC_Job_OfferCC_ProfileButton',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'cc_profile_cc_job_offer_fields' => array(
            'usage' => 'query_only',
        ),
        'cc_profile_cc_job_offer_id'=>array(
            'usage' => 'query_only',
        ),
        'cc_profile_cc_job_offer_dependency'=>array(
            'name'=>'cc_profile_cc_job_offer_dependency',
            'vname' => 'LBL_CC_JOB_OFFER_CC_PROFILE_DEPENDENCY',
            'width' => '10%',
            'sortable'=>false,
        ),
    ),
);
