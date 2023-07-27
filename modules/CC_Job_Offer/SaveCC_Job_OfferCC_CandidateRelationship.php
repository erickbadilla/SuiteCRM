<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php');


$focus = new CC_Job_OfferCC_CandidateRelationship();

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

$tableName = 'cc_candidate_cc_job_offer_c_audit';

jobApplicationAuditTable($tableName);

$focus->save();

if($_REQUEST["previousStage"] != $focus->stage){
    saveJobApplicationRecords($tableName, $focus);
}

$recordID = $focus->id;

$GLOBALS['log']->debug("Saved record with id of ".$recordID);

$header_URL = "Location: index.php?action={$_REQUEST['return_action']}&module={$_REQUEST['return_module']}&record={$_REQUEST['return_id']}";
$GLOBALS['log']->debug("about to post header URL of: $header_URL");

header($header_URL);

/**
 * Creates the audit table if not exist
 * @param string $tableName
 */
function jobApplicationAuditTable(string $tableName) 
{
    $existsql = "SHOW TABLES LIKE '".$tableName."';";
    // Get an instance of the dabatabase manager
    $db = DBManagerFactory::getInstance();
    $exist = $db->query($existsql);
    if($exist->num_rows == 0) {
        $createsql = "CREATE TABLE ".$tableName."(
            id char(36),
            job_application_id char(36),
            job_offer_id char(36),
            candidate_id char(36),
            date_created datetime,
            created_by varchar(36), 
            field_name varchar(100), 
            before_value_string varchar(255),
            after_value_string varchar(255)
        );";
        $db->query($createsql);
    }
}

/**
 * Save the audit record
 * @param string $tableName
 * @param object $focus
 */
function saveJobApplicationRecords(string $tableName, object $focus) 
{
    global $current_user;

    $sql = "INSERT INTO " . $tableName;
    
    $values = array();
    $values['id'] = "'".create_guid()."'";
    $values['job_application_id'] = "'".$focus->id."'";
    $values['job_offer_id'] = "'".$focus->cc_candidate_cc_job_offercc_job_offer_idb."'";
    $values['candidate_id'] = "'".$focus->cc_candidate_cc_job_offercc_candidate_ida."'";
    $values['date_created'] = "'".gmdate('Y-m-d h:i:s')."'";
    $values['created_by'] = "'".$current_user->id."'";
    $values['field_name'] = "'stage'";
    $values['before_value_string'] = "'".$_REQUEST["previousStage"]."'";
    $values['after_value_string'] = "'".$focus->stage."'";

    $sql .= "(" . implode(",", array_keys($values)) . ") ";
    $sql .= "VALUES(" . implode(",", $values) . ")";

    $db = DBManagerFactory::getInstance();
    $db->query($sql);

}

