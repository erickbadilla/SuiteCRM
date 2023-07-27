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
        $res = $controller -> matchProfile($secondaryModule, $secondaryId, $profileId);
    }

    if($_POST['sortby'] &&  $_POST['order'] && $_POST['childModule'] == 'Employee') {
        $profileId = $_POST['profileId'];
        $offset = $_POST['offset'] ? $_POST['offset']: 0;
        $sortby = $_POST['sortby'];
        $order = $_POST['order'];
        $limit = $_POST['limit'];
        $draw = $_POST['draw'];
        $active_only = ($_POST['activeOnly']=="true");
        $unassigned_only = ($_POST['assignedOnly']=="true");
        $controller = new CC_ProfileController();
        $res = $controller -> matchEmployee($profileId, $draw, $limit, $offset, $sortby, $order, $active_only, $unassigned_only);
    }

    if($_POST['sortby'] &&  $_POST['order'] && $_POST['childModule'] == 'Candidate') {
        $profileId = $_POST['profileId'];
        $offset = $_POST['offset'] ? $_POST['offset']: 0;
        $sortby = $_POST['sortby'];
        $order = $_POST['order'];
        $limit = $_POST['limit'];
        $draw = $_POST['draw'];
        $controller = new CC_ProfileController();
        $res = $controller -> matchCandidate($profileId, $draw, $limit, $offset, $sortby, $order);
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($res);