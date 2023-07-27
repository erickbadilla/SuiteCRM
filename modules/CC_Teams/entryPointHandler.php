<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once 'modules/CC_Teams/controller.php';
require_once('custom/modules/Project/ProjectCC_Employee_InformationRelationship.php');

$res = [];
$default = array("options" => array(
    "default" => null
));

global $app_list_strings;

$row_id = filter_input(INPUT_POST,'row_id',FILTER_DEFAULT,$default);
$row_id = ($row_id==='')?null:$row_id;

$userAction = filter_input(INPUT_GET, 'userAction', FILTER_DEFAULT, $default);
$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT, $default);
$project_id = filter_input(INPUT_POST,'project_id',FILTER_DEFAULT,$default);
$employee_id = filter_input(INPUT_POST,'employee_id',FILTER_DEFAULT,$default);
$employee_position_name = filter_input(INPUT_POST,'employee_position_name',FILTER_DEFAULT,$default);
$start_date = filter_input(INPUT_POST,'start_date',FILTER_DEFAULT,$default);
$end_date = filter_input(INPUT_POST,'end_date',FILTER_DEFAULT,$default);
$description = filter_input(INPUT_POST,'description',FILTER_DEFAULT,$default);
$project_name = filter_input(INPUT_POST,'project_name',FILTER_DEFAULT,$default);
$employee_name = filter_input(INPUT_POST,'employee_name',FILTER_DEFAULT,$default);
$cc_job_description_id = filter_input(INPUT_POST,'cc_job_description_id',FILTER_DEFAULT,$default);
$is_assigned = filter_input(INPUT_POST,'is_assigned',FILTER_DEFAULT,$default);
$role = filter_input(INPUT_POST,'role',FILTER_DEFAULT,$default);
$target_project_id = filter_input(INPUT_POST,'target_project_id',FILTER_DEFAULT,$default);
$actual_project_id = filter_input(INPUT_POST,'actual_project_id',FILTER_DEFAULT,$default);
$target_manager_id = filter_input(INPUT_POST,'target_manager_id',FILTER_DEFAULT,$default);
$actual_manager_id = filter_input(INPUT_POST,'actual_manager_id',FILTER_DEFAULT,$default);

// Update Project Data
$row_notes = filter_input(INPUT_POST,'row_notes',FILTER_DEFAULT,$default);
$row_date_end = filter_input(INPUT_POST,'row_date_end',FILTER_DEFAULT,$default);
$target_worktype = filter_input(INPUT_POST,'target_worktype',FILTER_DEFAULT,$default);
$target_module = filter_input(INPUT_POST,'target_module',FILTER_DEFAULT,$default);
$target_description = filter_input(INPUT_POST,'target_description',FILTER_DEFAULT,$default);
$target_load = filter_input(INPUT_POST,'target_load',FILTER_DEFAULT,$default);
$target_date_start = filter_input(INPUT_POST,'target_date_start',FILTER_DEFAULT,$default);
$target_date_end = filter_input(INPUT_POST,'target_date_end',FILTER_DEFAULT,$default);
$target_role = filter_input(INPUT_POST,'target_role',FILTER_DEFAULT,$default);
$target_is_assigned = filter_input(INPUT_POST,'target_is_assigned',FILTER_DEFAULT,$default);
$target_is_default = filter_input(INPUT_POST,'target_is_default',FILTER_DEFAULT,$default);
$select_employee = filter_input(INPUT_POST,'select_employee',FILTER_DEFAULT,$default);
$new_allocation = filter_input(INPUT_POST,'new_allocation',FILTER_DEFAULT,$default);
$isExistingCard = filter_input(INPUT_POST,'isExistingCard',FILTER_DEFAULT,$default);

$controller = new CC_TeamsController();

if (!is_null($action)) {
    if ($action == 'get'){
        $res = $controller->getProjectAllocation();
    }
    if ($action == 'getPM'){
        $managers = $controller->getProjectManagersList();
        $res = $controller->getManagerAllocation($managers);
    }
    if ($action == 'getTL'){
        $managers = $controller->getTechLeadList();
        $res = $controller->getManagerAllocation($managers);
    }

}

if (!is_null($userAction)) {
    global $current_user;

    if($userAction == "getEmployeeCard"){
        $employee_bean = BeanFactory::getBean('CC_Employee_Information',$select_employee);

        if(!$employee_bean){
            $res['result']=false;
            $res['message']= "Selected employee does not exist";
            echo json_encode($res);
            exit();
        }

        $job_description = BeanFactory::getBean('CC_Job_Description',$employee_bean->cc_job_description_id_c);
        $res['employee_id'] = $employee_bean->id;
        $res['employee_name'] = $employee_bean->name;
        $res['cc_job_description_id_c'] = $employee_bean->cc_job_description_id_c;
        $res['employee_position_name'] = ($job_description)?$job_description->name:'';
        $res['row_id'] = 'newCard|'.$employee_bean->id;
        $res['project_id'] = 'null';
        $res['project_name'] = '';

    }

    if($userAction == 'updateProjectAllocationData'){
        $relation_bean = ($row_id!==null)?
            BeanFactory::getBean('ProjectCC_Employee_InformationRelationship',$row_id):false;
        $employee_bean = BeanFactory::getBean('CC_Employee_Information',$employee_id);
        $target_bean = BeanFactory::newBean('ProjectCC_Employee_InformationRelationship');
        $modules_bean = BeanFactory::getBean('CT_Project_Modules',$target_module);
        $worktype_bean = BeanFactory::getBean('CT_Modules_Worktype',$target_worktype);
        $project_bean = BeanFactory::getBean('Project',$target_project_id);

        if(!$project_bean || !$worktype_bean || !$modules_bean || !$target_bean || !$employee_bean){
            $res['result']=false;
            $res['message']= "Inconsistent request data";
            echo json_encode($res);
            exit();
        }

        if($relation_bean){
            $relation_bean->end_date = $row_date_end;
            $relation_bean->notes = $row_notes;
            $relation_bean->modified_user_id = $current_user->id;
            $res["old"]=$relation_bean->save();
        }

        $update_employee = false;
        if(boolval($employee_bean->is_assigned)!= boolval($target_is_assigned)){
            $employee_bean->is_assigned = 0;
            $update_employee = true;
        }

        if($target_is_default == true){
            $employee_bean->project_id_c = $target_project_id;
            $update_employee = true;
        }

        $res["employee"] = $employee_id;
        if($update_employee){
            $employee_bean->modified_user_id = $current_user->id;
            $res["employee"]=$employee_bean->save();
        }

        $target_bean->cc_employee_information_projectcc_employee_information_ida = $employee_id;
        $target_bean->cc_employee_information_projectproject_idb = $target_project_id;
        $target_bean->start_date = $target_date_start;
        $target_bean->end_date = $target_date_end;
        $target_bean->description = $target_description;
        $target_bean->role = $target_role;
        $target_bean->ct_project_modules_id = $target_module;
        $target_bean->project_modules_name = $modules_bean->name;
        $target_bean->ct_modules_worktype_id = $target_worktype;
        $target_bean->modules_worktype_name = $worktype_bean->name;
        $target_bean->load_capacity = floatval($target_load);
        $target_bean->created_by = $current_user->id;
        $target_bean->modified_user_id = $current_user->id;
        $res["target"]=$target_bean->save();


        $res['result']=true;
        $res["message"] = sprintf("%s assigned to %s as %s with %s load capacity",
            $employee_bean->name,$project_bean->name,$app_list_strings['employee_project_role_list'][$target_role],$target_load);

        if(!$res["target"] || !$res["employee"]){
            $res['result']=false;
            $res['message']= "Inconsistent request data";
        }
    }

    if($userAction === 'getDetailsTemplate'){

        $arraySelect = function ($arr){
            $result = [];
            foreach ($arr as $key => $value){
                $result[$key] = $value->name;
            }
            return $result;
        };

        // This is an HTML end point
        $smarty = new Sugar_Smarty();
        $template = 'modules/CC_Teams/tpls/ProjectAllocationAction.tpl';
        if(!is_null($row_id)){

            $newbean = BeanFactory::newBean('ProjectCC_Employee_InformationRelationship');
            $databean = BeanFactory::getBean('ProjectCC_Employee_InformationRelationship',$row_id);
            $databean = BeanFactory::getBean('ProjectCC_Employee_InformationRelationship',$row_id);
            $modules = $controller->getProjectsModules($target_project_id);
            $targetProject = $controller->getProject($target_project_id);
            $worktypes = $controller->getProjectWorktypes(array_keys($modules));
            $smarty->assign('PROJECT_MODULES',$arraySelect($modules));
            $smarty->assign('PROJECT_WORKTYPES',$arraySelect($worktypes));
            $smarty->assign('ROW_ID',$row_id);
            $smarty->assign('ACTUAL_PROJECT','true');
            $smarty->assign('PROJECT_ID',$project_id);
            $smarty->assign('EMPLOYEE_ID',$employee_id);
            $smarty->assign('EMPLOYEE_POSITION_NAME',$employee_position_name);
            $smarty->assign('ROW_START_DATE',$start_date);
            $smarty->assign('ROW_END_DATE',$end_date);
            $smarty->assign('ROW_DESCRIPTION',$description);
            $smarty->assign('ROW_PROJECT_NAME',$project_name);
            $smarty->assign('ROW_EMPLOYEE_NAME',$employee_name);
            $smarty->assign('TARGET_PROJECT_NAME',$targetProject->name);
            $smarty->assign('TARGET_PROJECT_ID',$targetProject->id);
            $smarty->assign('CC_JOB_DESCRIPTION_ID',$cc_job_description_id);
            $smarty->assign('IS_ASSIGNED',$is_assigned);
            $smarty->assign('EMP_PROJECT_ROLE_LIST',$app_list_strings['employee_project_role_list']);
        }

        if(!is_null($new_allocation)){
            $modules = $controller->getProjectsModules($target_project_id);
            $targetProject = $controller->getProject($target_project_id);
            $worktypes = $controller->getProjectWorktypes(array_keys($modules));
            $smarty->assign('PROJECT_MODULES',$arraySelect($modules));
            $smarty->assign('PROJECT_WORKTYPES',$arraySelect($worktypes));
            $smarty->assign('PROJECT_ID',$project_id);
            $smarty->assign('EMPLOYEE_ID',$employee_id);
            $smarty->assign('EMPLOYEE_POSITION_NAME',$employee_position_name);
            $smarty->assign('ACTUAL_PROJECT','false');
            $smarty->assign('TARGET_PROJECT_NAME',$targetProject->name);
            $smarty->assign('TARGET_PROJECT_ID',$targetProject->id);
            $smarty->assign('IS_ASSIGNED',$is_assigned);
            $smarty->assign('EMP_PROJECT_ROLE_LIST',$app_list_strings['employee_project_role_list']);
        }

        $view = $smarty->fetch($template);
        echo $view;
        exit;
    }

    if($userAction === 'createManagementSlot'){
        $employee_bean = BeanFactory::getBean('CC_Employee_Information',$employee_id);
        $target_manager = BeanFactory::getBean('CC_Employee_Information',$target_manager_id);
        $res['result']=false;
        $res['message']= "There was an error creating your management relation";
        if($employee_bean || $target_manager){
            $result = $controller->createTeamRecord($employee_bean, $target_manager);
            if($result){
                $res['result']=true;
                $res["message"] = sprintf("%s assigned to %s",
                    $employee_bean->name,$target_manager->name);
                $res['details']=$result;
            }
        }

    }

    if($userAction === 'updateManagementSlot'){
        $team_record = BeanFactory::getBean('CC_Teams',$row_id);
        $employee_bean = BeanFactory::getBean('CC_Employee_Information',$employee_id);
        $actual_manager = BeanFactory::getBean('CC_Employee_Information',$actual_manager_id);
        $target_manager = BeanFactory::getBean('CC_Employee_Information',$target_manager_id);
        $res['result']=false;
        $res['message']= "There was an error creating your management relation";
        if($team_record || $employee_bean || $target_manager){
            $result = $controller->updateTeamRecord($team_record,$employee_bean, $target_manager);
            if($result){
                $res['result']=true;
                $res["message"] = sprintf("%s moved from %s to %s",
                    $employee_bean->name,$actual_manager->name, $target_manager->name);
                $res['details']=$result;
            }
        }
    }

    if($userAction === 'removeManagementSlot'){
        try {
            $team_record = BeanFactory::getBean('CC_Teams',$row_id);

            if(!$team_record){
                throw new Exception("Inconsistent request data");
            }

            $employee_bean = BeanFactory::getBean('CC_Employee_Information',
                $team_record->cc_employee_information_id_c);

            $actual_manager = BeanFactory::getBean('CC_Employee_Information',
            $team_record->cc_employee_information_id1_c);

            if(!$employee_bean || !$actual_manager){
                throw new Exception("Missing related data");
            }

            $result = $controller->deleteTeamRecord($team_record,$employee_bean,$actual_manager);
            if($result){
                $res['result']=true;
                $res["message"] = sprintf("Relation between %s and %s was deleted",
                    $employee_bean->name,$actual_manager->name);
                $res['details']=$result;
            }
        } catch (Exception $e){
            $res = ['result'=>false,'message'=>$e->getMessage()];
        }
    }
}

echo json_encode($res);



















