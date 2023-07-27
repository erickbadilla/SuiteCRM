<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/AR_Activity_Report/controller.php';
require_once 'custom/Extension/application/Include/entrypoint_handler.class.php';

use BeanFactory;

class ar_activity_report_entrypoint_handler extends entrypoint_handler
{
    private $response = [];
    private $start;
    private $length;
    private $default_sort;
    private $append_filter;
    private $ar_total = 0;
    private $activity_reports;
    private $ar_filtered = 0;

    private $object_name = "AR_Activity_Report";

    private $default_query = "SELECT m.name, m.description, m.date_start,
    m.parent_type,IF(m.duration_hours > 0, CONCAT(m.duration_hours,  ' Hours ',m.duration_minutes, ' Minutes' ), 
    CONCAT(m.duration_minutes, ' Minutes' )) duration,
    ar.parent_type AS typeAr, (SUM((duration_hours*60) + duration_minutes)/COUNT(ar.id)) AS result,
    ar.last_start_time, ar.accumulated_time, ar.status as status, ar.is_billable,
    DATEDIFF(NOW(), ar.last_start_time) AS running_days,
    TIME_FORMAT(TIMEDIFF(NOW(), ar.last_start_time), '%H') AS running_hours,
    TIME_FORMAT(TIMEDIFF(NOW(), ar.last_start_time), '%i') AS running_minutes,
    TRUNCATE((ar.accumulated_time/60)/24,0) as accumulated_days,
    TRUNCATE((ar.accumulated_time/60),0) - (TRUNCATE((ar.accumulated_time/60)/24,0)*24) as accumulated_hours,
    ar.accumulated_time%60 as accumulated_minutes,      
    ar.id as idAr , m.assigned_user_id , CONCAT(COALESCE(u.first_name, ''),' ' , u.last_name) AS assigned 
      FROM  ar_activity_report ar 
      JOIN meetings m ON ar.parent_id =m.id
      LEFT JOIN users u ON u.id=m.assigned_user_id
      WHERE m.date_start BETWEEN 'start_date' AND 'end_date' AND m.deleted = 0";

    private $total_query = "SELECT count(DISTINCT ar.id) as total 
      FROM  ar_activity_report ar 
      JOIN meetings m ON ar.parent_id =m.id
      WHERE m.date_start BETWEEN 'start_date' AND 'end_date' AND m.deleted = 0";

    public function __construct($order = [], $columns = [], $search = [], $start = 0, $length = 100)
    {
        $this->start = $start;
        $this->length = $length;


        $map_activity_report_list = function ($activity_report_list) {
            $result = [];
            foreach ($activity_report_list as $activity_report) {
                $result[$activity_report->id] = $activity_report;
            }
            return $result;
        };

        $bean_activity_reports = BeanFactory::getBean('AR_Activity_Report');
        $this->activity_reports = $map_activity_report_list($bean_activity_reports->get_full_list("", "parent_type='Meetings'", false,));
        $this->ar_total = count($this->activity_reports);

        if (count($order)) {
            $sort_result = self::order($order, $columns);
            if (!empty($sort_result)) {
                $this->default_sort = $sort_result;
            }
        }

        if (count($search)) {
            $filter = self::filter($search, $columns);
            if (!empty($filter)) {
                $this->append_filter = $filter;
            }
        }
    }

    private function mapRow($row)
    {
        global $app_list_strings,$current_user;
        $beanAR = BeanFactory::getBean('AR_Activity_Report', $row['idAr']);
        $beanListP = $beanAR->get_linked_beans('ar_activity_report_ar_participant','AR_Participant',array(),0,100,0);
        if(!empty($beanListP)){
            $partName ='';
            foreach ($beanListP as $key=>$relatedBean){
                $partName .= ($relatedBean->is_owner ==1)? $relatedBean->name . '(Requester)':$relatedBean->name;
                if ($key !== array_key_last($beanListP)) {
                    $partName .=', ';
                }
            }
        }else{
            $partName = $row['assigned'] . '(Requester)';
        }

        $result = (object)[
            'id'               => $row['idAr'],
            'object_name'      => $this->object_name,
            'subject'          => $row['name'],
            'agenda'           => $row['description'],
            'date_start'       => $row['date_start'],
            'typeAr'           => $row['typeAr'],
            'status'           => (key_exists($row['status'],$app_list_strings['activity_report_status']))?
                                     $app_list_strings['activity_report_status'][$row['status']]:$row['status'],
            'last_start_time'  => $row['last_start_time'],
            'accumulated_time' => intval($row['accumulated_time']),
            'running_days'     => intval($row['running_days']),
            'running_hours'    => intval($row['running_hours']),
            'running_minutes'  => intval($row['running_minutes']),
            'accumulated_days'   => intval($row['accumulated_days']),
            'accumulated_hours'  => intval($row['accumulated_hours']),
            'accumulated_minutes'=> intval($row['accumulated_minutes']),
            'is_billable'      => boolval($row['is_billable']),
            'parent_type'      => $row['parent_type'],
            'parent_id'        => $row['parent_id'],
            'duration'         => $row['duration'],
            'partName'         => $partName,
            'partId'           => $row['partId'],
            'canEdit'          => ($row['assigned_user_id'] == $current_user->id || $this->is_admin() ) ? 1 : 0,
        ];


        return $result;
    }

    public function handleGetAction($from, $to, $related)
    {

        global $current_user;

        $current_query = $this->default_query;
        $current_query = str_replace("start_date", $from, $current_query);
        $current_query = str_replace("end_date", $to, $current_query);

        $total_query = $this->total_query;
        $total_query = str_replace("start_date", $from, $total_query);
        $total_query = str_replace("end_date", $to, $total_query);


        $db = DBManagerFactory::getInstance();


        $where = (!empty($this->append_filter)) ? " AND " . $this->append_filter : "";
        $whereR = ($related != "") ? " AND m.parent_type = '" . $related . "' " : "";
        $whereP = ($this->is_admin()) ? "" : " AND (m.assigned_user_id = '". $current_user->id ."' OR p.parent_id = '". $current_user->id ."')";;

        $order = (!empty($this->default_sort)) ? " ORDER BY " . $this->default_sort : "";
        $group = " GROUP BY ar.id ";

        $query = $current_query . $where . $whereR . $whereP . $group . $order . sprintf(" LIMIT %d, %d", $this->start, $this->length);
        $queryTotal = $total_query . $where . $whereR . $whereP ;

        $rows = $db->query($query);
        $result = [];

        $total = $db->query($queryTotal);
        $row = $db->fetchRow($total);

        $this->ar_filtered = $row['total'];

        while ($row = $db->fetchRow($rows)) {
            $result[] = self::mapRow($row);
        }

        return $result;
    }

    public function get_ar_total()
    {
        return $this->ar_total;
    }

    public function get_ar_filtered()
    {
        return $this->ar_filtered;
    }

    public function is_admin(){
        global $current_user;
        $secGrpBean = BeanFactory::getBean('SecurityGroups');
        $beanGrp = $secGrpBean->retrieve_by_string_fields(array('name' => 'HR Admin'));
        if($beanGrp){
            $member_list = $beanGrp->getMembers();
            if(($current_user->is_admin) || key_exists($current_user->id,$member_list)){
                return true;
            }
        }
    }

}

$res = (object)['results' => []];

$default = array("options" => array(
"default" => null
));

$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT, $default);
$start = filter_input(INPUT_POST, 'start', FILTER_DEFAULT, $default);
$length = filter_input(INPUT_POST, 'length', FILTER_DEFAULT, $default);
$elementId = filter_input(INPUT_POST, 'elementId', FILTER_DEFAULT, $default);
$activity_action = filter_input(INPUT_POST, 'activity_action', FILTER_DEFAULT, $default);
$columns = filter_input(INPUT_POST, 'columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$activity_ids = filter_input(INPUT_POST, 'activity_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$from = filter_input(INPUT_COOKIE, 'activity_date_from', FILTER_DEFAULT, $default);
$to_date = filter_input(INPUT_COOKIE, 'activity_date_to', FILTER_DEFAULT, $default);
$to = date("Y-m-d", strtotime($to_date."+ 1 days"));
$related = filter_input(INPUT_COOKIE, 'activity_related', FILTER_DEFAULT, $default);

if (!is_null($action)) {
    $handler = new ar_activity_report_entrypoint_handler($order, $columns, $search, $start, $length);
    $table = "ar_activity_report";
    $request = $_REQUEST;
    if ($action == "get") {
        $results = $handler->handleGetAction($from, $to, $related);
        $res = (object)array(
            "draw" => $_POST['draw'],
            "recordsFiltered" => $handler->get_ar_filtered(),
            "recordsTotal" => $handler->get_ar_total(),
            "data" => $results,
        );
    }
    if ($action === 'bringRelatedTo') {
        $relation = filter_input(INPUT_POST, 'relation', FILTER_DEFAULT, $default);
        $results = (new AR_Activity_ReportController())->bringRelatedTo($relation);
        $res = (object)['results' => $results];
    }


    if ($action === 'CreateActivityReport') {
        // FixMe:: sanitize values before
        $results = (new AR_Activity_ReportController())->CreateActivityReport($_POST, $_FILES);
        $res = (object)['results' => $results];
    }

    if ($action === 'GetActivityReport') {
        $id_activity_report = filter_input(INPUT_POST, 'id_activity_report', FILTER_DEFAULT, $default);
        global $app_list_strings;
        $results = (new AR_Activity_ReportController())->GetActivityReport($id_activity_report);
        $res = (object)['results' => $results, 'related_to' => $app_list_strings['parent_type_display']];
    }

    if ($action === 'UpdateActivityStatus') {
        global $app_list_strings;
        $results = (new AR_Activity_ReportController())->UpdateActivityStatus($elementId, $activity_action);
        $res = (object)$results;
    }

    if ($action === 'deleteFile') {
        $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, $default);
        $results = (new AR_Activity_ReportController())->deleteFile($id);
        $res = (object)['results' => $results];
    }

    if ($action === 'deleteDuty') {
        $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, $default);
        $results = (new AR_Activity_ReportController())->deleteDuty($id);
        $res = (object)['results' => $results];
    }

    if ($action === 'updateDuty') {
        $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, $default);
        $results = (new AR_Activity_ReportController())->UpdateDuty($id,$_POST);
        $res = (object)['results' => $results];
    }

    if ($action === 'AddNewDuty') {
        $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, $default);
        $results = (new AR_Activity_ReportController())->AddNewDuty($id,$_POST);
        $res = (object)['results' => $results];
    }

    if ($action === 'deleteParticipant') {
        $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, $default);
        $idParRel = filter_input(INPUT_POST, 'idParRel', FILTER_DEFAULT, $default);
        $results = (new AR_Activity_ReportController())->deleteParticipant($idParRel,$id);
        $res = (object)['results' => $results];
    }

    if ($action === 'GetOwner') {
        $results = (new AR_Activity_ReportController())->getOwner();
        $res = (object)['results' => $results];
    }


}
echo json_encode($res);
