<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CT_Modules_Worktype/controller.php';

$res;


if(!isset($_POST['moduleId']) && !isset($_POST['worktypeId'])) { $res['error'] = 'module Id required';}

if(!isset($res['error'])) {

    if(isset($_POST['moduleId'])) {

        $moduleId = $_POST['moduleId'];   
    
        $controller = new CT_Modules_WorktypeController();
        $res = $controller -> getMatchingWorktypes($moduleId);
    }

    if(isset($_POST['worktypeId'])) {

        $worktypeId = $_POST['worktypeId'];   
    
        $controller = new CT_Modules_WorktypeController();
        $res = $controller -> getModuleWorktype($worktypeId);
    }

}

echo json_encode($res);