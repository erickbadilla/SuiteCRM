<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('modules/CC_Qualification/CC_QualificationCC_CandidateRelationship.php');
require_once('modules/CC_Profile/RatingCalculationUtility.php');

$focus = new CC_QualificationCC_CandidateRelationship();

$focus->retrieve($_REQUEST['record']);

foreach ($focus->column_fields as $field) {
    safe_map($field, $focus, true);
}

foreach ($focus->additional_column_fields as $field) {
    safe_map($field, $focus, true);
}

// send them to the edit screen.
if (isset($_REQUEST['record']) && $_REQUEST['record'] != "") {
    $recordID = $_REQUEST['record'];
}

if(!isset($_REQUEST['has_digital_support'])){
    $focus->has_digital_support = 0;
}

$focus->save();
$recordID = $focus->id;

(new RatingCalculationUtility)->calculateJobAplicationRating($focus->cc_candidate_cc_qualificationcc_candidate_ida, 'qualification');

$GLOBALS['log']->debug("Saved record with id of ".$recordID);

$header_URL = "Location: index.php?action={$_REQUEST['return_action']}&module={$_REQUEST['return_module']}&record={$_REQUEST['return_id']}";
$GLOBALS['log']->debug("about to post header URL of: $header_URL");

header($header_URL);