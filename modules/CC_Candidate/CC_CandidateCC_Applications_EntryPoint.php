<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Candidate/controller.php';
require_once 'modules/CC_Qualification/controller.php';
require_once 'modules/CC_Skill/controller.php';
require_once 'custom/modules/Users/customcontroller.php';
require_once 'custom/Extension/application/Include/entrypoint_handler.class.php';


use BeanFactory;

class cc_candidate_entrypoint_handler extends entrypoint_handler
{
    private $response = [];
    private $start;
    private $length;
    private $default_sort;
    private $append_filter;
    private $ar_total = 0;
    private $candidates;
    private $ar_filtered = 0;

    private $object_name = "CC_Candidate";

    private $default_query = "SELECT * 
      FROM  cc_candidate c WHERE c.deleted = 0";

    private $total_query = "SELECT count(DISTINCT id) as total 
      FROM  cc_candidate c WHERE c.deleted = 0";

    public function __construct($order = [], $columns = [], $search = [], $start = 0, $length = 100)
    {
        $this->start = $start;
        $this->length = $length;


        $bean_candidates = BeanFactory::getBean('CC_Candidate');
        $candidates =$bean_candidates->get_list();
        $this->ar_total = $candidates[row_count];

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
            'id'               => $row['id'],
            'object_name'      => $this->object_name,
            'name'             => $row['name'],
            'first_name'       => $row['first_name'],
            'last_name'        => $row['last_name'],
            'city'             => $row['city'],
            'country'          => $row['country'],
        ];


        return $result;
    }

    public function handleGetAction()
    {

        global $current_user;

        $current_query = $this->default_query;

        $total_query = $this->total_query;


        $db = DBManagerFactory::getInstance();
        $where = (!empty($this->append_filter)) ? " AND " . $this->append_filter : "";

        $order = (!empty($this->default_sort)) ? " ORDER BY " . $this->default_sort : "";

        $query = $current_query . $where  . $order . sprintf(" LIMIT %d, %d", $this->start, $this->length);
        $queryTotal = $total_query . $where  ;


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



$res = (object) ['results' => []];

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

if (!is_null($action)) {
    $handler = new cc_candidate_entrypoint_handler($order, $columns, $search, $start, $length);
    $table = "cc_candidate";
    $request = $_REQUEST;


if(key_exists("action",$_POST) && $_POST["action"]=='getCandidateById'){
    $controller = new customUsercontroller();
    $result = (new CC_CandidateController())->getRecordsByIds([$_POST["elementId"]]);
    foreach ($result[0]["PersonalityTest"] as $key=>$value){
        $userName = $controller->getUserNameById($result[0]["PersonalityTest"][$key]["modified_user_id"])[0];
        $fileName = strtolower($value['Pattern']);
        $fileName .= ".json";
        $jsonData = file_get_contents("modules/CC_Personality_Test/pattern_data/$fileName");
        $jsonData = json_decode($jsonData, true);
        $result[0]["PersonalityTest"][$key]["pattern_data"] = $jsonData;
        $result[0]["PersonalityTest"][$key]["modified_user_id"] = $userName;
    }
    $res = (object) ['results' => $result];
}

if(key_exists("action",$_POST) && $_POST["action"]=='GetSkillsByCandidateId'){
    $skills = (new CC_SkillController())->getRecordsByCandidateId($_POST["candidateId"]);
    $skillsFiltered = (new CC_SkillController())->linkingRatingAndYears($skills);
    $res = array("data" => $skillsFiltered);
}

if(key_exists("action",$_POST) && $_POST["action"]=='GetQualificationsByCandidateId'){
    $qualifications = (new CC_QualificationController)->getRecordsByCandidateId($_POST["candidateId"]);
    $res = array("data" => $qualifications);
}

if (key_exists("action", $_POST) && $_POST["action"] == 'GetCandidatesInformation') {
    $results = $handler->handleGetAction();
    $res = (object)array(
        "draw" => $_POST['draw'],
        "recordsFiltered" => $handler->get_ar_filtered(),
        "recordsTotal" => $handler->get_ar_total(),
        "data" => $results,
    );
}

if (key_exists("action", $_POST) && $_POST["action"] == 'quickEditCandidate') {
  $results = (new CC_CandidateController())->quickEditCandidate($_POST);
  $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'CandidateUploadResume') {
    $results = (new CC_CandidateController())->candidateUploadResume($_POST, $_FILES);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'CandidateUploadCreate') {
    $results = (new CC_CandidateController())->candidateUploadCreate($_POST, $_FILES);
    $res = $results;
}

}

echo json_encode($res);