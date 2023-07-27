<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php');
require_once ('modules/CC_Profile/RatingCalculationUtility.php');
require_once ("modules/CC_application_stage/StageHandler.php");


class after_relationship_add_class {

function after_relationship_add_method($bean, $event, $arguments){

    if ($arguments["relationship"] === "cc_candidate_cc_job_offer") {

        $cc_candidate_cc_job_offer = 'cc_candidate_cc_job_offer';  
        $beanCandidate = BeanFactory::getBean('CC_Candidate',$bean->id);
        $beanCandidate->load_relationship($cc_candidate_cc_job_offer);
        $id_candidet = $bean->id;

        $candidateJobOfferFields = $beanCandidate->cc_candidate_cc_job_offer->getBeans();

        $applicationStage = new StageHandler();
        $stageRelation = 'cc_job_applications_cc_application_stage';
        $actualStage = $applicationStage->getActualStage();
        
            foreach($candidateJobOfferFields as $key => $item){

                if($arguments['related_bean']->id == $candidateJobOfferFields[$key]->id){
                   
                    $rObject = new RatingCalculationUtility();
                    $ratings = $rObject->calculateJobOfferCandidateRating($id_candidet,$candidateJobOfferFields[$key]->id);
            
                    $FieldsRelations = new CC_Job_OfferCC_CandidateRelationship();
                    $FieldsRelations = $FieldsRelations->get_relation_row($bean->id, $candidateJobOfferFields[$key]->id);
                
                    /* recalculate */
                     $FieldsRelations->type = $actualStage->type;
                    $FieldsRelations->stage = $actualStage->name;
                    //ratings will be calculate by hooks  
                    $FieldsRelations->save();

                    
                   if(!BeanFactory::getBean('CC_Job_Applications',$FieldsRelations->id)){
                      $jobOfferApplicationsBean = BeanFactory::newBean('CC_Job_Applications');
                      $new_with_id = $FieldsRelations->id;
                      $jobOfferApplicationsBean->id                   = $new_with_id;
                      $jobOfferApplicationsBean->new_with_id          = $new_with_id;
                      $jobOfferApplicationsBean->name                 = $bean->name .' / '. $candidateJobOfferFields[$key]->name;
                      $jobOfferApplicationsBean->application_type     = $FieldsRelations->type;
                      $jobOfferApplicationsBean->general_rating       = $ratings['general'];
                      $jobOfferApplicationsBean->skill_rating         = $ratings['skills'];
                      $jobOfferApplicationsBean->qualification_rating = $ratings['qualifications'];
                      $jobOfferApplicationsBean->actual_stage         = $actualStage->name;
                      $jobOfferApplicationsBean->actual_stage_id      = $actualStage->id;
                      $jobOfferApplicationsBean->last_modification_data  = date('Y-m-d h:i:s');
                      $jobOfferApplicationsBean->application_type         = $actualStage->type;
                      
                      $jobOfferApplicationsBean->save();

                      if(!$jobOfferApplicationsBean){
                         var_dump("Error creating applications, contact support");
                         exit;
                      }else{
                       $jobOfferApplicationsBean->load_relationship($stageRelation);
                       $jobOfferApplicationsBean->$stageRelation->add($actualStage);
                      
                    }


                   }
                  
                }   
            }  
           

           
           
    } 
}
    
 

}