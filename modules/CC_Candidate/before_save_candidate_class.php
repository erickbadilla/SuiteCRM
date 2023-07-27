<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class before_save_candidate_class{
    function before_save_method($bean, $event, $arguments){
        $bean->name = $bean->first_name.", ".$bean->last_name;
    }
}