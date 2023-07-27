<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';

use Api\V8\Config\Common as Common;
use Api\V8\Utilities;
use CC_Employee_InformationController;

require_once 'modules/CC_Employee_Information/controller.php';

class CC_Professional_ExperienceController extends SugarController{
    
    public function __construct(){
        parent::__construct();
        self::createViewProfessionalExperienceList();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Professional_ExperienceController()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }
 

    public function saveProfessional_ExperienceRecord(object $Professional_Experience)
    {
  
      global $current_user;
      $date_begin = ($Professional_Experience->Start instanceof \DateTime) ?
        $Professional_Experience->Start->format("Y-m-d") : explode('T', $Professional_Experience->Start)[0];
  
      $date_end   = ($Professional_Experience->End instanceof \DateTime) ?
        $Professional_Experience->End->format("Y-m-d") : explode('T', $Professional_Experience->End)[0];
  
  
      $Professional_ExperienceBean = BeanFactory::newBean('CC_Professional_Experience');
      $Professional_ExperienceBean->name = $Professional_Experience->Position;
      $Professional_ExperienceBean->description = $Professional_Experience->Role;
      $Professional_ExperienceBean->business_name = $Professional_Experience->BusinessName;
      $Professional_ExperienceBean->start_date = $date_begin;
      $Professional_ExperienceBean->end_date = $date_end;
      $Professional_ExperienceBean->modified_user_id = (!is_null($current_user->id)) ? $current_user->id : 1;
      $Professional_ExperienceBean->created_by = (!is_null($current_user->id)) ? $current_user->id : 1;
      $result =  $Professional_ExperienceBean->save();
  
      return $result;
    }

    public function simpleEditProfessional_ExperienceRecord(object $Professional_Experience)
    {

      global $current_user;
      $date_begin = ($Professional_Experience->Start instanceof \DateTime) ?
        $Professional_Experience->Start->format("Y-m-d") : explode('T', $Professional_Experience->Start)[0];

      $date_end   = ($Professional_Experience->End instanceof \DateTime) ?
        $Professional_Experience->End->format("Y-m-d") : explode('T', $Professional_Experience->End)[0];


      $Professional_ExperienceBean = BeanFactory::getBean('CC_Professional_Experience', $Professional_Experience->id);
      $Professional_ExperienceBean->name = $Professional_Experience->Position;
      $Professional_ExperienceBean->description = $Professional_Experience->Role;
      $Professional_ExperienceBean->business_name = $Professional_Experience->BusinessName;
      $Professional_ExperienceBean->start_date = $date_begin;
      $Professional_ExperienceBean->end_date = $date_end;
      $Professional_ExperienceBean->modified_user_id = (!is_null($current_user->id)) ? $current_user->id : 1;
      $Professional_ExperienceBean->created_by = (!is_null($current_user->id)) ? $current_user->id : 1;
      $result =  $Professional_ExperienceBean->save();

      return $result;
    }


  public function getRecordsByEmployeeId(string $employeeId) {

    $sql = "SELECT  pe.name 'Position', pe.business_name 'BusinesName', pe.start_date 'Start', pe.end_date 'End', pe.description 'Role' 
      FROM ".Common::$peTable." pe
      LEFT JOIN ".Common::$eipeRelationName." per ON per.".Common::$eipeRelationFieldA." = pe.id
      WHERE pe.deleted = 0 AND per.".Common::$eipeRelationFieldB." = '".$employeeId."'";

    // Get an instance of the dabatabase manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $rows = $db->query($sql);
    
    // Initialize an array with the results
    $result = [];

    // Fetch the row
    while ($row = $db->fetchRow($rows)) {
      $result[] = $row;
    }
    return $result;
  }

  public function createViewProfessionalExperienceList()
    {
        $sql = "CREATE OR REPLACE VIEW cc_professional_experience_cc_project_related as SELECT 
        pe.id, 
        pe.name, 
        pe.business_name, 
        pe.start_date, 
        pe.end_date, 
        pe.description,
        e.id AS id_employee,
        e.name AS employee, 
        e.project_id_c, 
        p.name AS project,
        epm.id AS pm_id, 
        epm.name AS pm_name 
        FROM cc_professional_experience pe 
        LEFT JOIN cc_professional_experience_cc_employee_information_c pe_e ON pe.id=pe_e.cc_professbc84erience_ida
        LEFT JOIN cc_employee_information e ON pe_e.cc_profess8e9armation_idb=e.id
        LEFT JOIN project p ON e.project_id_c=p.id
        LEFT JOIN cc_employee_information_project_c e_p ON e.project_id_c=e_p.cc_employee_information_projectproject_idb AND e_p.deleted = 0 AND e_p.role IN('PM', 'project_manager')
        LEFT JOIN cc_employee_information epm ON e_p.cc_employee_information_projectcc_employee_information_ida=epm.id
        WHERE pe.deleted =0 ";

        $db = DBManagerFactory::getInstance();
        $db->query($sql);

    }


    function GetProfessionalExperience(){

      $projects = BeanFactory::getBean('Project');
      $project_list = array();

      if($projects){ 
          $projectsFields = $projects->get_full_list();
          foreach($projectsFields as $key => $item){
              $parray = array();
              $parray[$projectsFields[$key]->id]   = $projectsFields[$key]->name;
              $project_list[] = $parray; 
          }

      }
    
      $result = array();
      $sql = "SELECT *
      FROM 
          cc_professional_experience_cc_project_related 
       ";

      
      // Get an instance of the dabatabase manager
      $db = DBManagerFactory::getInstance();
      // Perform the query
      $rows = $db->query($sql);
      // Initialize an array with the results
      $result = [];
      // Fetch the row
      
      while ($row = $db->fetchRow($rows)) {
        $row["project_list"]    =  $project_list;
        $result[] = $row;
      }     
     

      $json_data = array(
          "data" => $result,
      );

     

      return $json_data;
  }


  public function quickEditProfessionalExperience($params){

    $employeeInformation = BeanFactory::getBean('CC_Employee_Information', $params['id_employee']);
    $professionalExperience = BeanFactory::getBean('CC_Professional_Experience', $params['id_experience']);


    switch ($params['change']) {
        case 1:
          $employeeInformation->project_id_c = $params['info'];
          (new CC_Employee_InformationController)->addProjectoToEmployee($employeeInformation, $params['info']);
          break;
        case 2:
            $professionalExperience->business_name = $params['info'];
          break;
        case 3:
            $professionalExperience->name = $params['info'];
          break;
          case 4:
            $date = new DateTime($params['info']);
            $professionalExperience->start_date =  $date->format('Y-m-d');
          break;
          case 5:
            $date = new DateTime($params['info']);
            $professionalExperience->end_date = $date->format('Y-m-d');
          break;
        default:
        $professionalExperience->description = $params['info'];
      }

    $professionalExperience->save();

}


function get_pm_name($params){
    $sql = "SELECT 
      ep.cc_employee_information_projectproject_idb AS id_project, 
      e.name AS name_pm 
    FROM cc_employee_information e 
    JOIN cc_employee_information_project_c ep ON e.id=ep.cc_employee_information_projectcc_employee_information_ida
    WHERE ep.role IN ('project_manager', 'PM') and ep.cc_employee_information_projectproject_idb = '". $params['id_project'] ."'";

    // Get an instance of the dabatabase manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $rows = $db->query($sql);

    $project_pm_list = array();

    // Fetch the row
    while ($row = $db->fetchRow($rows)) {
      $pmarray = array();
      $pmarray['name']   = $row['name_pm'];
      $project_pm_list[] = $pmarray; 
    }

    return $project_pm_list;
}



}