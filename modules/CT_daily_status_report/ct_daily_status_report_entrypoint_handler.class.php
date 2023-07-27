<?php

require_once 'modules/CT_daily_status_report/controller.php';
require_once 'custom/modules/Project/customcontroller.php';
require_once 'custom/Extension/application/Include/entrypoint_handler.class.php';
use BeanFactory;

class ct_daily_status_report_entrypoint_handler extends entrypoint_handler {

    private $response = [];
    private $start;
    private $length;
    private $projects;
    private $project_total = 0;
    private $default_sort;
    private $append_filter;

    private static $default_query = "SELECT ".
    " sr.id, cei.id as employee_id, cei.name as employee_name,".
    " sr.date_reported, sr.schedule,sr.mood,".
    " sr.project_id_c, p.name as project_name,".
    " sr.description, sr.eta, ".
    " (SELECT COUNT(sr1.id) FROM ct_daily_status_report sr1 WHERE sr1.cc_employee_information_id_c = ".
    " sr.cc_employee_information_id_c AND sr1.date_reported AND CURDATE() - INTERVAL 30 DAY AND CURDATE() AND ".
    " sr1.schedule IN ('Blocked','blocked') ) as last_blockers, ".
    " (SELECT COUNT(sr2.id) FROM ct_daily_status_report sr2 WHERE sr2.cc_employee_information_id_c = ".
    " sr.cc_employee_information_id_c AND sr2.date_reported AND CURDATE() - INTERVAL 30 DAY AND CURDATE() AND ".
    " sr2.schedule IN ('Delayed','delayed') ) as last_schedule ".
    " FROM ct_daily_status_report sr".
    " JOIN cc_employee_information cei on sr.cc_employee_information_id_c = cei.id ".
    " JOIN project p ON sr.project_id_c = p.id WHERE sr.deleted=0 ";

    private static $total_query = "SELECT ".
    " count(sr.id) as total ".
    " FROM ct_daily_status_report sr".
    " JOIN cc_employee_information cei on sr.cc_employee_information_id_c = cei.id ".
    " JOIN project p ON sr.project_id_c = p.id WHERE sr.deleted=0 ";
    private static $scheduleBaseQuery = "SELECT ".
    " sr.schedule as thekey, count(sr.id) as total FROM ct_daily_status_report sr ".
    " WHERE sr.deleted = 0 AND lower(replace(sr.schedule,' ','')) IN ('blocked','delayed','ontime') ".
    " %s %s GROUP BY sr.schedule";

    private static $scheduleBlockEmployeeQuery = "SELECT ".
    " sr.cc_employee_information_id_c as id, cei.name as employee_name FROM ct_daily_status_report sr ".
    " JOIN cc_employee_information cei on sr.cc_employee_information_id_c = cei.id ".
    " WHERE sr.deleted = 0 AND lower(replace(sr.schedule,' ','')) IN ('blocked') ".
    " %s %s GROUP BY 1";

    private static $scheduleDelayedEmployeeQuery = "SELECT ".
    " sr.cc_employee_information_id_c as id, cei.name as employee_name FROM ct_daily_status_report sr ".
    " JOIN cc_employee_information cei on sr.cc_employee_information_id_c = cei.id ".
    " WHERE sr.deleted = 0 AND lower(replace(sr.schedule,' ','')) IN ('delayed') ".
    " %s %s GROUP BY 1";

    private static $missingReportsBaseQuery = " SELECT ".
    " rel.cc_employee_information_projectcc_employee_information_ida as id, emp.name as employee_name".
    " FROM cc_employee_information_project_c rel".
    " JOIN cc_employee_information emp on rel.cc_employee_information_projectcc_employee_information_ida=emp.id".
    " WHERE (emp.project_id_c %s OR  rel.cc_employee_information_projectproject_idb %s)".
    " AND 'the_date' BETWEEN rel.start_date AND rel.end_date AND emp.active=1 and emp.deleted=0".
    " AND cc_employee_information_projectcc_employee_information_ida NOT IN ".
    " (SELECT dr.cc_employee_information_id_c FROM ct_daily_status_report dr WHERE dr.date_reported = 'the_date' )";

    private static $moodBaseQuery = "SELECT ".
    " sr.mood as thekey, count(sr.id) as total FROM ct_daily_status_report sr".
    " WHERE sr.deleted = 0 AND lower(sr.mood) IN ('okay','angry','great','fantastic','down','depressed')".
    " %s %s GROUP BY sr.mood";

    private static $dateRangeFilter = " AND sr.date_reported BETWEEN 'start_date' AND 'end_date' ";
    private static $projectFilter = " AND sr.project_id_c IN %s ";


    public function __construct($order =[],$columns= [], $search=[], $start=0, $length=100){

        $this->start = $start;
        $this->length = $length;
        $this->projects = self::getProjectList();

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

    /**
     * @return false|string
     */
    public static function getFromDate(){
        return date('Y-m-d', strtotime("-3 months"));
    }

    /**
     * @return false|string
     */
    public static function getToDate(){
        return date('Y-m-d');
    }

    /**
     * @return array
     */
    public static function getProjectList(){
        return (new customProjectcontroller)->getMyProyects();
    }

    private function mapRow($row)
    {
        $valueOrZero = function ($a,$key){
            return  (!empty($a[$key]))?$a[$key]:0;
        };
        $decode_data = json_decode($row['description']);

        if(json_last_error()!==JSON_ERROR_NONE){
            $decode_data =(object)[
                "yesterday"=>"",
                "today"=>"",
                "blockers"=>""
            ];
        }

        $reason = "";
        if(property_exists($decode_data,"blockers")){
            $reason .= $decode_data->blockers. " ";
        }
        if(property_exists($decode_data,"reason")){
            $reason .= $decode_data->reason;
        }

        $result = (object) [
            'id' => $row['id'],
            'employee_id' => $row['employee_id'],
            'employee_name' => $row['employee_name'],
            'date_reported' => $row['date_reported'],
            'schedule' => strtolower(str_replace(' ', '', $row['schedule'])),
            'last_schedule' => $row['last_schedule'],
            'mood' => strtolower(str_replace(' ', '', $row['mood'])),
            'project_id' => $row['project_id_c'],
            'project_name' => $row['project_name'],
            'yesterday' => property_exists($decode_data,"yesterday")?$decode_data->yesterday:"",
            'today' => property_exists($decode_data,"today")?$decode_data->today:"",
            'reason' => (!empty($reason))?$reason:"no reason reported",
            'eta' => $row['eta'],
            'last_blockers' => $row['last_blockers'],
        ];

        return $result;
    }

    /**
     * @param $current_query
     * @param $from
     * @param $to
     * @param $selectedProject
     * @return array
     */
    private function getEmployeeScheduleSummary($current_query, $from, $to, $selectedProject = null){

        $dateRange = self::$dateRangeFilter;
        $dateRange = str_replace("start_date",$from,$dateRange);
        $dateRange = str_replace("end_date",$to,$dateRange);

        $projects = sprintf(self::$projectFilter,sprintf("('".implode("', '", array_keys($this->projects))."')"));
        if(!is_null($selectedProject)){
            $projects = sprintf(self::$projectFilter,"('".$selectedProject."')");
        }

        $db = DBManagerFactory::getInstance();
        $exec_query = sprintf($current_query,$dateRange,$projects);

        $rows = $db->query($exec_query);
        $result = [];

        while ($row = $db->fetchRow($rows)) {
            $result[$row['id']] = $row['employee_name'];
        }

        return $result;

    }

    /**
     * @param $current_query
     * @param $from
     * @param $to
     * @param $selectedProject
     * @return array
     */
    private function getKeySummary($current_query, $from, $to, $selectedProject = null){
        $dateRange = self::$dateRangeFilter;
        $dateRange = str_replace("start_date",$from,$dateRange);
        $dateRange = str_replace("end_date",$to,$dateRange);

        $projects = sprintf(self::$projectFilter,sprintf("('".implode("', '", array_keys($this->projects))."')"));
        if(!is_null($selectedProject)){
            $projects = sprintf(self::$projectFilter,"('".$selectedProject."')");
        }

        $db = DBManagerFactory::getInstance();
        $exec_query = sprintf($current_query,$dateRange,$projects);

        $rows = $db->query($exec_query);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[$row['thekey']] = $row['total'];
        }
        return $result;
    }

    public function getMissingReportsForADate($thedate,$selectedProject){
        $baseQuery = self::$missingReportsBaseQuery;
        $baseQuery = str_replace("the_date",$thedate,$baseQuery);

        $projects = " IN ('".implode("', '", array_keys($this->projects))."')";

        if($selectedProject === 'undefined' || $selectedProject === 'all'){
            $selectedProject = null;
        }

        if(!is_null($selectedProject)){
            $projects = " = '".$selectedProject."'";
        }

        $db = DBManagerFactory::getInstance();
        $exec_query = sprintf($baseQuery, $projects, $projects);

        $rows = $db->query($exec_query);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[$row['id']] = $row['employee_name'];
        }
        return $result;

    }

    public function getMoodSumary( $from, $to, $selectedProject = null){
        return $this->getKeySummary(self::$moodBaseQuery,$from, $to, $selectedProject);
    }

    public function getScheduleSummary($from, $to, $selectedProject = null){
        return $this->getKeySummary(self::$scheduleBaseQuery,$from, $to, $selectedProject );
    }

    public function getEmployeesBlocked($from, $to, $selectedProject = null){
        return $this->getEmployeeScheduleSummary(self::$scheduleBlockEmployeeQuery,$from, $to, $selectedProject );
    }

    public function getEmployeesDelayed($from, $to, $selectedProject = null){
        return $this->getEmployeeScheduleSummary(self::$scheduleDelayedEmployeeQuery,$from, $to, $selectedProject );
    }


    public function handleGetAction($from, $to, $selectedProject = null){
        $current_query = self::$default_query;
        $dateRange = self::$dateRangeFilter;
        $dateRange = str_replace("start_date",$from,$dateRange);
        $dateRange = str_replace("end_date",$to,$dateRange);

        $projects = sprintf(self::$projectFilter,sprintf("('".implode("', '", array_keys($this->projects))."')"));

        if($selectedProject === 'undefined' || $selectedProject === 'all'){
            $selectedProject = null;
        }

        if(!is_null($selectedProject)){
            $projects = sprintf(self::$projectFilter,"('".$selectedProject."')");
        }

        $db = DBManagerFactory::getInstance();
        $total = $db->query(self::$total_query.$dateRange.$projects);
        $row = $db->fetchRow($total);
        $this->project_total = $row['total'];

        $where = (!empty($this->append_filter))?" AND ".$this->append_filter:"";
        $order = (!empty($this->default_sort))?" ORDER BY ".$this->default_sort:"";

        $query = $current_query.$dateRange.$projects.$where.$order.sprintf(" LIMIT %d, %d",$this->start, $this->length );
        $rows = $db->query($query);

        $result = [];

        while ($row = $db->fetchRow($rows)) {
            $result[] = self::mapRow($row);
        }

        return $result;
    }

    public function get_project_total(){
        return $this->project_total;
    }
}
