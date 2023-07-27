<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


require_once('modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php');


global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new CC_Job_OfferCC_CandidateRelationship();

if (isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}

// Prepopulate either side of the relationship if passed in.
safe_map('cc_candidate_name', $focus);
safe_map('CC_Candidate_id', $focus);
safe_map('CC_Job_Offer_name', $focus);
safe_map('CC_Job_Offer_id', $focus);

$GLOBALS['log']->info("Contact opportunity relationship");

$json = getJSONobj();


$xtpl = new Sugar_Smarty();
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");
$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("JOB_OFFER", $Job_OfferName = array("NAME" => $focus->cc_job_offer_name));
$xtpl->assign("CANDIDATE", $candidateName = array("NAME" => $focus->cc_candidate_name));

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$Job_OfferName['NAME']. " - ".$candidateName['NAME']), true);

$xtpl->assign("APPLICANT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['applicant_type_list'], $focus->type));
$xtpl->assign("APPLICANT_STAGE_LIST", get_select_options_with_id($app_list_strings['applicant_stage_list'], $focus->stage));

$fields = array(
    "type" => array("default_value" => '', "value" =>$focus->type, "name" => "type"),
    "stage" => array("default_value" => '', "value" =>$focus->stage, "name" => "stage"),
    "general_rating" => array("default_value" => '', "value" =>$focus->general_rating, "name" => "general_rating"),
    "skill_rating" => array("default_value" => '', "value" =>$focus->skill_rating, "name" => "skill_rating"),
    "qualification_rating" => array("default_value" => '', "value" =>$focus->qualification_rating, "name" => "qualification_rating"),
);

$xtpl->assign("fields", $fields);

$page = $xtpl->fetch('modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationshipEdit.tpl');
echo $page;
