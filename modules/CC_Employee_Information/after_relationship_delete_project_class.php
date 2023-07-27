<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once("modules/CC_Employee_Information/after_relationship_add_project_class.php");

use after_relationship_add_class;

class after_relationship_delete_class {

    public function after_relationship_delete_method($bean, $event, $arguments)
    {
        if($arguments["related_module"] === "Project") 
        {
            $action = "changed from the";
            (new after_relationship_add_class())->PMSNotification($arguments["related_id"], $arguments["related_bean"]->name, $bean->name, $action);
        }
    }

}