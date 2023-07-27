<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Profile/controller.php';

if(!isset($_POST['profileId'])) { $res['error'] = 'Profile Id required';}

if(!isset($res['error'])) {

    if($_POST['secondaryId'] && $_POST['secondaryModule']){
        $secondaryId = $_POST['secondaryId'];
        $profileId = $_POST['profileId']; 
        $secondaryModule = $_POST['secondaryModule'];  
        $controller = new CC_ProfileController();
        $res = $controller ->rateProfile($secondaryModule, $secondaryId, $profileId);
    }

    if($_POST['sortby'] &&  $_POST['order'] && $_POST['childModule'] == 'Employee') {
        $profileId = $_POST['profileId']; 
        $offset = $_POST['offset'] ? $_POST['offset']: 0;
        $sortby = $_POST['sortby']; 
        $order = $_POST['order'];  
        $controller = new CC_ProfileController();
        $res = $controller -> matchEmployee($profileId, true, $offset, $sortby, $order);
    }

    if($_POST['sortby'] &&  $_POST['order'] && $_POST['childModule'] == 'Candidate') {
        $profileId = $_POST['profileId']; 
        $offset = $_POST['offset'] ? $_POST['offset']: 0;
        $sortby = $_POST['sortby']; 
        $order = $_POST['order'];  
        $controller = new CC_ProfileController();
        $res = $controller -> matchCandidate($profileId, true, $offset, $sortby, $order);
    }
}

echo json_encode($res);