<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Job_Offer/controller.php';

$res = (object)['results' => []];

if (key_exists("action", $_POST) && $_POST["action"] == 'search') {
    if (key_exists("searchTerm", $_POST) && count($_POST["searchTerm"]) > 0) {
        $results = (new CC_Job_OfferController())->getJobOffersByName($_POST['searchTerm']);
        $res = (object)['results' => $results];
    }
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getJobOfferById') {   
    $resultById = (new CC_Job_OfferController())->getJobOfferRecordById($_POST['elementId']);
    $resultByName = (new CC_Job_OfferController())->getJobOffersByName($resultById["Name"]);
    $res = (object)['results' => $resultByName];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetJobApplicationsOffer') {
    if (!empty($_POST['JobApplicationId'])) {
        $results = (new CC_Job_OfferController())->getJobApplicationsOffer($_POST['JobApplicationId']);
        $res = $results;
    }
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getAccount') {
    $results = (new CC_Job_OfferController())->getAccount($_POST['searchTerm'],$_POST['id']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getRecruitment') {
    $results = (new CC_Job_OfferController())->getRecruitment($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetJobOffer') {
    global $app_list_strings;
    $results = (new CC_Job_OfferController())->GetJobOffer($_POST['JobApplicationId'],$_POST['IdAccount']);
    $resultJD = (new CC_Job_OfferController())->GetJobOfferPosition($_POST['JobApplicationId']);
    $res = (object)[
                    'results' => $results, 
                    'resultJD' => $resultJD, 
                    'contract_type_list' => $app_list_strings['contract_type_list'],
                    'assigned_location_list' => $app_list_strings['assigned_location_list']
           ];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'EditJobOffer') {
    $results = (new CC_Job_OfferController())->EditJobOffer($_POST['JobApplicationId'],$_POST['position_name'],$_POST['expire_on'],$_POST['assigned_location'],$_POST['contact_type'],$_POST['account'],$_POST['description'],$_POST['position'],$_POST['old_position'] , $_FILES);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'DeleteJobOffer') {
    $results = (new CC_Job_OfferController())->DeleteJobOffer($_POST['JobApplicationId']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfileQualifications') {
    $results = (new CC_Job_OfferController())->GetProfileQualifications($_POST['JobApplicationId']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfileSkills') {
    $results = (new CC_Job_OfferController())->GetProfileSkills($_POST['JobApplicationId']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfileJobOffer') {
    $results = (new CC_Job_OfferController())->GetProfileJobOffer($_POST['JobApplicationId']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getProfile') {
    $results = (new CC_Job_OfferController())->getProfile($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'addProfile') {
    $results = (new CC_Job_OfferController())->addProfile($_POST['JobApplicationId'],$_POST['id_profile']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'deleteProfile') {
    $results = (new CC_Job_OfferController())->deleteProfile($_POST['JobApplicationId'],$_POST['id_profile']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'updateDependecyProfileJobOffer') {
    $results = (new CC_Job_OfferController())->updateDependecyProfileJobOffer($_POST['JobApplicationId'],$_POST['id_profile'],$_POST['dependecy']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetCandidateRatingJobOffer') {
    $results = (new CC_Job_OfferController())->GetCandidateRatingJobOffer($_POST['JobApplicationId']);
    $res = $results;
}


if (key_exists("action", $_POST) && $_POST["action"] == 'GetApplicationsRatingobOffer') {
    $results = (new CC_Job_OfferController())->GetApplicationsRatingobOffer($_POST['JobApplicationId']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetEmployeeRatingJobOffer') {
    $results = (new CC_Job_OfferController())->GetEmployeeRatingJobOffer($_POST['profileAllJobOffer']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'UpdateStateJobOffer') {


    if($_POST['IsPublished'] == 1){
        $results = (new CC_Job_OfferController())->action_unpublish();
    }else if($_POST['IsPublished'] == 0){
        $results = (new CC_Job_OfferController())->action_publish();
    }

    return $results;
}


if (key_exists("action", $_POST) && $_POST["action"] == 'getListsJobOffer') {
    global $app_list_strings;
    $res = (object)[
      'contract_type_list' => $app_list_strings['contract_type_list'],
      'assigned_location_list' => $app_list_strings['assigned_location_list']
    ];
}


if (key_exists("action", $_POST) && $_POST["action"] == 'CreateJobOffer') {

    $results = (new CC_Job_OfferController())->createJobOffer($_POST, $_FILES);
    $res = (object)['results' => $results];
}



  /******************** interviewer-role *********************/
  if (key_exists("action", $_POST) && $_POST["action"] == 'GetInterviewersJobOffer') {
    $results = (new CC_Job_OfferController())->GetInterviewersJobOffer($_POST['JobApplicationId']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getInterviewer') {
    $results = (new CC_Job_OfferController())->getInterviewer($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getEmployee') {
    $results = (new CC_Job_OfferController())->getInterviewer($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'addInterviewer') {
    $results = (new CC_Job_OfferController())->addInterviewer($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'deleteInterviewer') {
    $results = (new CC_Job_OfferController())->deleteInterviewer($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'updateRoleInterviewerJobOffer') {
    $results = (new CC_Job_OfferController())->updateRoleInterviewerJobOffer($_POST);
    $res = (object)['results' => $results];
}


  /******************** Related employee -role ********************/
  if (key_exists("action", $_POST) && $_POST["action"] == 'GetRelatedEmployees') {
    $results = (new CC_Job_OfferController())->GetRelatedEmployees($_POST['JobApplicationId']);
    $res = $results;
}


if (key_exists("action", $_POST) && $_POST["action"] == 'addRelatedEmployee') {
    $results = (new CC_Job_OfferController())->addRelatedEmployee($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'deleteRelatedEmployee') {
    $results = (new CC_Job_OfferController())->deleteRelatedEmployee($_POST['JobApplicationId'],$_POST['id_employee']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'updateRoleRelatedEmployee') {
    $results = (new CC_Job_OfferController())->updateRoleRelatedEmployee($_POST['JobApplicationId'],$_POST['id_employee'],$_POST['role']);
    $res = (object)['results' => $results];
}


  /******************** Accounts *********************/
  if (key_exists("action", $_POST) && $_POST["action"] == 'GetAccountJobOffer') {
    $results = (new CC_Job_OfferController())->GetAccountJobOffer($_POST['JobApplicationId']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'addAccount') {
    $results = (new CC_Job_OfferController())->addAccount($_POST['JobApplicationId'],$_POST['id_account']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'deleteAccount') {
    $results = (new CC_Job_OfferController())->deleteAccount($_POST);
    $res = (object)['results' => $results];
}


/************************** Profiles ********************************/

if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfilesJobOffer') {
    $results = (new CC_Job_OfferController())->GetProfilesJobOffer($_POST['JobApplicationId']);
    $res = $results;
}



echo json_encode($res);
