<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Recruitment_Request/controller.php';

$res = (object)['results' => []];


if (key_exists("action", $_POST) && $_POST["action"] == 'getJobDescription') {
    $results = (new CC_Recruitment_RequestController())->getJobDescription($_POST['searchTerm']);
    $res = (object)['results' => $results];
}


if (key_exists("action", $_POST) && $_POST["action"] == 'saveRecruitmentRequest') {  
    $results = (new CC_Recruitment_RequestController())->saveRecruitmentRequest($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getRecruitmentRequest') {  
    $results = (new CC_Recruitment_RequestController())->getRecruitmentRequest($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getProfile') {  
    $results = (new CC_Recruitment_RequestController())->getProfile($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getSkill') {  
    $results = (new CC_Recruitment_RequestController())->getSkill($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getQualification') {  
    $just = $_POST['justFavourites'] === 'false';
    $results = (new CC_Recruitment_RequestController())->getQualification($_POST['searchTerm'],$just);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfileSkills') {

    $results = (new CC_Recruitment_RequestController())->getProfileSkills($_POST['obj_new_data']);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfileQualifications') {

    $results = (new CC_Recruitment_RequestController())->GetProfileQualifications($_POST['obj_new_data']);
    $res = $results;
}


if (key_exists("action", $_POST) && $_POST["action"] == 'editRecruitmentRequest') {  
    $results = (new CC_Recruitment_RequestController())->editRecruitmentRequest($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getListRequiredQualifications') {  
    $results = (new CC_Recruitment_RequestController())->getListRequiredQualifications($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getPriority') {  
    $results = (new CC_Recruitment_RequestController())->getPriority();
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getAssignedTo') {  
    $results = (new CC_Recruitment_RequestController())->getAssignedTo($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'createCaseRecruitment') {  
    $results = (new CC_Recruitment_RequestController())->createCaseRecruitment($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'closeRecruitmentAndCase') {  
    $results = (new CC_Recruitment_RequestController())->closeRecruitmentAndCase($_POST);
    $res = (object)['results' => $results];
}


if (key_exists("action", $_POST) && $_POST["action"] == 'getActionRecruitmentRequest') {  
    $results = (new CC_Recruitment_RequestController())->getActionRecruitmentRequest($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getValidationRecruitmentRequest') {
    $results = (new CC_Recruitment_RequestController())->getValidationRecruitmentRequest($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getStepRecruitmentRequest') {
    $results = (new CC_Recruitment_RequestController())->getStepRecruitmentRequest($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'getRecruitmentRequestDataAll') {  

    $start  = $_POST['start'];   
    $length = $_POST['length'];  
    $search = $_POST['search']['value']; 
    $draw   = $_POST['draw'];
    $column_orden       = $_POST['order'][0]['column']; 
    $column_orden_type  = $_POST['order'][0]['dir']; 
    $column_order_data  = $_POST['columns'][$column_orden]['data'];
    $hide_closed = $_POST['hideClosed'] === 'true';


    $results = (new CC_Recruitment_RequestController())->getRecruitmentRequestDataAll(
        $start, 
        $length, 
        $search, 
        $draw, 
        $column_orden, 
        $column_orden_type, 
        $column_order_data, 
        $_POST['filterColumData'],
        $hide_closed
    );

    /** total obtained */
    $total_obtained = count($results['data']);

    $json_data = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => intval($total_obtained),
        "recordsFiltered" => intval($results['total_fila']),
        "data" => $results['data']
    );

    $res = $json_data;
}


if (key_exists("action", $_POST) && $_POST["action"] == 'getJobOfferClone') {  
    $results = (new CC_Recruitment_RequestController())->getJobOfferClone($_POST['searchTerm']);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'createCloneJobOffer') {  
    $results = (new CC_Recruitment_RequestController())->createCloneJobOffer($_POST);
    $res = (object)['results' => $results];
}

if (key_exists("action", $_POST) && $_POST["action"] == 'checkProfilePosition') {  
    $results = (new CC_Recruitment_RequestController())->checkProfilePosition($_POST['position']);
    $res = (object)['results' => $results];
}


echo json_encode($res);
