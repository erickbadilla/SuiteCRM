<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once "modules/CC_Job_Applications/JobApplicationPDFFactory.php";
global $current_user;

$default = array("options" => array(
    "default" => null
));

$applicationId = filter_input(INPUT_GET, 'applicationId', FILTER_DEFAULT, $default);
$templateId = "db36eb2f-b844-4d29-6bca-614899b9f405";

$_REQUEST["uid"] = true;

$aPdf = new JobApplicationPDFFactory($applicationId,$templateId);
