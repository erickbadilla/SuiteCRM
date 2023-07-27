<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
global $current_user;
require_once "modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php";
require_once "modules/CC_application_stage/StageHandler.php";
require_once 'modules/CC_Profile/RatingCalculationUtility.php';
require_once 'custom/Extension/application/Include/cc_recruitment_activity_handler.php';

function ajaxResult(bool $success,array $messages, $new_id=null){
    ob_get_clean();
    $ajax_ret = array( 'success' => $success,  'message' => $messages );
    if(!is_null($new_id)){
        $ajax_ret['new_id']=$new_id;
        $ajax_ret['module']='CC_Job_Applications';
    }
    echo json_encode($ajax_ret);
}

/**
 * Filter and SANITIZE inputs
 */
$module =  filter_input(INPUT_POST, 'module', FILTER_SANITIZE_SPECIAL_CHARS);
$return_module =  filter_input(INPUT_POST, 'return_module', FILTER_SANITIZE_SPECIAL_CHARS);
$return_id =  filter_input(INPUT_POST, 'return_id', FILTER_SANITIZE_SPECIAL_CHARS);
$return_action =  filter_input(INPUT_POST, 'return_action', FILTER_SANITIZE_SPECIAL_CHARS);
$application_type =  filter_input(INPUT_POST, 'application_type', FILTER_SANITIZE_SPECIAL_CHARS);
$candidate_id =  filter_input(INPUT_POST, 'candidate_id', FILTER_SANITIZE_SPECIAL_CHARS);
$job_offer_id =  filter_input(INPUT_POST, 'job_offer_id', FILTER_SANITIZE_SPECIAL_CHARS);
$authenticated_user = $_SESSION['authenticated_user_id'];

$error = [];
$messages = [];
// create a Candidate / Job_Offer Relation Object
$candidateOfferRelation = new CC_Job_OfferCC_CandidateRelationship();
if($candidateOfferRelation->get_relation_row($candidate_id, $job_offer_id)){
    $error[] = "The candidate has already applied for this job";
}
$new_with_id = null;
if(empty($error)){
    $applicationBean = $this->bean;
    $list = (new StageHandler(null, $application_type))->getList($application_type);
    $applicationStage = new StageHandler($list[0]->id, $application_type);

    $stageRelation = 'cc_job_applications_cc_application_stage';

    $actualStage = $applicationStage->getActualStage();

    if($actualStage->name == "" || $actualStage->name == null){
        $error[] = "There is not an stage created";
    }

    $bean = $this->bean;
    if(!is_null($bean->id)){
        $error[] = "There is an actual Application created";
    } else {
        $db = DBManagerFactory::getInstance();
        $db->query('START TRANSACTION');

        $rObject = new RatingCalculationUtility();
        $ratings = $rObject->calculateJobOfferCandidateRating($candidate_id,$job_offer_id);

        $candidate_bean = BeanFactory::getBean('CC_Candidate', $candidate_id);
        $job_offer_bean = BeanFactory::getBean('CC_Job_Offer', $job_offer_id);
        $bean = BeanFactory::getBean('CC_Job_Applications');

        $new_with_id = create_guid();
        $new_name = $candidate_bean->name." / ".$job_offer_bean->name;
        $bean->id= $new_with_id;
        $bean->new_with_id = $new_with_id;
        $bean->created_by = $authenticated_user;
        $bean->modified_user_id = $authenticated_user;
        $bean->name = $new_name;
        $bean->application_type = $application_type;
        $bean->skill_rating = $ratings['skills'];
        $bean->qualification_rating = $ratings['qualifications'];
        $bean->general_rating = $ratings['general'];
        $bean->best_rating = 0;
        $bean->actual_stage = $actualStage->name;
        $bean->actual_stage_id = $actualStage->id;
        $bean->last_modification_data = date('Y-m-d h:i:s');
        $bean->load_relationship($stageRelation);
        $bean->$stageRelation->add($actualStage);

        $candidateOfferRelation->id = $new_with_id;
        $candidateOfferRelation->new_with_id = $new_with_id;
        $candidateOfferRelation->cc_candidate_cc_job_offercc_candidate_ida = $candidate_id;
        $candidateOfferRelation->cc_candidate_cc_job_offercc_job_offer_idb = $job_offer_id;
        $candidateOfferRelation->skill_rating = $ratings['skills'];
        $candidateOfferRelation->qualification_rating = $ratings['qualifications'];
        $candidateOfferRelation->general_rating = $ratings['general'];
        $candidateOfferRelation->name = $new_name;
        $candidateOfferRelation->type = $application_type;
        $candidateOfferRelation->application_state = $actualStage->id;
        $candidateOfferRelation->stage = $actualStage->name;
        $candidateOfferRelation->created_by = $authenticated_user;
        $candidateOfferRelation->modified_user_id = $authenticated_user;
        $relationResult = $candidateOfferRelation->save();
        $result = $bean->save();

        if((!$relationResult)||(!$result)){
            $db->query('ROLLBACK');
            $error[] = "There was an error saving the application";
        } else {
            $registry = (new CC_Recruitment_Activity_Handler($candidate_bean,"The candidate ".$candidate_bean->name." applied for the offer",null,null,$bean->id,null))->saveRecruitmentActivity();
            $registry = (new CC_Recruitment_Activity_Handler($bean,"A new candidate has applied",null,null,null,null,null))->saveRecruitmentActivity();
            $db->query('COMMIT');
            $messages[] = $new_name;
        }
    }
}
if(empty($error)){
    ajaxResult(true,$messages,$new_with_id);
}else{
    ajaxResult(false,$error);
}
