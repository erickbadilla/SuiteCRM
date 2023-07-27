<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


require_once('modules/CC_Skill/CC_SkillCC_Employee_InformationRelationship.php');


global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new CC_SkillCC_Employee_InformationRelationship();

if (isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}

// Prepopulate either side of the relationship if passed in.
safe_map('cc_employee_information_name', $focus);
safe_map('CC_Employee_Information_id', $focus);
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
$xtpl->assign("EMPLOYEE", $employeeName = array("NAME" => $focus->cc_employee_information_name));
$xtpl->assign("USER", $focus->modified_user_id);

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$skillName['NAME']. " - ".$employeeName['NAME']), true);

$fields = array(
    "amount" => array("default_value" => 0, "value" =>$focus->amount, "name" => "amount"),
    "rating" => array("default_value" => 0, "value" =>$focus->rating, "name" => "rating"),
    "years" => array("default_value" => 0, "value" =>$focus->years, "name" => "years")
);
$xtpl->assign("fields", $fields);

$page = $xtpl->fetch('modules/CC_Skill/CC_SkillCC_Employee_InformationRelationshipEdit.tpl');
echo $page;
