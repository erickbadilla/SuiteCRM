<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point'); 
}
require_once('modules/CC_Job_Offer/CC_Job_OfferCC_ProfileRelationship.php');


global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new CC_Job_OfferCC_ProfileRelationship();



if (isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}

// Prepopulate either side of the relationship if passed in.
safe_map('cc_job_offer_name', $focus);
safe_map('cc_job_offer_id', $focus);
safe_map('cc_profile_name', $focus);
safe_map('cc_profile_id', $focus);

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
$xtpl->assign("PROFILE", $profileName = array("NAME" => $focus->cc_profile_name));
$xtpl->assign("JOB_OFFER", $jobofferName = array("NAME" => $focus->cc_job_offer_name));

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$profileName["NAME"]. " - ".$jobofferName["NAME"]), true);

$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['dependency_list'], $focus->dependency));

$fields = array("dependency" => array("default_value" => $focus->dependency, "value" =>$focus->dependency, "name" => "dependency"));
$xtpl->assign("fields", $fields);

$page = $xtpl->fetch('modules/CC_Job_Offer/CC_Job_OfferCC_ProfileRelationshipEdit.tpl');

echo $page;
