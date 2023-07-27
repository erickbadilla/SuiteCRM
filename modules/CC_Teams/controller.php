<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('custom/application/Ext/Api/V8/Service/InsideODBC/InsideConnect.php');
require_once 'custom/Extension/application/Include/inside_teams_sync.php';
class CC_TeamsController extends SugarController {

    private $kanbanTypes = [
        "kanban-type"=> "Projects",
        "project-manager-type" => "Project Manager",
        "technical-lead-type" => "Technical Lead"
    ];

    private $queryGetProjectTeamMembers =
        "SELECT er.id as row_id, pr.id as project_id, ei.id as employee_id," .
        " er.start_date, er.end_date, er.description, er.load_capacity, " .
        " pr.name as project_name, ei.name as employee_name, ei.cc_job_description_id_c as cc_job_description_id, ".
        " (SELECT name from cc_job_description jd WHERE jd.id = ei.cc_job_description_id_c) as employee_position, ".
        "  ei.is_assigned, er.role ".
        " FROM cc_employee_information_project_c er ".
        " LEFT JOIN cc_employee_information ei ON er.cc_employee_information_projectcc_employee_information_ida=ei.id ".
        " LEFT JOIN project pr ON er.cc_employee_information_projectproject_idb=pr.id ".
        " WHERE (er.end_date is null or er.end_date = '0000-00-00 00:00:00' or er.end_date>=now()) ".
        " AND er.deleted = 0 and ei.deleted = 0 and pr.deleted = 0 and ei.active=1 ";

    private $queryGetAllocationRow =
        "SELECT id, date_modified, deleted, ".
        " cc_employee_information_projectcc_employee_information_ida as employee_id, ".
        " cc_employee_information_projectproject_idb as project_id, ".
        " start_date, end_date, description, role FROM cc_employee_information_project_c ";

    private $queryProjectModules =
        "SELECT DISTINCT pm.id, pm.name ".
        " FROM ct_project_modules pm ".
        " INNER JOIN ct_project_modules_project_c pmp ON pm.id = pmp.ct_project_modules_projectct_project_modules_idb ".
        " WHERE pm.deleted=0 AND pmp.ct_project_modules_projectproject_ida = '%s' ".
        " ORDER BY pm.name ";

    private $queryProjectWorktypes =
        "SELECT DISTINCT wt.id,wt.name,wt.description ".
        " from ct_modules_worktype wt inner join ct_modules_worktype_ct_project_modules_c wtp ".
        " on wt.id = wtp.ct_modules_worktype_ct_project_modulesct_modules_worktype_idb ".
        " inner join ct_project_modules pm on pm.id = ct_modules_worktype_ct_project_modulesct_project_modules_ida ".
        " WHERE wt.deleted = 0 AND wtp.ct_modules_worktype_ct_project_modulesct_project_modules_ida in (%s)".
        " ORDER BY wt.name";

    private $getTechnicalLeadsJobDescritionIDS =
        "SELECT DISTINCT id,name ".
        " from cc_job_description where LOWER( name ) LIKE '%tech lead%' OR relate_role='tech_lead' ".
        " and deleted = 0;";

    private $getProjectManagerJobDescritionIDS =
        "SELECT DISTINCT id,name ".
        " from cc_job_description where LOWER( name ) LIKE '%project manager%' OR relate_role='project_manager' ".
        " and deleted = 0;";

    private $getManagementGroup =
        "SELECT teams.id row_id, teams.cc_employee_information_id_c as employee_id, ".
        " (SELECT name from cc_job_description jd WHERE jd.id = employee.cc_job_description_id_c) as employee_position, ".
        " employee.name as employee_name, employee.person_id as employee_inside_id,".
        " teams.cc_employee_information_id1_c as manager_id, manager.name as manager_name, ".
        " manager.person_id as manager_inside_id FROM cc_teams teams ".
        "   JOIN cc_employee_information employee on teams.cc_employee_information_id_c=employee.id ".
        "   JOIN cc_employee_information manager on teams.cc_employee_information_id1_c=manager.id ".
        " WHERE employee.person_id is not null AND manager.person_id is not null AND teams.deleted=0 AND ".
        " employee.active=1 AND manager.active=1 AND manager.deleted=0 AND employee.deleted=0 AND teams.deleted=0 AND ".
        " teams.cc_employee_information_id1_c IN (%s)";

    public function __construct(){
        parent::__construct();
    }

    public function getKanbanTypes()
    {
        return $this->kanbanTypes;
    }

    public function getTeams(){
        $teamsBean = BeanFactory::getBean('CC_Teams');
        $teamsList = $teamsBean->get_full_list("name");
        $teams = [];
        foreach($teamsList as $key => $item){
            $teams[] = (object) [
                'name'             => $teamsList[$key]->name,
                'date_entered'     => $teamsList[$key]->date_entered,
                'user_related'     => $teamsList[$key]->user_related,
                'assigned_user_id' => $teamsList[$key]->assigned_user_id
            ];
        }
        return $teams;
    }

    public function getProjectsModules($project_id){
        $db = DBManagerFactory::getInstance();
        $rows = $db->query(sprintf($this->queryProjectModules,$project_id));
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[$row['id']] = (object)[
                "id" => $row['id'],
                "name" => $row['name']
            ];
        }
        return $result;
    }

    public function getProjectWorktypes($modules_ids){
        $db = DBManagerFactory::getInstance();
        $query = sprintf($this->queryProjectWorktypes,"'".implode("', '", $modules_ids)."'");
        $rows = $db->query($query);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[$row['id']] = (object) [
                "id" => $row['id'],
                "name" => $row['name'],
                "description" => $row['description'],
            ];
        }
        return $result;
    }

    public function getProject($project_id){
        return BeanFactory::getBean('Project', $project_id);
    }

    public function getProjects(){
        $projectBean =BeanFactory::getBean('Project');
        $projectList = $projectBean->get_full_list("name");
        $projects = [];
        $count = 0;
        foreach($projectList as $item){
            $projects[$item->id] = (object) [
                'id'         => $item->id,
                'name'       => $item->name,
                'status'     => $item->status,
                'stageorder' => $count
            ];
            $count += 1;
        }
        return $projects;
    }

    public function getEmployeeList(){
        $employeeBean =BeanFactory::getBean('CC_Employee_Information');
        $employeeList = $employeeBean->get_full_list("name", " cc_employee_information.active=1 ");
        $employees = [];
        foreach($employeeList as $item){
            $employees[$item->id] = $item->name;
        }
        return $employees;
    }

    public function getProjectAllocation(){
        $db = DBManagerFactory::getInstance();
        $rows = $db->query($this->queryGetProjectTeamMembers);
        $styles = [ "Base", "Average", "Mid", "Full" ];
        $base_style_name = "LoadCapacity";
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $sty_index = intval($row['load_capacity']*4/100);
            $style = $base_style_name.$styles[$sty_index];
            $result[] = (object) [
                "row_id" => $row['row_id'],
                "project_id" => $row['project_id'],
                "employee_id" => $row['employee_id'],
                "start_date" => $row['start_date'],
                "end_date" => $row['end_date'],
                "description" => $row['description'],
                "project_name" => $row['project_name'],
                "employee_name" => $row['employee_name'],
                "cc_job_description_id" => $row['cc_job_description_id'],
                "employee_position_name" => $row['employee_position'],
                "is_assigned" => $row['is_assigned'],
                "load_capacity" => $row['load_capacity'],
                "style" => $style,
                "role" => $row['role']
            ];
        }
        return $result;
    }

    public function getManagerAllocation($managers){
        $db = DBManagerFactory::getInstance();
        $query = sprintf($this->getManagementGroup,"'".implode("', '", array_keys($managers))."'");
        $rows = $db->query($query);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[] = (object) [
                "row_id" => $row['row_id'],
                "employee_id" => $row['employee_id'],
                "employee_name" => $row['employee_name'],
                "employee_position_name" => $row['employee_position'],
                "employee_inside_id" => $row['employee_inside_id'],
                "manager_id" => $row['manager_id'],
                "manager_name" => $row['manager_name'],
                "manager_inside_id" => $row['manager_inside_id'],
            ];
        }
        return $result;
    }

    public function getJobDescriptionRelatedToPMs(){
        $db = DBManagerFactory::getInstance();
        $rows = $db->query($this->getProjectManagerJobDescritionIDS);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[$row['id']] = (object) [
                "id" => $row['id'],
                "name" => $row['name'],
            ];
        }
        return $result;
    }

    public function getJobDescriptionRelatedToTLs(){
        $db = DBManagerFactory::getInstance();
        $rows = $db->query($this->getTechnicalLeadsJobDescritionIDS);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[$row['id']] = (object) [
                "id" => $row['id'],
                "name" => $row['name']
            ];
        }
        return $result;
    }


    public function getProjectManagersList(){
        $employeeBean =BeanFactory::getBean('CC_Employee_Information');
        $group_arr = $this->getJobDescriptionRelatedToPMs();
        $group_qry = " IN ('".implode("', '", array_keys($group_arr))."')";
        $where = sprintf(
            " cc_employee_information.cc_job_description_id_c %s AND cc_employee_information.active=1 ",
            $group_qry);
        $employeeList = $employeeBean->get_full_list("name", $where);
        $employees = [];
        foreach($employeeList as $item){
            $employees[$item->id] = (object) ["id" => $item->id, "name" => $item->name];
        }
        return $employees;
    }

    public function getTechLeadList(){
        $employeeBean =BeanFactory::getBean('CC_Employee_Information');
        $group_arr = $this->getJobDescriptionRelatedToTLs();
        $group_qry = " IN ('".implode("', '", array_keys($group_arr))."')";
        $where = sprintf(
            " cc_employee_information.cc_job_description_id_c %s AND cc_employee_information.active=1 ",
            $group_qry);
        $employeeList = $employeeBean->get_full_list("name", $where);
        $employees = [];
        foreach($employeeList as $item){
            $employees[$item->id] = (object) ["id" => $item->id, "name" => $item->name];
        }
        return $employees;
    }

    /**
     * @param SugarBean $employee_bean
     * @return User
     */
    private function getCRMUserAssociated(SugarBean $employee_bean){
        $crm_user = new User();
        $crm_user->retrieve_by_email_address($employee_bean->current_email);
        return $crm_user;
    }

    private function saveCRMTeamsData($record_id=null,SugarBean $employee_bean, SugarBean $target_manager){
        global $current_user;
        $crm_user = $this->getCRMUserAssociated($target_manager);

        if(is_null($record_id)){
            $team_bean = BeanFactory::newBean("CC_Teams");
            $team_bean->created_by = $current_user->id;
        } else{
            $team_bean = BeanFactory::getBean("CC_Teams",$record_id);
        }

        if(!$team_bean){
            return false;
        }

        $team_bean->modified_user_id = $current_user->id;
        $team_bean->name = $employee_bean->name." - ".$target_manager->name;
        $team_bean->description = $employee_bean->name." - ".$target_manager->name;
        $team_bean->assigned_user_id = 1;
        $team_bean->cc_employee_information_id_c = $employee_bean->id;
        $team_bean->cc_employee_information_id1_c = $target_manager->id;
        $team_bean->user_id_c = $crm_user->id;

        return $team_bean->save();
    }

    /**
     * @param SugarBean $team_record
     * @return string
     */
    private function deleteCRMTeamsData(SugarBean $team_record){
        $team_record->mark_deleted($team_record->id);
        return $team_record->save();
    }

    /**
     * @param SugarBean $employee
     * @param SugarBean $manager
     * @return mixed
     */
    private function createInsideTeamsRecord(SugarBean $employee,SugarBean $manager){
        if(empty($employee->person_id) || empty($manager->person_id)){
            return false;
        }

        $instance = new inside_teams_sync();
        $exist = $instance->lookUpForRelation($employee->person_id,$manager->person_id);
        if(count($exist)===0){
            return $instance->insertNewRelation($employee->person_id,$manager->person_id);
        }
        return true;
    }

    /**
     * @param SugarBean $employee
     * @param SugarBean $manager
     * @return mixed
     */
    private function updateInsideTeamsRecord(SugarBean $employee,SugarBean $manager){
        if(empty($employee->person_id) || empty($manager->person_id)){
            return false;
        }

        $instance = new inside_teams_sync();
        $exist = $instance->lookUpForRelation($employee->person_id,$manager->person_id);
        if(count($exist)===0) {
            return false;
        }
        return $instance->updateRelation($exist,$employee->person_id);
    }

    /**
     * @param $employee
     * @param $manager
     * @return bool
     */
    public function deleteInsideTeamsRecord($employee,$manager){
        if(empty($employee->person_id) || empty($manager->person_id)){
            return false;
        }
        $instance = new inside_teams_sync();
        $exist = $instance->lookUpForRelation($employee->person_id,$manager->person_id);
        if(count($exist)===0) {
            return false;
        }
        return $instance->deleteRelation($exist);

    }


    /**
     * @param SugarBean $employee_bean
     * @param SugarBean $target_manager
     * @return array
     */
    public function createTeamRecord(SugarBean $employee_bean, SugarBean $target_manager){

        $result_crm = $this->saveCRMTeamsData(null,$employee_bean,$target_manager);
        $result_sql = $this->createInsideTeamsRecord($employee_bean,$target_manager);
        return [
            "crm" => $result_crm,
            "inside" => $result_sql
        ];

    }

    /**
     * @param SugarBean $team_record
     * @param SugarBean $employee_bean
     * @param SugarBean $target_manager
     * @return array
     */
    public function updateTeamRecord(SugarBean $team_record,SugarBean $employee_bean,SugarBean  $target_manager){

        $result_crm = $this->saveCRMTeamsData($team_record->id,$employee_bean,$target_manager);
        $result_sql = $this->updateInsideTeamsRecord($employee_bean,$target_manager);
        return [
            "crm" => $result_crm,
            "inside" => $result_sql
        ];
    }

    /**
     * @param SugarBean $team_record
     * @param SugarBean $employee_bean
     * @param SugarBean $target_manager
     * @return array
     */
    public function deleteTeamRecord(SugarBean $team_record,SugarBean $employee_bean,SugarBean  $target_manager){

        $result_crm = $this->deleteCRMTeamsData($team_record);
        $result_sql = $this->deleteInsideTeamsRecord($employee_bean,$target_manager);
        return [
            "crm" => $result_crm,
            "inside" => $result_sql
        ];
    }
}