<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Job_Applications/controller.php';
require_once 'modules/CC_Interviews/controller.php';
require_once 'custom/Extension/application/Include/entrypoint_handler.class.php';


use BeanFactory;

class cc_job_application_entrypoint_handler extends entrypoint_handler
{
    private $response = [];
    private $start;
    private $length;
    private $default_sort;
    private $append_filter;
    private $ar_total = 0;
    private $job_applications;
    private $ar_filtered = 0;

    private $object_name = "CC_Job_Applications";

    private $default_query = "SELECT * 
      FROM  cc_job_applications_list c WHERE c.candidate_id IS not null";

    private $total_query = "SELECT count(*) as total 
      FROM  cc_job_applications_list c WHERE c.candidate_id IS not null";

    public function __construct($order = [], $columns = [], $search = [], $start = 0, $length = 100)
    {
        $this->start = $start;
        $this->length = $length;

        $bean_job_applications = BeanFactory::getBean('CC_Job_Applications');
        $job_applications =$bean_job_applications->get_list();
        $this->ar_total = $job_applications[row_count];

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

        $result = (object)[
            'object_name'           => $this->object_name,
            'candidate_id'          => $row['candidate_id'],
            'job_offer_id'          => $row['job_offer_id'],
            'applications_id'       => $row['applications_id'],
            'candidate_name'        => $row['candidate_name'],
            'job_offer_name'        => $row['job_offer_name'],
            'skill_rating'          => $row['skill_rating'],
            'qualification_rating'  => $row['qualification_rating'],
            'general_rating'        => $row['general_rating'],
            'stage'                 => $row['stage'],
        ];


        return $result;
    }

    public function handleGetAction($type)
    {
        global $current_user;

        $current_query = $this->default_query;

        $total_query = $this->total_query;


        $db = DBManagerFactory::getInstance();
        $where = (!empty($this->append_filter)) ? " AND " . $this->append_filter : "";
        $whereR = ($type != "") ? " AND application_type = '" . $type . "' " : "";
        $order = (!empty($this->default_sort)) ? " ORDER BY " . $this->default_sort : "";

        $query = $current_query . $where . $whereR . $order . sprintf(" LIMIT %d, %d", $this->start, $this->length);
        $queryTotal = $total_query . $where  . $whereR;

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

}


$res = [];

$default = array("options" => array(
    "default" => null
    ));
    
    $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT, $default);
    $start = filter_input(INPUT_POST, 'start', FILTER_DEFAULT, $default);
    $length = filter_input(INPUT_POST, 'length', FILTER_DEFAULT, $default);
    $elementId = filter_input(INPUT_POST, 'elementId', FILTER_DEFAULT, $default);
    $columns = filter_input(INPUT_POST, 'columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $type = filter_input(INPUT_COOKIE, 'CC_job-applications-type-selected', FILTER_DEFAULT, $default);

if(!isset($res['error'])) {

    $handler = new cc_job_application_entrypoint_handler($order, $columns, $search, $start, $length);
    $primaryKey = 'job_offer_id';
    $table = "cc_job_applications_list";
    $request = $_REQUEST;

    if(key_exists("action",$_POST) && $_POST["action"]=='get'){
        $res = (object) ['results' => []];
        //$results = (new CC_Job_ApplicationsController)->getJobApplicationsList($request, $table, $primaryKey);
        $results = $handler->handleGetAction($type);
        $res = (object)array(
            "draw" => $_POST['draw'],
            "recordsFiltered" => $handler->get_ar_filtered(),
            "recordsTotal" => $handler->get_ar_total(),
            "data" => $results,
        );
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='getSkillsSummary'){
        $results = (new CC_Job_ApplicationsController)->getSkillsSummary($request["jobOfferId"], $request["candidateId"]);
        $res = $results;
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='getQualificationsSummary'){
        $results = (new CC_Job_ApplicationsController)->getQualificationsSummary($request["jobOfferId"], $request["candidateId"]);
        $res = $results;        
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='getRelatedNotes'){
        $results = (new CC_Job_ApplicationsController)->getRelatedNotes($request["applicationId"]);
        $res = $results;        
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='CreateNote'){
        $results = (new CC_Job_ApplicationsController)->createNote($_POST,$_FILES);  
        $res = (object)['results' => $results];     
    }


    if(key_exists("action",$_POST) && $_POST["action"]=='GetNoteSingle'){
        $results = (new CC_Job_ApplicationsController)->getNoteSingle($_POST["idNote"]);
        $res = (object)['results' => $results];       
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='getInterviewResult'){
        $results = (new CC_InterviewsController)->getInterviewResult($request["applicationId"]);
        $res = $results;        
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='CreateInterviewResult'){
        $results = (new CC_InterviewsController)->createInterviewResult($_POST, 1);  
        $res = (object)['results' => $results];     
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='GetIntResultSingle'){
        $results = (new CC_InterviewsController)->getIntResultSingle($_POST["idResult"]);
        $res = (object)['results' => $results];       
    }

    if(key_exists("action",$_POST) && $_POST["action"]=='GetIntResultType'){
        $results = (new CC_Job_ApplicationsController)->getIntResultType();
        $res = $results;       
    }


    if(key_exists("action",$_POST) && $_POST["action"]=='updateCandidate'){
        $action = (json_decode(json_encode($_POST)));
        $results = (new CC_CandidateController)->saveCandidateRecord($action, $_POST["id_candidate"]);
        if($results->id){
        $res = (object)['results' => 'done'];  
        } 

    }
    
}

echo json_encode($res);