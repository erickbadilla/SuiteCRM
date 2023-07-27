<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Profile/controller.php';
require_once 'modules/CC_Skill/controller.php';
$res = [];
if(!isset($_POST['profileId'])) { $res['error'] = 'Profile Id required';}

if(!isset($res['error'])) {
    $profileId = $_POST['profileId'];

    if(key_exists("action",$_POST) && $_POST["action"]=='get'){
        $skills = new CC_SkillController();
        $res['data'] = $skills->getRecordsByProfileId($profileId,false);
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='updateSkill'){
        $skill = new CC_SkillCC_ProfileRelationship();
        $skill = $skill->get_relation_row($profileId, $_POST["id"]);
        $skill->rating = $_POST["rating"];
        $skill->years = $_POST["years_of_experience"];
        $skill->save();
        $res['data'] = $skill;
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='deleteSkill'){
        $skillId = $_POST["id"];
        $skill = new CC_SkillCC_ProfileRelationship();
        $skill = $skill->get_relation_row($profileId, $skillId);
        $res['data'] = $skill->remove_related_skill($profileId, $skillId);
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='addSkill'){
        $skillId = $_POST["id"];
        $actualRelation = 'cc_profile_cc_skill';
        $profileBean = BeanFactory::newBean('CC_Profile');
        $profileBean->load_relationship($actualRelation);
        $profileBean->id = $profileId;
        $skill = (object) ['Id' => $skillId];
        $skillBean = (new CC_SkillController)->saveSkillRecord($skill);
        $status = $profileBean->$actualRelation->add($skillBean);
        $res['data']->status = $status;
        $res['data']->mesagge = $status ? 'Skill related correctly.' : 'The Skill could not be correctly related to the Profile.';
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='searchSkills'){
        $term = $_POST['searchTerm'];
        $results = (new CC_SkillController)->searchByTerm($term);
        $res = (object) ['results' => $results];
    }

}

echo json_encode($res);