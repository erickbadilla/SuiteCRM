<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'CC_Candidate';
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
            'widget_class' => 'SubPanelEditCC_SkillCC_CandidateButton',
            'cc_candidate_cc_skill_id'=>'cc_candidate_cc_skill_id',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'cc_candidate_cc_skill_fields' => array(
            'usage' => 'query_only',
        ),
        'cc_candidate_cc_skill_id'=>array(
            'usage' => 'query_only',
        ),
        'cc_candidate_cc_skill_rating'=>array(
            'name'=>'cc_candidate_cc_skill_rating',
            'vname' => 'LBL_CANDIDATE_SKILL_RATING',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_candidate_cc_skill_years'=>array(
            'name'=>'cc_candidate_cc_skill_years',
            'vname' => 'LBL_CANDIDATE_SKILL_YEARS',
            'width' => '10%',
            'sortable'=>false,
        ),
    ),
);
