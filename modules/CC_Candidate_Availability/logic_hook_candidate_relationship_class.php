<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

use CC_Candidate_AvailabilityController;
use SuiteCRM\Exception\Exception;

require_once 'modules/CC_Candidate_Availability/controller.php';

class before_save_candidate_relationship_class{

    function before_save_relationship_method($bean, $event, $arguments){
        if($_POST['return_id']){
            try {
                $candidateAvailabilityController = new CC_Candidate_AvailabilityController;
                $candidateAvailabilityController->check_duplicates($bean);
            } catch (\Exception $exception) {      
                SugarApplication::appendErrorMessage($exception->getmessage());
                SugarApplication::redirect("index.php?action=DetailView&module={$_REQUEST["return_module"]}&record={$_REQUEST["return_id"]}");                
            }
        }

    }
}