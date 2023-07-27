<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/CC_Profile/RatingCalculationUtility.php');
require_once('modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php');

class logic_hook_candidate_relationship_class {

    function after_delete_relationship_method($bean, $event, $arguments)
    {
        if ($arguments["relationship"] === "cc_profile_cc_job_offer") {
            $candidateIds = (new CC_Job_OfferCC_CandidateRelationship)->getCandidatesIdByJobOfferId($bean->id);
            foreach($candidateIds as $candidateId) {
                (new RatingCalculationUtility)->calculateJobAplicationRating($candidateId["id"], 'skill');
                (new RatingCalculationUtility)->calculateJobAplicationRating($candidateId["id"], 'qualification');
            }
        }
    }
    
    function after_add_relationship_method($bean, $event, $arguments)
    {
        if ($arguments["relationship"] === "cc_profile_cc_job_offer" || $arguments["relationship"] === "cc_candidate_cc_job_offer") {
            $candidateIds = (new CC_Job_OfferCC_CandidateRelationship)->getCandidatesIdByJobOfferId($bean->id);
            foreach($candidateIds as $candidateId) {
                (new RatingCalculationUtility)->calculateJobAplicationRating($candidateId["id"], 'skill');
                (new RatingCalculationUtility)->calculateJobAplicationRating($candidateId["id"], 'qualification');
            }
        }  
    }
}