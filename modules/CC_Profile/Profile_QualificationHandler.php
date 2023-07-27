<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Profile/controller.php';
require_once 'modules/CC_Qualification/controller.php';
require_once('modules/CC_Skill/CC_SkillCC_ProfileRelationship.php');

if(!isset($_POST['profileId'])) { $res['error'] = 'Profile Id required';}

if(!isset($res['error'])) {
    $profileId = $_POST['profileId'];

    if(key_exists("action",$_POST) && $_POST["action"]=='get'){
        $qualification = new CC_QualificationController();
        $res['data'] = $qualification->getRecordsByProfileId($profileId,false);
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='deleteQualification'){
        $qualificationId = $_POST['Id'];
        $qualification = new CC_QualificationCC_ProfileRelationship();
        $qualification->get_relation_row($profileId, $qualificationId);
        $res['data'] = $qualification->remove_related_qualification($profileId, $qualificationId);
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='addQualification'){
        $qualificationId = $_POST['id'];
        $actualRelation = 'cc_profile_cc_qualification';
        $profileBean = BeanFactory::newBean('CC_Profile');
        $profileBean->load_relationship($actualRelation);
        $profileBean->id = $profileId;
        $qualificationBean = BeanFactory::getBean('CC_Qualification', trim ($qualificationId));
        $status = $profileBean->$actualRelation->add($qualificationBean);
        $res['data']->status = $status;
        $res['data']->mesagge = $status ? 'Qualification related correctly.' : 'The Qualification could not be correctly related to the Profile.';
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='searchQualifications'){
        $term = $_POST['searchTerm'];
        $just = $_POST['justFavourites'] === 'false';
        $results = (new CC_QualificationController)->searchByTerm($term,$just);
        $res = (object) ['results' => $results];
    }
}

echo json_encode($res);