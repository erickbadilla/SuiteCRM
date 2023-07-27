<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class before_save_personality_test_class{
    function before_save_method($bean, $event, $arguments){
        $related_module = $_REQUEST['return_module'];
        $related_id = $_REQUEST['return_id'];
        if($related_module!=$bean->object_name){
            $relatedBean = BeanFactory::getBean($related_module, $related_id);
            if($relatedBean){
                $bean->name = $relatedBean->name;
            }
        }
        $bean->modify_by_user = $bean->modified_user_id;
    }
}