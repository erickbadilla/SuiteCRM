<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Questions/controller.php';

$res = (object)['results' => []];


if (key_exists("action", $_POST) && $_POST["action"] == 'reorderRecords') {
    $id = filter_input(INPUT_POST, 'elementId', FILTER_DEFAULT, $default);
    $results = (new CC_QuestionsController())->reorderRecords($id);
    $res = (object)['results' => $results];
}


if (key_exists("action", $_POST) && $_POST["action"] == 'give_order_questions') { 
    $id = filter_input(INPUT_POST, 'elementId', FILTER_DEFAULT, $default);
    $order = filter_input(INPUT_POST, 'value', FILTER_DEFAULT, $default); 
    $results = (new CC_QuestionsController())->give_order_questions($id,$order);
    $res = (object)['results' => $results];
}


echo json_encode($res);