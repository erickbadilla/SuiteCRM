<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/CC_Profile/RatingCalculationUtility.php');

class after_relationship_delete_class {

    function after_relationship_delete_method($bean, $event, $arguments)
    {
        if ($arguments["relationship"] === "cc_candidate_cc_skill") {
            (new RatingCalculationUtility)->calculateJobAplicationRating($bean->id, 'skill');
        }elseif ($arguments["relationship"] === "cc_candidate_cc_qualification") {
            (new RatingCalculationUtility)->calculateJobAplicationRating($bean->id, 'qualification');
        }
    }

}