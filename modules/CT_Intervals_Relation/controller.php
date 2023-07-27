<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/CT_Activity/controller.php';

use Api\V8\Utilities;
use \BeanFactory;

class CT_Intervals_RelationController extends SugarController {

    private $Project = "Project";
    private $Account = "Accounts";
    private $CTActivity = "CT_Activity";
    private $CT_Modules = "CT_Project_Modules";
    private $CT_Worktype = "CT_Modules_Worktype";
    private $intervalsTableName = "ct_intervals_relation";
    private static $customModuleName = "CT_Intervals_Relation";
    private $nameOfRelationshipProjectModule = "ct_project_modules_project";
    private $nameOfRelationshipIntervalsProject = "ct_intervals_relation_project";
    private $nameOfRelationshipEmployeeProject = "cc_employee_information_project";
    private $nameOfRelationshipIntervalsAccounts = "ct_intervals_relation_accounts";
    private $nameOfRelationshipActivityEmployee = "ct_activity_cc_employee_information";
    private $nameOfRelationshipModuleWorktype = "ct_modules_worktype_ct_project_modules";
    private $nameOfRelationshipAccountProject = "accounts";

    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CT_Intervals_RelationController() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    /**
     * @param SugarBean
     * @param object $intervalsRelationSync
     * @return array
     */
    public function saveMyIntervalsRelationSync(\SugarBean $parentBean, object $activity)
    {
        //Create bean intervals
        $my_interval_relation_sync = BeanFactory::newBean(self::$customModuleName);
        $results_fill = $this->fillMyIntervalsRelationSync($my_interval_relation_sync, $activity, $parentBean);
        $my_interval_relation_sync = $results_fill[0];
        $account = $results_fill[1];
        $project = $results_fill[2];
        $activity = $results_fill[3];

        $my_interval_relation_sync->name = $parentBean->name." - ".$project->name." - ".$activity->External_Id;
        $my_interval_relation_sync->save();

        //Add relationship between Project and Employee
        $employee_project = $this->nameOfRelationshipEmployeeProject;
        $parentBean->load_relationship($employee_project);
        $index_relation_project_employee = false;
        $project_employee_relations = $parentBean->$employee_project->get();
        if(count($project_employee_relations) > 0){
            $index_relation_project_employee = array_search($project->id, $project_employee_relations);
        }
        if(!is_numeric($index_relation_project_employee)){
            $parentBean->$employee_project->add($project);
        }

        //Add relationship between Account and Project
        $account_project = $this->nameOfRelationshipAccountProject;
        $project->load_relationship($account_project);
        $index_relation_account_project = false;
        $account_project_relations = $project->$account_project->get();
        if(count($account_project_relations) > 0){
            $index_relation_account_project = array_search($account->id, $account_project_relations);
        }
        if(!is_numeric($index_relation_account_project)){
            $project->$account_project->add($account);
        }

        //Create Activity
        $activity->Name = $my_interval_relation_sync->name;
        $activity_id = (new CT_ActivityController)->saveActivityRecord($parentBean, $activity);
        $activityBean = Utilities::getCustomBeanById($this->CTActivity, $activity_id['Activity Id']);

        //Add Activity Id and Name to My Intervals Relation Sync
        $my_interval_relation_sync->activity_id = $activityBean->id;
        $my_interval_relation_sync->activity_name = $activityBean->name;
        $my_interval_relation_sync->save();

        //Add relationships Project and Account to intervals Bean
        $this->addRelationshipsProjectAndAccount($my_interval_relation_sync, $account, $project);

        //Return Activity ID
        $result = $activity_id;

        return $result;
    }

    /**
     * @param SugarBean
     * @param string
     * @param object
     * @return array
     */
    public function updateMyIntervalsRelationSync(\SugarBean $employeeBean, string $externalActId, object $activity)
    {
        //Get the Intrvals_relation with externalActId
        $my_interval_relation_sync = $this->getMyIntervalsReSiByExternalActivityId($externalActId);

        //Get the activity id and Activity Bean with Intervals_relation
        $activity_id = $my_interval_relation_sync->activity_id;

        if(!$activity_id) return [];

        $activityBean = Utilities::getCustomBeanById($this->CTActivity, $activity_id);

        //Validate the relationship between the activity and the employee
        $exist = Utilities::validateRelation($this->nameOfRelationshipActivityEmployee, $employeeBean, $activity_id);
        if(!$exist) throw new \InvalidArgumentException('Employee doesn\'t have that Activity');

        //////////////START DELETE CHANGED RELATIONSHIPS //////////////////////////////////////////////
        $accountBean = Utilities::getCustomBeanById($this->Account, $my_interval_relation_sync->client_id);
        $projectBean = Utilities::getCustomBeanById($this->Project, $my_interval_relation_sync->id_project);
        $moduleBean = Utilities::getCustomBeanById($this->CT_Modules, $my_interval_relation_sync->module_id);
        $worktypeBean = Utilities::getCustomBeanById($this->CT_Worktype, $my_interval_relation_sync->work_id);

        //Evaluate which elements have changed and Update that, if change remove the relationship
        $number_elements_account_project = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $accountBean->id,
            "client_id",
            $projectBean->id,
            "id_project"
        );
        $account_project = $this->nameOfRelationshipAccountProject;
        $projectBean->load_relationship($account_project);

        if($my_interval_relation_sync->external_client_id != $activity->Account_Id){
            $number_elements_account = $this->howManyExternalIdAreThere(
                $my_interval_relation_sync->id,
                $accountBean->id,
                'client_id'
            );
            if($number_elements_account === 0){
                $account_intervals = $this->nameOfRelationshipIntervalsAccounts;
                $my_interval_relation_sync->load_relationship($account_intervals);
                $my_interval_relation_sync->$account_intervals->delete($my_interval_relation_sync->id, $accountBean);
            }

            if($number_elements_account_project === 0){
                $projectBean->$account_project->delete($projectBean->id, $accountBean);
            }
            // $this->deleteIfItIsTheLastOne($my_interval_relation_sync, "external_client_id", $accountBean);
        }

        $project_project_module_relation_name = $this->nameOfRelationshipProjectModule;
        $moduleBean->load_relationship($project_project_module_relation_name);
        $number_elements_project_module = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $moduleBean->id,
            "module_id",
            $projectBean->id,
            "id_project"
        );

        $employee_project = $this->nameOfRelationshipEmployeeProject;
        $employeeBean->load_relationship($employee_project);

        if($my_interval_relation_sync->external_project_id != $activity->Project_Id){
            $number_elements_project = $this->howManyExternalIdAreThere(
                $my_interval_relation_sync->id,
                $projectBean->id,
                'id_project'
            );
            if($number_elements_project === 0){
                $project_relation_name = $this->nameOfRelationshipIntervalsProject;
                $projectBean->load_relationship($project_relation_name);
                $projectBean->$project_relation_name->delete($projectBean->id, $my_interval_relation_sync);
            }

            $number_elements_employee_project = $this->GetQuantityByComparingFields(
                $this->intervalsTableName,
                $my_interval_relation_sync->id,
                $employeeBean->id,
                "id_employee",
                $projectBean->id,
                "id_project"
            );
            if($number_elements_employee_project === 0){
                $employeeBean->$employee_project->delete($employeeBean->id, $projectBean);
            }

            if($number_elements_project_module === 0){
                $moduleBean->$project_project_module_relation_name->delete($moduleBean->id, $projectBean);
            }

            if($number_elements_account_project === 0){
                $projectBean->$account_project->delete($projectBean->id, $accountBean);
            }

            // $this->deleteIfItIsTheLastOne($my_interval_relation_sync, "external_project_id", $projectBean);
        }

        $workType_project_module_relation_name = $this->nameOfRelationshipModuleWorktype;
        $moduleBean->load_relationship($workType_project_module_relation_name);
        $number_elements_module_worktype = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $moduleBean->id,
            "module_id",
            $worktypeBean->id,
            "work_id"
        );

        if($my_interval_relation_sync->external_module_id != $activity->Project_Module_Id){
            //Existing quantity of the given relation
            if($number_elements_project_module === 0){
                $moduleBean->$project_project_module_relation_name->delete($moduleBean->id, $projectBean);
            }

            if($number_elements_module_worktype === 0){
                $moduleBean->$workType_project_module_relation_name->delete($moduleBean->id, $worktypeBean);
            }

            // $this->deleteIfItIsTheLastOne($my_interval_relation_sync, "external_module_id", $moduleBean);
        }

        if($my_interval_relation_sync->external_work_id != $activity->Module_Worktype_Id){
            if($number_elements_module_worktype === 0){
                $moduleBean->$workType_project_module_relation_name->delete($moduleBean->id, $worktypeBean);
                // $this->deleteIfItIsTheLastOne($my_interval_relation_sync, "external_work_id", $worktypeBean);
            }
        }
        //////////////END DELETE CHANGED RELATIONSHIPS //////////////////////////////////////////////

        $results_fill = $this->fillMyIntervalsRelationSync($my_interval_relation_sync, $activity, $employeeBean);
        $my_interval_relation_sync = $results_fill[0];
        $account = $results_fill[1];
        $project = $results_fill[2];
        $activity = $results_fill[3];

        $my_interval_relation_sync->name = $employeeBean->name." - ".$project->name." - ".$activity->External_Id;
        $my_interval_relation_sync->save();

        //Add relationship between Project and Employee
        $employeeBean->load_relationship($employee_project);
        $index_relation_project_employee = false;
        $project_employee_relations = $employeeBean->$employee_project->get();
        if(count($project_employee_relations) > 0){
            $index_relation_project_employee = array_search($project->id, $project_employee_relations);
        }
        if(!is_numeric($index_relation_project_employee)){
            $employeeBean->$employee_project->add($project);
        }

        //Add relationship between Account and Project
        $account_project = $this->nameOfRelationshipAccountProject;
        $project->load_relationship($account_project);
        $index_relation_account_project = false;
        $account_project_relations = $project->$account_project->get();
        if(count($account_project_relations) > 0){
            $index_relation_account_project = array_search($account->id, $account_project_relations);
        }
        if(!is_numeric($index_relation_account_project)){
            $project->$account_project->add($account);
        }

        //Create Activity
        $activity->Name = $my_interval_relation_sync->name;
        (new CT_ActivityController)->updateActivityRecord($activityBean, $activity);

        //Add relationships Project and Account to intervals Bean
        $this->addRelationshipsProjectAndAccount($my_interval_relation_sync, $account, $project);

        //Return Activity ID
        $result["Activity Id"] = $activityBean->id;

        return $result;
    }

    /**
     * @param SugarBean
     * @param String
     * @return array
     */
    public function deleteMyIntervalsRelationSync(\SugarBean $employeeBean, string $externalActId)
    {
        //Get MyIntervalrelationsSync Bean
        $my_interval_relation_sync = $this->getMyIntervalsReSiByExternalActivityId($externalActId);
        $my_interval_relation_sync->is_used = 0;
        $my_interval_relation_sync->mark_deleted($my_interval_relation_sync->id);
        $my_interval_relation_sync->save();

        //Get the activity id and Activity Bean with Intervals_relation
        $activity_id = $my_interval_relation_sync->activity_id;

        if(!$activity_id) return [];

        $activityBean = Utilities::getCustomBeanById($this->CTActivity, $activity_id);
        $activityBean->mark_deleted($activityBean->id);
        $activityBean->save();


        /////////////////START DELETING RELATIONSHIPS OF ELEMENTS THAT HAVE NO RELATIONSHIP /////////////////////////////////////////////////////
        $accountBean = Utilities::getCustomBeanById($this->Account, $my_interval_relation_sync->client_id);
        $projectBean = Utilities::getCustomBeanById($this->Project, $my_interval_relation_sync->id_project);
        $moduleBean = Utilities::getCustomBeanById($this->CT_Modules, $my_interval_relation_sync->module_id);
        $worktypeBean = Utilities::getCustomBeanById($this->CT_Worktype, $my_interval_relation_sync->work_id);

        $number_elements_employee_project = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $employeeBean->id,
            "id_employee",
            $projectBean->id,
            "id_project"
        );

        $number_elements_account_project = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $accountBean->id,
            "client_id",
            $projectBean->id,
            "id_project"
        );

        $number_elements_project_module = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $moduleBean->id,
            "module_id",
            $projectBean->id,
            "id_project"
        );

        $number_elements_module_worktype = $this->GetQuantityByComparingFields(
            $this->intervalsTableName,
            $my_interval_relation_sync->id,
            $moduleBean->id,
            "module_id",
            $worktypeBean->id,
            "work_id"
        );

        if($number_elements_employee_project === 0){
            $employee_project = $this->nameOfRelationshipEmployeeProject;
            $employeeBean->load_relationship($employee_project);
            $employeeBean->$employee_project->delete($employeeBean->id, $projectBean);
        }
        if($number_elements_account_project === 0){
            $account_project = $this->nameOfRelationshipAccountProject;
            $projectBean->load_relationship($account_project);
            $projectBean->$account_project->delete($projectBean->id, $accountBean);
        }
        if($number_elements_project_module === 0) {
            $project_project_module_relation_name = $this->nameOfRelationshipProjectModule;
            $moduleBean->load_relationship($project_project_module_relation_name);
            $moduleBean->$project_project_module_relation_name->delete($moduleBean->id, $projectBean);
        }
        if($number_elements_module_worktype === 0){
            $workType_project_module_relation_name = $this->nameOfRelationshipModuleWorktype;
            $moduleBean->load_relationship($workType_project_module_relation_name);
            $moduleBean->$workType_project_module_relation_name->delete($moduleBean->id, $worktypeBean);
        }

        // $my_interval_relation_sync->mark_deleted($my_interval_relation_sync->id);
        $my_interval_relation_sync->is_used = 0;
        $my_interval_relation_sync->save();

        /////////////////END DELETING RELATIONSHIPS OF ELEMENTS THAT HAVE NO RELATIONSHIP ///////////////////
    }

    public function getMyIntervalsReSiByExternalActivityId($externalActId)
    {
        $mirs = BeanFactory::getBean(self::$customModuleName);
        $mirs = $mirs->retrieve_by_string_fields(
            array(
                "external_activity_id" => $externalActId
            )
        );
        if (is_null($mirs)) {
            throw new \InvalidArgumentException(sprintf(
                'Activity with External_Id %s not found.',
                $externalActId
            ));
        }
        return $mirs;
    }

    public function fillMyIntervalsRelationSync($my_interval_relation_sync, $activity, $employeeBean)
    {
        //Names
        $client_name = trim($activity->Account_Name);
        $project_name = trim($activity->Project);
        $module_name = trim($activity->Project_Module);
        $work_type_name = trim($activity->Module_Worktype);

        //Bring or create the client
        $account = $this->bringOrCreateBean(
            $client_name,
            $activity->Account_Id,
            "external_client_id",
            "client_id",
            $this->Account
        );

        //Bring or create the project
        $project = $this->bringOrCreateBean(
            $project_name,
            $activity->Project_Id,
            "external_project_id",
            "id_project",
            $this->Project
        );

        //Bring or create the module
        $module = $this->bringOrCreateBean(
            $module_name,
            $activity->Project_Module_Id,
            "external_module_id",
            "module_id",
            $this->CT_Modules
        );

        //Bring or create the modulo work_type
        $Worktype = $this->bringOrCreateBean(
            $work_type_name,
            $activity->Module_Worktype_Id,
            "external_work_id",
            "work_id",
            $this->CT_Worktype
        );

        //Add relationship between WorkType and Module
        $index_relation_work_module = false;
        $workType_project_module_relation_name = $this->nameOfRelationshipModuleWorktype;
        $module->load_relationship($workType_project_module_relation_name);
        $workType_module_relations = $module->$workType_project_module_relation_name->get();
        if(count($workType_module_relations) > 0){
            $index_relation_work_module = array_search($Worktype->id, $workType_module_relations);
        }
        if(!is_numeric($index_relation_work_module)){
            $module->$workType_project_module_relation_name->add($Worktype);
        }

        //Add relationship between Project and Module
        $index_relation_project_module = false;
        $project_project_module_relation_name = $this->nameOfRelationshipProjectModule;
        $module->load_relationship($project_project_module_relation_name);
        $project_module_relations = $module->$project_project_module_relation_name->get();
        if(count($project_module_relations) > 0){
            $index_relation_project_module = array_search($project->id, $project_module_relations);
        }
        if(!is_numeric($index_relation_project_module)){
            $module->$project_project_module_relation_name->add($project);
        }

        //Fill the intervals bean
        $my_interval_relation_sync->name_employee = $employeeBean->name;
        $my_interval_relation_sync->id_employee = $employeeBean->id;
        $my_interval_relation_sync->description = $activity->Description;
        $my_interval_relation_sync->external_activity_id = $activity->External_Id;
        $my_interval_relation_sync->client_name = $account->name;
        $my_interval_relation_sync->client_id = $account->id;
        $my_interval_relation_sync->external_client_id = $activity->Account_Id;
        $my_interval_relation_sync->project_name = $project->name;
        $my_interval_relation_sync->id_project = $project->id;
        $my_interval_relation_sync->external_project_id = $activity->Project_Id;
        $my_interval_relation_sync->module_name = $module->name;
        $my_interval_relation_sync->module_id = $module->id;
        $my_interval_relation_sync->external_module_id = $activity->Project_Module_Id;
        $my_interval_relation_sync->work_name = $Worktype->name;
        $my_interval_relation_sync->work_id = $Worktype->id;
        $my_interval_relation_sync->external_work_id = $activity->Module_Worktype_Id;
        $timeValue = number_format((float)$activity->Time, 2, '.', '');
        $my_interval_relation_sync->time = $timeValue;
        $my_interval_relation_sync->date_activity = $activity->Date.' 00:00:01';
        $my_interval_relation_sync->is_used = 1;

        //Fill the activity object
        $activity->Account_Id = $account->id;
        $activity->Account_Name = $account->name;
        $activity->Project_Id = $project->id;
        $activity->Project = $project->name;
        $activity->Project_Module_Id = $module->id;
        $activity->Project_Module = $module->name;
        $activity->Module_Worktype_Id = $Worktype->id;
        $activity->Module_Worktype = $Worktype->name;

        return [$my_interval_relation_sync, $account, $project, $activity];
    }

    public function addRelationshipsProjectAndAccount($my_interval_relation_sync, $account, $project)
    {
        $account_relation_name = $this->nameOfRelationshipIntervalsAccounts;
        $my_interval_relation_sync->load_relationship($account_relation_name);
        $my_interval_relation_sync->$account_relation_name->add($account);

        $project_relation_name = $this->nameOfRelationshipIntervalsProject;
        $project->load_relationship($project_relation_name);
        $project->$project_relation_name->add($my_interval_relation_sync);
    }

    public function bringOrCreateBean($element_name, $element_external_id, $element_external_field, $element_package_field_id, $package)
    {
        $beanUsed = BeanFactory::getBean(self::$customModuleName);
        $existBean = BeanFactory::getBean($package);

        $beanUsed = $beanUsed->retrieve_by_string_fields(
            array(
                $element_external_field => $element_external_id
            )
        );

        //Evalueta if exist el Bean element and if this Bean element is used in
        //the relation with Intervals relation
        if(!is_null($beanUsed)){
            $existBean = BeanFactory::getBean($package, $beanUsed->$element_package_field_id);
        }else{
            $existBean = BeanFactory::getBean($package);
            $existBean->name = $element_name;
            $existBean->save();
        }

        return $existBean;
    }

    public function deleteIfItIsTheLastOne($interval_realtion, $external_element_field_name, $bean_element)
    {
        $external_element_id = $interval_realtion->$external_element_field_name;
        $how_many_are_there = $this->howManyExternalIdAreThere($interval_realtion->id, $external_element_id, $external_element_field_name);
        if(!$how_many_are_there){
            $bean_element->mark_deleted($bean_element->id);
            // $bean_element->save();
        }
    }

    public function GetQuantityByComparingFields($table_name, $intervals_id, $firts_id, $firts_field_name, $second_id, $second_field_name)
    {
        $sql = "SELECT  COUNT(id) AS actives
            FROM ".$table_name." cir
            WHERE cir.id!='".$intervals_id."' AND cir.".$firts_field_name."='".$firts_id."' AND 
            cir.".$second_field_name."='".$second_id."' AND cir.deleted!=1";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $result = [];
        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row["actives"];
        }
        return intval($result[0]);
    }

    public function howManyExternalIdAreThere($interval_id, $external_id, $external_field_name)
    {
        $sql = "SELECT  COUNT(id) AS actives
            FROM ".$this->intervalsTableName." cir
            WHERE cir.id != '".$interval_id."' AND cir.".$external_field_name."='".$external_id."' AND cir.deleted!=1";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $result = [];
        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row["actives"];
        }
        return intval($result[0]);
    }

}