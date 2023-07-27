<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


require_once('modules/CC_Qualification/CC_QualificationCC_Employee_InformationRelationship.php');


global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new CC_QualificationCC_Employee_InformationRelationship();

if (isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}

// Pre-populate either side of the relationship if passed in.
safe_map('cc_employee_information_name', $focus);
safe_map('CC_Employee_Information_id', $focus);
safe_map('CC_Qualification_name', $focus);
safe_map('CC_Qualification_id', $focus);

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
$xtpl->assign("QUALIFICATION", $qualificationName = array("NAME" => $focus->CC_Qualification_name, "ACTUAL_QUALIFICATION" => $focus->actual_qualification, "HAS_DIGITAL_SUPPORT" => $focus->has_digital_support));
$xtpl->assign("EMPLOYEE", $employeeName = array("NAME" => $focus->CC_Employee_Information_name));
$xtpl->assign("CHECKED", ($focus->has_digital_support)? 'checked="checked"' : '' );
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$employeeName['NAME']. " » ".$qualificationName['NAME']), true);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['actual_qualification_list'], $focus->actual_qualification));
$page = $xtpl->fetch('modules/CC_Qualification/CC_QualificationCC_Employee_InformationRelationshipEdit.tpl');
echo $page;

//$xtpl->display("main");