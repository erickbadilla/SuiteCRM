<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class before_save_skill_class {
    const DUPLICATE_MESSAGE = 'ERROR: There is a skill with the same value';

    function before_save_method($bean, $event, $arguments)
    {
        $duplicates = $bean->findDuplicates();
        if(!is_null($duplicates) && count($duplicates)>0){
            SugarApplication::appendErrorMessage(self::DUPLICATE_MESSAGE);
            if(!empty($_REQUEST['view'])){
                echo '<script>window.location.reload();</script>';
            } else {
                $record = (!empty($_REQUEST['return_id']))?'record='.$_REQUEST['return_id']:'';
                $action = (!empty($_REQUEST['return_id']))?$_REQUEST['return_action']:'ListView';
                SugarApplication::redirect("index.php?action={$action}&module={$_REQUEST['return_module']}{$record}");
            }
        }
    }
}