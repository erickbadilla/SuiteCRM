<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/CC_Professional_Experience/controller.php';

$res = (object)['results' => []];

    $table = "cc_professional_experience";
    $request = $_REQUEST;

  if (key_exists("action", $_POST) && $_POST["action"] == 'GetProfessionalExperience') {
    $results = (new CC_Professional_ExperienceController())->GetProfessionalExperience();
    $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'quickEditProfessionalExperience') {
  $results = (new CC_Professional_ExperienceController())->quickEditProfessionalExperience($_POST);
  $res = $results;
}

if (key_exists("action", $_POST) && $_POST["action"] == 'get_pm_name') {
  $results = (new CC_Professional_ExperienceController())->get_pm_name($_POST);
  $res = $results;
}



echo json_encode($res);
