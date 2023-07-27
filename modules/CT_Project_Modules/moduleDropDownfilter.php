<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CT_Project_Modules/controller.php';

$res;


if(!isset($_POST['projectId']) && !isset($_POST['moduleId'])) { $res['error'] = 'Project Id required';}

if(!isset($res['error'])) {

    if(isset($_POST['projectId'])) {

        $projectId = $_POST['projectId'];   
    
        $controller = new CT_Project_ModulesController();
        $res = $controller -> getMatchingModules($projectId);
    }

    if(isset($_POST['moduleId'])) {

        $moduleId = $_POST['moduleId'];   
    
        $controller = new CT_Project_ModulesController();
        $res = $controller -> getProjectModule($moduleId);
    }

}

echo json_encode($res);