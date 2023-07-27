<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/Currencies/Currency.php';
require_once "custom/Extension/application/Include/jqGrid/jqGrid.php";
require_once "custom/Extension/application/Include/jqGrid/jqGridPdo.php";
use Api\V8\Utilities;
use Api\V8\Config\Common as Common;

class CT_ActivityController extends SugarController {

    public function __construct(){
        parent::__construct();
        self::createActivityView();
    }

    public function createActivityView()
    {
        $sql = "create or replace view ct_activity_view as
                select `a`.`id`               AS `id`,
                       `ir`.`id_employee`     AS `id_employee`,
                       `ir`.`name_employee`   AS `name`,
                       `p`.`name`             AS `project_name`,
                       `a`.`description`      AS `description`,
                       `a`.`is_billable`      AS `is_billable`,
                       `a`.`activity_time`    AS `activity_time`,
                       `a`.`activity_date`    AS `activity_date`,
                       `pm`.`name`            AS `module_name`,
                       `wt`.`name`            AS `worktype_name`,
                       `a`.`date_entered`     AS `date_entered`,
                       `a`.`date_modified`    AS `date_modified`,
                       `a`.`modified_user_id` AS `modified_user_id`,
                       `a`.`created_by`       AS `created_by`,
                       `a`.`deleted`          AS `deleted`,
                       `a`.`assigned_user_id` AS `assigned_user_id`,
                       `a`.`project_id_c`     AS `project_id_c`,
                       `a`.`project_module`   AS `project_module`,
                       `a`.`module_worktype`  AS `module_worktype`,
                       `a`.`external_id`      AS `external_id`
                from ((((`ct_activity` `a` join `project` `p` on (`a`.`project_id_c` = `p`.`id`)) join `ct_project_modules` `pm` on (`a`.`project_module` = `pm`.`id`)) join `ct_modules_worktype` `wt` on (`a`.`module_worktype` = `wt`.`id`))
                join `ct_intervals_relation` `ir` on (`a`.`external_id` = `ir`.`external_activity_id`))
                where `a`.`deleted` = 0;";
        $db = DBManagerFactory::getInstance();
        $db->query($sql);
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CT_ActivityController() {
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
     * @return array
     */
    public function getRecordsByEmployeeId(\SugarBean $parentBean) {
        $eaRelation = 'ct_activity_cc_employee_information';

        $parentBean->load_relationship($eaRelation);
        $activitiesArray = $parentBean->$eaRelation->get();

        foreach ($activitiesArray as $activityId){

            $activityBean = Utilities::getCustomBeanById('CT_Activity', $activityId);
            $ProjectBean = Utilities::getCustomBeanById('Project', $activityBean->project_id_c);
            $PMBean = Utilities::getCustomBeanById('CT_Project_Modules',$activityBean->project_module);
            $MWBean = Utilities::getCustomBeanById('CT_Modules_Worktype', $activityBean->module_worktype);
            $activity['Id'] = $activityBean->id;
            $activity['Name'] = $activityBean->name;
            $activity['Employee'] = $parentBean->name;
            $activity['Date'] = $activityBean->activity_date;
            $activity['Project'] = $ProjectBean->name;
            $activity['Project_Module'] = $PMBean->name;
            $activity['Module_Worktype'] = $MWBean->name;
            $activity['Time'] = $activityBean->activity_time;
            $activity['Is_Billable'] = $activityBean->is_billable;
            $activity['Description'] = $activityBean->description;
            $activity['External_Id'] = $activityBean->external_id;

            $result[] = $activity;
        }

        return $result;
    }

    /**
     *
     * @param string $projectId
     * @return array
     */
    public function getRecordsByProjectId(string $projectId) {
        $eaRelation = 'ct_activity_cc_employee_information';

        $sql = "SELECT act.id 'Id', act.name 'Name', act.activity_date 'date',
     act.project_module 'Project Module', act.module_worktype 'Module Worktype',act.activity_time 'Time',
     act.is_billable 'Is Billable',act.description 'Description',act.external_id'External ID'
      FROM ".Common::$activityTable." act
      WHERE act.deleted = 0 AND act.project_id_c = '".$projectId."' ";

        $sql .= " ORDER BY act.name";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $PMBean = Utilities::getCustomBeanById('CT_Project_Modules',$row['Project Module']);
            $MWBean = Utilities::getCustomBeanById('CT_Modules_Worktype', $row['Module Worktype']);

            $activityBean = Utilities::getCustomBeanById('CT_Activity', $row['Id']);
            $activityBean->load_relationship($eaRelation);
            $employeeId = $activityBean->$eaRelation->get();

            if($employeeId){
                $employeeBean = Utilities::getCustomBeanById('CC_Employee_Information', $employeeId[0]);
            }

            $row['Employee Name'] =  $employeeId? $employeeBean->name : '';
            $row['Employee Id'] = $employeeId? $employeeBean->id: '';
            $row['Project Module'] = $PMBean->name;
            $row['Module Worktype'] = $MWBean->name;

            $result[] = $row;
        }
        return $result;
    }

    /**
     * @param SugarBean
     * @param object $Activity
     * @return array
     */
    public function saveActivityRecord(\SugarBean $parentBean, object $activity){
        $this->validateActivity($activity);
        $eaRelation = 'ct_activity_cc_employee_information';

        $parentBean->load_relationship($eaRelation);
        $activityBean = BeanFactory::newBean('CT_Activity');

        $activityBean = $this -> fillActivityBean($activityBean, $activity);

        $activityId = $activityBean->save();
        if($activityId){
            if(($parentBean instanceof \SugarBean) && property_exists($parentBean,$eaRelation)){
                $parentBean->$eaRelation->add($activityBean);
            }else{
                $log = LoggerManager::getLogger();
                $log->error('Unable to create relation for: '.$activityId." on :".json_encode($parentBean));
            }
        }

        $result['Activity Id'] = $activityId;

        return $result;
    }

    /**
     * @param SugarBean
     * @param object $Activity
     * @return array
     */
    public function updateActivityRecord(\SugarBean $activityBean, object $activity){

        $this->validateActivity($activity);
        $activityBean = $this -> fillActivityBean($activityBean, $activity);
        $activityBean->save();

        $eaRelation = 'ct_activity_cc_employee_information';
        $employeeId = $activityBean->$eaRelation->get();
        if($employeeId){
            $employeeBean = Utilities::getCustomBeanById('CC_Employee_Information', $employeeId[0]);
        }
        $ProjectBean = Utilities::getCustomBeanById('Project', $activityBean->project_id_c);
        $PMBean = Utilities::getCustomBeanById('CT_Project_Modules',$activityBean->project_module);
        $MWBean = Utilities::getCustomBeanById('CT_Modules_Worktype', $activityBean->module_worktype);

        $result['Id'] = $activityBean->id;
        $result['Name'] = $activityBean->name;
        $result['Employee'] = $employeeBean->name;
        $result['Date'] = $activityBean->activity_date;
        $result['Project'] = $ProjectBean->name;
        $result['Project_Module'] = $PMBean->name;
        $result['Module_Worktype'] = $MWBean->name;
        $result['Time'] = $activityBean->activity_time;
        $result['Is_Billable'] = $activityBean->is_billable;
        $result['Description'] = $activityBean->description;
        $result['External_Id'] = $activityBean->external_id;

        return $result;
    }

    /**
     * @param SugarBean $activityBean
     * @return SugarBean
     */
    public function deleteActivityRecord(\SugarBean $activityBean) {

        $activityBean->mark_deleted($activityBean->id);
        $activityBean->save();

        return $activityBean;
    }

    /**
     * Fill the information of the Activity Bean
     * @param SugarBean
     * @param object $Activity
     * @return SugarBean
     */
    public function fillActivityBean(\SugarBean $activityBean, object $activity){
        $activityBean->name = $activity->Name;
        $activityBean->activity_date = $activity->Date;
        $activityBean->activity_time = $activity->Time;
        $activityBean->is_billable = $activity->Is_Billable;
        $activityBean->description = $activity->Description;
        $activityBean->external_id = $activity->External_Id;

        $projectId = $activity->Project_Id;
        $projectModuleId = $activity->Project_Module_Id;
        $moduleWorktypeId = $activity->Module_Worktype_Id;

        $this -> validateProjectSubModules($projectId, $projectModuleId, $moduleWorktypeId);

        $activityBean->project_id_c = $projectId;
        $activityBean->project_module = $projectModuleId;
        $activityBean->module_worktype = $moduleWorktypeId;

        return $activityBean;
    }

    /**
     * Validate if the projectModule and moduleWorktype
     * are related to the project.
     * @param string $projectId
     * @param string $projectModuleId
     * @param string $moduleWorktypeId
     */
    public function validateProjectSubModules($projectId, $projectModuleId, $moduleWorktypeId){
        $ppmRelation = 'ct_project_modules_project';
        $pmmwRelation = 'ct_modules_worktype_ct_project_modules';

        $ProjectBean = Utilities::getCustomBeanById('Project',$projectId);

        if($ProjectBean) {
            if(!Utilities::validateRelation($ppmRelation, $ProjectBean, $projectModuleId)){
                throw new \InvalidArgumentException('Project Module doesn\'t exist in the Project');
            }
        } else {
            throw new \InvalidArgumentException('Project doesn\'t exist');
        }

        $PMBean = Utilities::getCustomBeanById('CT_Project_Modules',$projectModuleId);

        if(!Utilities::validateRelation($pmmwRelation, $PMBean, $moduleWorktypeId)){
            throw new \InvalidArgumentException('Worktype doesn\'t exist in the Project Module');
        }

    }


    /**
     * Validate if the request has the required fields
     * @param object $activity
     */
    public function validateActivity(object $activity){
        if (is_null($activity->Name)) {
            throw new \InvalidArgumentException('Name is required');
        }

        if (is_null($activity->Date)) {
            throw new \InvalidArgumentException('Date is required');
        }

        if (is_null($activity->Time)) {
            throw new \InvalidArgumentException('Time is required');
        }

        if (is_null($activity->Project_Id)) {
            throw new \InvalidArgumentException('Project is required');
        }

        if (is_null($activity->Project_Module_Id)) {
            throw new \InvalidArgumentException('Project Module is required');
        }

        if (is_null($activity->Module_Worktype_Id)) {
            throw new \InvalidArgumentException('Module Worktype is required');
        }
    }

    public static function getProjectsNames(){
        $projectsBean = BeanFactory::getBean('Project');
        $projectList = $projectsBean->get_full_list("name");
        if(is_array($projectList)){
            $result = [];
            foreach ($projectList as $projectItem){
                $result[] = $projectItem->name.':'.$projectItem->name;
            }
            return $result;
        }
        return [];
    }

    public static function getOptionsProjects(){
        $projectsBean = BeanFactory::getBean('Project');
        $projectList = $projectsBean->get_full_list("name");
        if(is_array($projectList)){
            $result = [];
            foreach ($projectList as $projectItem){
                $result[$projectItem->id] = $projectItem->name;
            }
            return $result;
        }
        return [];
    }

    public function validateDate($date, $default, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date){
            return $date;
        } else {
            return $default;
        }
    }

    public function getActivities(){}

    public function GetActivityProjects(){
        $week_before = strtotime("-4 week");
        $dateStart = date('Y-m-d', $week_before);
        $dateEnd = date('Y-m-d');

        $default = array("options" => array(
            "default" => null
        ));

        $from = filter_input(INPUT_COOKIE, 'careers_activity_from', FILTER_DEFAULT, $default);
        $to = filter_input(INPUT_COOKIE, 'careers_activity_to', FILTER_DEFAULT, $default);

        $dateFrom = $this->validateDate($from,$dateStart);
        $dateTo = $this->validateDate($to,$dateEnd);

        $where = " WHERE activity_date BETWEEN '{$dateFrom}' and '{$dateTo}' ";

        $customQuery = "SELECT 
                project_name as name, module_name as module,worktype_name as worktype, sum(activity_time) as total_hours, 
                project_id_c as id,project_module as module_id, module_worktype as worktype_id FROM
                ct_activity_view {$where} GROUP BY project_id_c,project_module,module_worktype ORDER BY project_name, module_name, worktype_name";

        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($customQuery);

        // Initialize an array with the results
        $result = [];
        // Fetch the row
        $aPr = '';
        $total = 0;

        while ($item = $db->fetchRow($rows)) {
            $row = (object) $item;
            $value = $row->total_hours + 0;
            if($aPr != $row->id){
                $aPr = $row->id;
                $aMo = $row->module_id;
                $result[$aPr] = [];
                $result[$aPr][$row->module_id] = [];
                $result[$aPr][$row->module_id][$row->worktype_id]=[];
                $total = 0;
                $mTotal = 0;
            }

            if($aMo==$row->project_module){
                $mTotal = $mTotal + $value;
            } else {
                $mTotal = $value;
                $aMo = $row->module_id;
            }

            $result[$aPr][$row->module_id]['total'] = $mTotal;
            $result[$aPr][$row->module_id]['name'] = $row->module;

            $total = $total + $value;

            $result[$aPr][$row->module_id][$row->worktype_id]['total'] = $result[$aPr][$row->module_id][$row->worktype_id]['total']+$value;
            $result[$aPr][$row->module_id][$row->worktype_id]['name'] = $row->worktype;

            $result[$aPr]['total'] = $total;
            $result[$aPr]['name'] = $row->name;
        }
        return json_encode((array) $result);
    }

    private function getBeanListMap(array $bean_list){
        $result = [];
        foreach ($bean_list as $item){
            $result[$item->id] = $item;
        }
        return $result;
    }

    public function getProjectsList(){
        $project_bean = BeanFactory::getBean('Project');
        $project_list = $project_bean->get_full_list("name");
        return $this->getBeanListMap($project_list);
    }

    private function getProjectModules($activity_list){
        $project_modules = [];
        if(!empty($activity_list)){
            $db = DBManagerFactory::getInstance();
            $where = " id IN ('".implode("', '", $activity_list)."')";
            $query = sprintf("SELECT project_module, ".
                "( SELECT name FROM ct_project_modules WHERE id = project_module ) as project_module_name,  ".
                "SUM(CASE WHEN is_billable = true THEN activity_time ELSE 0 END) as billable, ".
                "SUM(CASE WHEN is_billable = false THEN activity_time ELSE 0 END) as non_billable, ".
                "SUM(CASE WHEN is_billable = true AND aos_invoices_id_c is not null THEN activity_time ELSE 0 END) as invoiced, ".
                "SUM(CASE WHEN is_billable = false AND aos_invoices_id_c is null THEN activity_time ELSE 0 END) as pending, ".
                "SUM(activity_time) as total ".
                " FROM ct_activity WHERE %s and deleted = 0 GROUP by project_module",$where);
            $result = $db->query($query);
            while ($row = $db->fetchRow($result)){
                $project_modules[$row['project_module']]= array(
                    "name" => $row['project_module_name'],
                    "billable" => $row['billable'],
                    "non_billable" => $row['non_billable'],
                    "invoiced" => $row['invoiced'],
                    "pending" => $row['pending'],
                    "total" => $row['total'],
                );
            }
        }
        return $project_modules;
    }

    public function getWorkTypesRates(array $workTypeIds){
        $available_rates = [];

        if(count($workTypeIds)>0){
            $currency_bean = new Currency();
            $db_instance = DBManagerFactory::getInstance();
            $filter_worktypes = "'".implode("', '", $workTypeIds)."'";
            $query_related_worktype_rates =
                "SELECT ct_modules_worktype_id_c as id,cost,currency_id ".
                "FROM ci_worktype_rates WHERE ct_modules_worktype_id_c IN (%s) and deleted = 0 ";
            $filtered_query = sprintf($query_related_worktype_rates,$filter_worktypes);
            $result_worktypes_rates = $db_instance->query($filtered_query);
            while ($row_rates = $db_instance->fetchRow($result_worktypes_rates)){
                $currency_bean->retrieve($row_rates['currency_id']);
                $available_rates[$row_rates['id']] = $currency_bean->convertToDollar($row_rates['cost']);
            }
        }

        return $available_rates;
    }
    private function getModulesWorktype($activity_list){
        global $current_user;
        $modules_worktype = [];
        if(!empty($activity_list)){
            $db = DBManagerFactory::getInstance();
            $where = " id IN ('".implode("', '", $activity_list)."')";
            $query = sprintf("SELECT module_worktype,".
                " ( SELECT name FROM ct_modules_worktype WHERE id = module_worktype ) as worktype_name, ".
                "SUM(CASE WHEN is_billable = true THEN activity_time ELSE 0 END) as billable, ".
                "SUM(CASE WHEN is_billable = false THEN activity_time ELSE 0 END) as non_billable, ".
                "SUM(CASE WHEN is_billable = true AND aos_invoices_id_c is not null THEN activity_time ELSE 0 END) as invoiced, ".
                "SUM(CASE WHEN is_billable = false AND aos_invoices_id_c is null THEN activity_time ELSE 0 END) as pending, ".
                "SUM(activity_time) as total ".
                " FROM ct_activity WHERE %s and deleted = 0 GROUP by module_worktype",$where);
            $result = $db->query($query);
            while ($row = $db->fetchRow($result)){
                $modules_worktype[$row['module_worktype']]= array(
                    "id" => $row['module_worktype'],
                    "name" => $row['worktype_name'],
                    "billable" => $row['billable'],
                    "non_billable" => $row['non_billable'],
                    "invoiced" => $row['invoiced'],
                    "pending" => $row['pending'],
                    "total" => $row['total'],
                );
            }
        }

        $userHasRateAccess = ACLController::checkAccess('CI_WorkType_Rates', 'view', $current_user->id);
        if( $userHasRateAccess){
            $rates = $this->getWorkTypesRates(array_keys($modules_worktype));
            foreach ($modules_worktype as $key => $value){
                if(key_exists($key,$rates)){
                    $modules_worktype[$key]['cost']= $rates[$key];
                }
            }
        }

        return $modules_worktype;
    }

    public function getEmployeeActivities($activity_arr){
        $result = [];
        if (!empty($activity_arr)){
            $activity_bean = BeanFactory::getBean('CT_Activity');
            $where_activity = " ct_activity.id IN ('".implode("', '", $activity_arr)."')";
            $activity_list = $activity_bean->get_full_list("name", $where_activity);
            foreach ($activity_list as $activity){
                $result[] = (object) array(
                    'id' => $activity->id,
                    'activity_date' => $activity->activity_date,
                    'description' => $activity->description,
                    'score' => 0,
                    'activity_time' => $activity->activity_time,
                    'is_billable' => $activity->is_billable,
                    'module_worktype' => $activity->module_worktype,
                    'project_module' => $activity->project_module,
                    'invoiced' => $activity->aos_invoices_id_c,
                );
            }
        }
        return $result;
    }
    public function reportedEmployees($project_id, $start, $end){
        global $current_user;
        $total_billable_time = 0;
        $total_non_billable_time = 0;
        $total_invoiced_time = 0;
        $total_pending_time = 0;
        $total_activities = 0;
        $related_activities = [];

        $db = DBManagerFactory::getInstance();
        $query_activity = sprintf("SELECT id ".
            " FROM ct_activity WHERE project_id_c = '%s' AND activity_date BETWEEN  '%s' and '%s' and deleted = 0 ".
            " ",$project_id,$start,$end);
        $result_activity = $db->query($query_activity);
        $activity_arr = [];
        while ($row = $db->fetchRow($result_activity)){
            $activity_arr[] =$row['id'];
        }
        // Prevent list all employees if there are no activities loaded
        $where_activity = '1=0';
        if (!empty($activity_arr)){
            $total_activities = count($activity_arr);
            $related_activities = $activity_arr;
            $where_activity = " ct_activity_cc_employee_informationct_activity_idb IN ('".implode("', '", $activity_arr)."')";
        }

        $db = DBManagerFactory::getInstance();
        $sub_queries = DBManagerFactory::getInstance();
        $query = sprintf(
            "SELECT ct_activity_cc_employee_informationcc_employee_information_ida as employee_id, ".
            " ct_activity_cc_employee_informationct_activity_idb as activity_id  ".
            " FROM ct_activity_cc_employee_information_c WHERE %s and deleted = 0 ".
            " ",$where_activity);
        $result = $db->query($query);
        $employees = [];
        $employees_ids =[];
        while ($row = $db->fetchRow($result)){
            if(!key_exists($row['employee_id'],$employees)){
                $employees[$row['employee_id']] = [];
            }
            $employees[$row['employee_id']][] = $row['activity_id'];
        }

        $base_select = " SELECT ROUND(COALESCE(SUM(activity_time), 0),2) as ";
        foreach ($employees as $key => $activity_ids){
            $employee_id = $key;
            $employee_activity_details = [
                "billable_time" =>0,
                "non_billable_time" =>0,
                "invoiced_time" => 0,
                "related_worktypes" => [],
                "pending_time" => 0,
            ];
            if (!empty($activity_ids)){
                $query_billable_time = $base_select." billable_time from ct_activity WHERE is_billable = 1 AND ";
                $query_non_billable_time = $base_select." non_billable_time from ct_activity WHERE is_billable = 0 AND ";
                $query_invoiced_time = $base_select." invoiced_time from ct_activity WHERE aos_invoices_id_c is not null AND";
                $query_pending_time = $base_select." pending_time from ct_activity WHERE aos_invoices_id_c is null AND is_billable = 1 AND";
                $query_related_worktype = "SELECT GROUP_CONCAT(DISTINCT module_worktype) as worktypes FROM ct_activity WHERE ";
                $employee_activity_where = " ct_activity.id IN ('".implode("', '", $activity_ids)."') and deleted = 0";

                $result_billable_time = $sub_queries->query($query_billable_time.$employee_activity_where);
                $row_billable_time = $sub_queries->fetchRow($result_billable_time);
                $employee_activity_details["billable_time"] = $row_billable_time['billable_time'];
                $total_billable_time = $total_billable_time + $employee_activity_details["billable_time"];

                $result_non_billable_time = $sub_queries->query($query_non_billable_time.$employee_activity_where);
                $row_non_billable_time = $sub_queries->fetchRow($result_non_billable_time);
                $employee_activity_details["non_billable_time"] = $row_non_billable_time['non_billable_time'];
                $total_non_billable_time = $total_non_billable_time + $employee_activity_details["non_billable_time"];

                $result_invoiced_time = $sub_queries->query($query_invoiced_time.$employee_activity_where);
                $row_invoiced_time = $sub_queries->fetchRow($result_invoiced_time);
                $employee_activity_details["invoiced_time"] = $row_invoiced_time['invoiced_time'];
                $total_invoiced_time = $total_invoiced_time + $employee_activity_details["invoiced_time"];

                $result_pending_time = $sub_queries->query($query_pending_time.$employee_activity_where);
                $row_pending_time = $sub_queries->fetchRow($result_pending_time);
                $employee_activity_details["pending_time"] = $row_pending_time['pending_time'];
                $total_pending_time = $total_pending_time + $employee_activity_details["pending_time"];

                $result_worktypes = $sub_queries->query($query_related_worktype.$employee_activity_where.
                 " GROUP BY project_id_c ");
                $row_worktypes = $sub_queries->fetchRow($result_worktypes);
                $employee_activity_worktypes["worktypes"] = $row_worktypes['worktypes'];


            }

            $available_rates = [];
            if(count($employee_activity_worktypes["worktypes"])>0){
                $currency_bean = new Currency();
                $filter_worktypes = "'".implode("', '", explode(',', $employee_activity_worktypes["worktypes"]))."'";
                $query_related_worktype_rates =
                    "SELECT ct_modules_worktype_id_c as id,cost,currency_id as worktypes ".
                    "FROM ci_worktype_rates WHERE project_id_c='%s' and ct_modules_worktype_id_c IN (%s) and deleted = 0 ";
                $filtered_query = sprintf($query_related_worktype_rates,$project_id,$filter_worktypes);
                $result_worktypes_rates = $sub_queries->query($filtered_query);
                while ($row_rates = $sub_queries->fetchRow($result_worktypes_rates)){
                    $currency_bean->retrieve($row_rates['currency_id']);
                    $available_rates[$row_rates['id']] = $currency_bean->convertToDollar($row_rates['cost']);
                }
            }

            $total = $row['total'];
            $employees_ids[] =$employee_id;
            $employees[$employee_id] = [
                "billable_time" => $employee_activity_details["billable_time"],
                "non_billable_time" => $employee_activity_details["non_billable_time"],
                "invoiced_time" =>$employee_activity_details["invoiced_time"],
                "pending_time" =>$employee_activity_details["pending_time"],
                "activity_ids" => $activity_ids,
                "related_worktypes" => explode(',', $employee_activity_worktypes["worktypes"] ),
                "total" => $employee_activity_details["billable_time"]+$employee_activity_details["non_billable_time"],
            ];
        }

        $where_employees = '';
        if (!empty($activity_arr)){
            $where_employees .= " cc_employee_information.id IN ('".implode("', '", $employees_ids )."')";
            $employee_module = BeanFactory::getBean('CC_Employee_Information');
            $employee_module_list = $employee_module->get_full_list("name", $where_employees);
            foreach ($employee_module_list as $employee){
                $employees[$employee->id]['id'] = $employee->id;
                $employees[$employee->id]['name'] = $employee->name;
            }
        }

        $project_modules = $this->getProjectModules($activity_arr);
        $modules_worktype = $this->getModulesWorktype($activity_arr);

        $userHasRateAccess = ACLController::checkAccess('CI_WorkType_Rates', 'view', $current_user->id);
        if( $userHasRateAccess){
            foreach ($available_rates as $wType => $cost){
                $modules_worktype[$wType]['cost'] = $cost;
            }
        }

        return [
            "total_billable_time" => $total_billable_time,
            "total_non_billable_time" => $total_non_billable_time,
            "total_invoiced_time" => $total_invoiced_time,
            "total_pending_time" => $total_pending_time,
            "total_time" => $total_billable_time+$total_non_billable_time,
            "total_activities" => $total_activities,
            "related_activities" => $activity_arr,
            "related_modules" => $project_modules,
            "related_worktypes" => $modules_worktype,
            "employee_data" => $employees
        ];

    }

}




