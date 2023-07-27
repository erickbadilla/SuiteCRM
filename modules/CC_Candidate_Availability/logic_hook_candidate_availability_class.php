<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

use CC_Candidate_AvailabilityController;
use SuiteCRM\Exception\Exception;

require_once 'modules/CC_Candidate_Availability/controller.php';

class before_save_candidate_availability_class{

    function before_save_availability_method($bean, $event, $arguments){
        if($_POST['return_id']){
            try {
                $candidateAvailabilityController = new CC_Candidate_AvailabilityController;
                $candidateAvailabilityController->check_duplicates($bean);
                $keys = array_keys($_SESSION);
                for($i = 0; $i < count($keys); $i++){
                    if(array_key_exists("global", $_SESSION[$keys[$i]])){

                        $TimeZoneNameTo=($_SESSION[$keys[$i]]['global']['timezone']);
                    }
                }
                date_default_timezone_set($TimeZoneNameTo);
                $TimeZoneUTC=new DateTimeZone("UTC");
                $time1 = new DateTime($bean->time_1);
                $time2 = new DateTime($bean->time_2);

                $bean->time_1 = $time1->setTimezone($TimeZoneUTC)->format('H:i:s');
                $bean->time_2 = $time2->setTimezone($TimeZoneUTC)->format('H:i:s');
            } catch (\Exception $exception) {
                SugarApplication::appendErrorMessage($exception->getmessage());
                SugarApplication::redirect("index.php?action=DetailView&module={$_REQUEST["return_module"]}&record={$_REQUEST["return_id"]}");
            }
        }

    }
}

class process_record_class{
    function process_record_method($bean, $event, $arguments){
        $keys = array_keys($_SESSION);
        for($i = 0; $i < count($keys); $i++){
            if(array_key_exists("global", $_SESSION[$keys[$i]])){

                $TimeZoneNameTo=($_SESSION[$keys[$i]]['global']['timezone']);
            }
        }
        date_default_timezone_set($TimeZoneNameTo);
        $TimeZoneNameFrom="UTC";
        $bean->time_1 = empty($bean->time_1) ? gmdate("H:i") : $bean->time_1;
        $bean->time_2  = empty($bean->time_2 ) ? gmdate("H:i") : $bean->time_2 ;
        $bean->time_1 = date_create($bean->time_1, new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i:s");
        $bean->time_2 = date_create($bean->time_2, new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i:s");
    }
}
