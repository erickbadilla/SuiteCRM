<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


require_once('modules/CC_Skill/CC_SkillCC_ProfileRelationship.php');


global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new CC_SkillCC_ProfileRelationship();

if (isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}

// Prepopulate either side of the relationship if passed in.
safe_map('cc_profile_name', $focus);
safe_map('CC_Profile_id', $focus);
safe_map('CC_Skill_name', $focus);
safe_map('CC_Skill_id', $focus);

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
$xtpl->assign("SKILL", $skillName = array("NAME" => $focus->cc_skill_name, "TYPE" => $focus->type, "AMOUNT" => $focus->amount));
$xtpl->assign("PROFILE", $profileName = array("NAME" => $focus->cc_profile_name));

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$skillName['NAME']. " - ".$profileName['NAME']), true);

$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['type_list'], $focus->type));

$fields = array(
    "amount" => array("default_value" => 0, "value" =>$focus->amount, "name" => "amount", "parentfield" => 'type'),
    "rating" => array("default_value" => 0, "value" =>$focus->rating, "name" => "rating"),
    "years" => array("default_value" => 0, "value" =>$focus->years, "name" => "years")
);

$xtpl->assign("fields", $fields);

$page = $xtpl->fetch('modules/CC_Skill/CC_SkillCC_ProfileRelationshipEdit.tpl');
echo $page;
