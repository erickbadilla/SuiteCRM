<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CT_Activity/controller.php';
require_once 'custom/Extension/application/Include/entrypoint_handler.class.php';
use BeanFactory;
class ct_activity_entrypoint_handler extends entrypoint_handler {
    private $response = [];
    private $start;
    private $length;
    private $projects;
    private $project_total = 0;
    private $default_sort;
    private $append_filter;

    private $default_query = "SELECT
       project.id, project.name, project.status, project.priority, project.assigned_user_id,
       CONCAT(users.first_name,' ',users.last_name) assigned_user_name,
       COUNT(ct_activity.id) as total_activities,
       SUM(IF(ct_activity.is_billable = 1 AND ct_activity.aos_invoices_id_c IS NOT NULL, 1, 0)) as invoiced_activities,
       SUM(IF(ct_activity.is_billable = 0 AND ct_activity.aos_invoices_id_c IS NOT NULL, 1, 0)) as non_billable_invoiced,
       ROUND(SUM(IF(ct_activity.is_billable = 1 AND ct_activity.aos_invoices_id_c IS NOT NULL, activity_time, 0)), 2) as invoiced_time,
       SUM(IF(ct_activity.is_billable=1 AND ct_activity.aos_invoices_id_c IS NULL,1,0 )) as pending_activities,
       ROUND(SUM(IF(ct_activity.is_billable = 1 AND ct_activity.aos_invoices_id_c IS NULL, activity_time, 0)), 2) as pending_time,
       SUM(IF(ct_activity.is_billable=0,1,0 )) as non_billable_activities,
       ROUND(SUM(IF(ct_activity.is_billable = 0, activity_time, 0)), 2) as non_billable_time
    FROM ct_activity INNER JOIN project on ct_activity.project_id_c = project.id LEFT JOIN users ON project.assigned_user_id = users.id
    WHERE ct_activity.activity_date BETWEEN 'start_date' AND 'end_date' AND ct_activity.deleted = 0";

    private $total_query = "SELECT count(DISTINCT project.id) as total 
    FROM ct_activity INNER JOIN project on ct_activity.project_id_c = project.id LEFT JOIN users ON project.assigned_user_id = users.id
    WHERE ct_activity.activity_date BETWEEN 'start_date' AND 'end_date' AND ct_activity.deleted = 0";


    public function __construct($order =[],$columns= [], $search=[], $start=0, $length=100){
        $this->start = $start;
        $this->length = $length;

        $map_project_list = function ($project_list){
            $result = [];
            foreach ($project_list as $project){
                $result[$project->id]=$project;
            }
            return $result;
        };

        $bean_projects = BeanFactory::getBean('Project');
        $this->projects = $map_project_list($bean_projects->get_full_list());
        $this->project_total = count($this->projects);

        if(count($order)){
            $sort_result = self::order($order,$columns);
            if(!empty($sort_result)){
                $this->default_sort = $sort_result;
            }
        }

        if(count($search)){
            $filter = self::filter($search,$columns);
            if(!empty($filter)){
                $this->append_filter = $filter;
            }
        }
    }

    private function mapRow($row)
    {
        global $app_list_strings;

        $status = (key_exists($row['status'],$app_list_strings['project_status_dom']))?
            $app_list_strings['project_status_dom'][$row['status']]:"";
        $priority = (key_exists($row['priority'],$app_list_strings['projects_priority_options']))?
            $app_list_strings['projects_priority_options'][$row['priority']]:"";

        $valueOrZero = function ($a,$key){
            return  (!empty($a[$key]))?$a[$key]:0;
        };

        $result = (object) [
            'id' => $row['id'],
            'name' => $row['name'],
            'status' => $status,
            'priority' => $priority,
            'assigned_user_id' => (!empty($row['assigned_user_id']))?$row['assigned_user_id']:'',
            'assigned_user_name' => (!empty($row['assigned_user_id']) && !empty($row['assigned_user_name']))?$row['assigned_user_name']:'',
            'total_activities' => $valueOrZero($row,'total_activities'),
            'invoiced_activities' => $valueOrZero($row,'invoiced_activities'),
            'non_billable_invoiced' => $valueOrZero($row,'non_billable_invoiced'),
            'invoiced_time' => $valueOrZero($row,'invoiced_time'),
            'pending_activities' => $valueOrZero($row,'pending_activities'),
            'pending_time' => $valueOrZero($row,'pending_time'),
            'non_billable_activities' => $valueOrZero($row,'non_billable_activities'),
            'non_billable_time' => $valueOrZero($row,'non_billable_time')
        ];


        return $result;
    }
            
    public function handleGetAction($from, $to){
        $current_query = $this->default_query;
        $current_query = str_replace("start_date",$from,$current_query);
        $current_query = str_replace("end_date",$to,$current_query);

        $total_query = $this->total_query;
        $total_query = str_replace("start_date",$from,$total_query);
        $total_query = str_replace("end_date",$to,$total_query);


        $db = DBManagerFactory::getInstance();

        $total = $db->query($total_query);
        $row = $db->fetchRow($total);
        $this->project_total = $row['total'];

        $where = (!empty($this->append_filter))?" AND ".$this->append_filter:"";
        $order = (!empty($this->default_sort))?" ORDER BY ".$this->default_sort:"";
        $group = " GROUP BY ct_activity.project_id_c HAVING total_activities >0 ";

        $query = $current_query.$where.$group.$order.sprintf(" LIMIT %d, %d",$this->start, $this->length );
        $rows = $db->query($query);

        $result = [];

        while ($row = $db->fetchRow($rows)) {
            $result[] = self::mapRow($row);
        }

        return $result;
    }

    public function execute_activity_sync($user_list,$project_id,$start, $end){

        global $current_user,$BASE_DIR;
        $execute_task = sprintf("cd %s;nohup php activateHolidays.php -r %s > /dev/null &",$BASE_DIR,$record_id);
        $exec_task = exec($execute_task);
        $bean = BeanFactory::getBean('CH_Country_Holidays',$record_id);
        $bean->active = 1;
        $sResult = $bean->save();
        $message = ($sResult)?"List Activated":"List activation error";
        return array("result"=>$sResult, "records"=> $exec_task, "message"=>$message);
    }

    public function get_project_total(){
        return $this->project_total;
    }
}

$res = [];

$default = array("options" => array(
    "default" => null
));

$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT, $default);
$start = filter_input(INPUT_POST, 'start', FILTER_DEFAULT, $default);
$length = filter_input(INPUT_POST, 'length', FILTER_DEFAULT, $default);
$elementId = filter_input(INPUT_POST, 'elementId', FILTER_DEFAULT, $default);
$columns = filter_input(INPUT_POST, 'columns', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$activity_ids = filter_input(INPUT_POST, 'activity_ids', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$employees_ids = filter_input(INPUT_POST, 'employees_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$project_id = filter_input(INPUT_POST, 'project_id', FILTER_DEFAULT, $default);
$search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$from = filter_input(INPUT_COOKIE, 'careers_activity_from', FILTER_DEFAULT, $default);
$to = filter_input(INPUT_COOKIE, 'careers_activity_to', FILTER_DEFAULT, $default);
if (!is_null($action)) {
    $handler = new ct_activity_entrypoint_handler($order,$columns,$search,$start,$length);
    if($action == "get"){
        $results = $handler->handleGetAction($from, $to);
        $res = (object) array(
            "recordsFiltered" => $handler->get_project_total(),
            "recordsTotal" => $handler->get_project_total(),
            "data"=> $results,
        );
    }

    if($action == "getEmployeeActivities"){
        $results = (new CT_ActivityController())->getEmployeeActivities($activity_ids);
        $res = (object) array(
            "recordsFiltered" => count($activity_ids),
            "recordsTotal" => count($activity_ids),
            "data"=> $results,
        );
    }

    if($action == "synchronizationAction"){
        global $current_user, $BASE_DIR;
        $execute_task = sprintf(
        "cd %s;nohup php syncEmployeeActivities.php -u %s -p %s --employees=\"%s\" --start=\"%s\" --end=\"%s\" > /dev/null &",
            $BASE_DIR,$current_user->id,$project_id,implode(",",$employees_ids),$from,$to
        );
        $exec_task = exec($execute_task);
        return array("result"=>true, "task"=> $exec_task);
    }

} else {
    $res = (object)['results' => []];
    $request = $_REQUEST;

    if (key_exists("entryPoint", $_GET) && $_GET["entryPoint"] == 'CTActivityEntryPoint') {
        $results = (new CT_ActivityController())->GetActivities();
        $res = $results;
    }

    if (key_exists("entryPoint", $_GET) && $_GET["entryPoint"] == 'CTActivityProjectsEntryPoint') {
        $results = (new CT_ActivityController())->GetActivityProjects();
        $res = $results;
    }
}


echo json_encode($res);