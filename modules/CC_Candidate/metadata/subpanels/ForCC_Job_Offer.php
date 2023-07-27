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
            'widget_class' => 'SubPanelEditCC_Job_OfferCC_CandidateButton',
            'cc_candidate_cc_job_offer_id'=>'cc_candidate_cc_job_offer_id',
            'module' => $module_name,
            'width' => '4%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'cc_candidate_cc_job_offer_fields' => array(
            'usage' => 'query_only',
        ),
        'cc_candidate_cc_job_offer_id'=>array(
            'usage' => 'query_only',
        ),
        'cc_candidate_cc_job_offer_type'=>array(
            'name'=>'cc_candidate_cc_job_offer_type',
            'vname' => 'LBL_CANDIDATE_JOB_OFFER_TYPE',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_candidate_cc_job_offer_stage'=>array(
            'name'=>'cc_candidate_cc_job_offer_stage',
            'vname' => 'LBL_CANDIDATE_JOB_OFFER_STAGE',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_candidate_cc_job_offer_general_rating'=>array(
            'name'=>'cc_candidate_cc_job_offer_general_rating',
            'vname' => 'LBL_CANDIDATE_JOB_OFFER_GENERAL_RATING',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_candidate_cc_job_offer_skill_rating'=>array(
            'name'=>'cc_candidate_cc_job_offer_skill_rating',
            'vname' => 'LBL_CANDIDATE_JOB_OFFER_SKILL_RATING',
            'width' => '10%',
            'sortable'=>false,
        ),
        'cc_candidate_cc_job_offer_qualification_rating'=>array(
            'name'=>'cc_candidate_cc_job_offer_qualification_rating',
            'vname' => 'LBL_CANDIDATE_JOB_OFFER_QUALIFICATION_RATING',
            'width' => '10%',
            'sortable'=>false,
        )
    ),
);
