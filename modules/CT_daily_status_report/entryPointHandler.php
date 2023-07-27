<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CT_daily_status_report/ct_daily_status_report_entrypoint_handler.class.php';

$res = [];

$default = array("options" => array(
    "default" => null
));

$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT, $default);
$start = filter_input(INPUT_POST, 'start', FILTER_DEFAULT, $default);
$length = filter_input(INPUT_POST, 'length', FILTER_DEFAULT, $default);
$columns = filter_input(INPUT_POST, 'columns', FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$search = filter_input(INPUT_POST, 'search', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$project_id = filter_input(INPUT_COOKIE, 'daily_status_related', FILTER_DEFAULT, $default);
$from = filter_input(INPUT_COOKIE, 'daily_status_date_from', FILTER_DEFAULT, $default);
$to = filter_input(INPUT_COOKIE, 'daily_status_date_to', FILTER_DEFAULT, $default);

if($from==='undefined'){ $from= ct_daily_status_report_entrypoint_handler::getFromDate(); }
if($to==='undefined'){ $to= ct_daily_status_report_entrypoint_handler::getFromDate(); }
if($project_id==='undefined'){ $project_id = null; }

if (!is_null($action)) {
    $handler = new ct_daily_status_report_entrypoint_handler($order,$columns,$search,$start,$length);

    if($action == "get"){
        $results = $handler->handleGetAction($from, $to, $project_id);
        $res = (object) array(
            "recordsFiltered" => $handler->get_project_total(),
            "recordsTotal" => $handler->get_project_total(),
            "data"=> $results,
        );
    }

}
echo json_encode($res);