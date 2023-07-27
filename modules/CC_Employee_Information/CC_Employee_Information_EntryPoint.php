<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Employee_Information/controller.php';

$res = (object)['results' => []];

    $table = "cc_employee_information";
    $request = $_REQUEST;

  if(key_exists("action",$_POST) && $_POST["action"]=='search'){
      if(key_exists("searchTerm",$_POST) && count($_POST["searchTerm"])>0) {
          $results = (new CC_Employee_InformationController())->getEmployeeByName($_POST['searchTerm']);
          $res = (object) ['results' => $results];
      }
  }

  if (key_exists("action", $_POST) && $_POST["action"] == 'GetEmployeesInformation') {
    $results = (new CC_Employee_InformationController())->GetEmployeesInformation($_POST["filterColumData"]);
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'quickEditEmployee') {
  $results = (new CC_Employee_InformationController())->quickEditEmployee($_POST);
  $res = $results;
}
if (key_exists("action", $_POST) && $_POST["action"] == 'inactivateEmployee') {
  $results = (new CC_Employee_InformationController())->inactivateEmployee($_POST);
  $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'changeAnniversary') {
  $results = (new CC_Employee_InformationController())->changeAnniversary($_POST);
  $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'update_rating') {
  $id = filter_input(INPUT_POST, 'id', FILTER_DEFAULT, $default);
  $rating = filter_input(INPUT_POST, 'rating', FILTER_DEFAULT, $default);
  $results = (new CC_Employee_InformationController())->update_rating($id, $rating);
  $res = $results;
}

if (key_exists("action", $_GET) && $_GET["action"] == 'getAllInformation') {
  $file="demo.xls";
  header("Content-type: application/vnd.ms-excel; charset=UTF-8");
  header("Content-Disposition: attachment; filename=$file");
  echo "\xEF\xBB\xBF"; 
  $results = (new CC_Employee_InformationController())->getAllInformation();
  $res = $results;
  echo $res;
  return $res;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'get_info') {
  $record_id = filter_input(INPUT_POST, 'record_id', FILTER_DEFAULT, $default);
  $results = (new CC_Employee_InformationController())->get_info($record_id);
  $res = $results;
}


echo json_encode($res);
