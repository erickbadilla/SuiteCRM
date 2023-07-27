<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/CC_Skill/CC_SkillCC_ProfileRelationship.php';
require_once 'custom/Extension/application/Include/cc_recruitment_activity_handler.php';
require_once 'modules/CC_Recruitment_Request/JobDescriptionProfileDifference.php';


$errorLevelStored = error_reporting();
error_reporting(0);
error_reporting($errorLevelStored);


use Api\V8\Config\Common as Common;
use Api\V8\Utilities;

class CC_Recruitment_RequestController extends SugarController{

public function __construct(){
    parent::__construct();
    self::declareFunctionCC_Recruitment_Request_Get_Related_ActivitiesQuery();
    self::declareFunctionCC_Application_Stage_Last_Stage_IdQuery();
    self::declareFunctionCC_Recruitment_Request_Get_Activity_CountQuery();
    self::declareFunctionCC_Recruitment_Request_Get_Modules_Related_IdsQuery();
    self::declareFunctionCC_Recruitment_Request_Last_Job_Offer_StatusQuery();
    self::declareFunctionCC_Recruitment_Request_Last_Activity_DateQuery();
    self::declareFunctionCC_Recruitment_Request_ClosedQuery();
    self::createRecruitmentRequestView();
}

/***
 * Create a db function to calculate the Application Stage Last Stage Id
 */
private function declareFunctionCC_Application_Stage_Last_Stage_IdQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Application_Stage_Last_Stage_Id('')";
            if($db->query($sql,false,'Creating CC_Application_Stage_Last_Stage_Id',true)){
                return;
            }

            $sql = "create function CC_Application_Stage_Last_Stage_Id(application_id varchar(40)) returns varchar(40)
                    BEGIN
                        DECLARE result varchar(100);
                        DECLARE app_type varchar(100);
                        SELECT application_type into app_type from cc_job_applications WHERE id = application_id;
                        SELECT id into result FROM cc_application_stage WHERE type=app_type AND deleted=0 ORDER BY stageorder DESC LIMIT 1;
                        RETURN result;
                    END;";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/***
 * Create a db function to calculate the CC_Recruitment_Request_Get_Related_Activities
 */
private function declareFunctionCC_Recruitment_Request_Get_Related_ActivitiesQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Recruitment_Request_Get_Related_Activities('')";
            if($db->query($sql,false,'Creating CC_Recruitment_Request_Get_Related_Activities',true)){
                return;
            }

            $sql = "create function CC_Recruitment_Request_Get_Related_Activities(recruitment_id varchar(40)) returns varchar(21845)
                    BEGIN
                        DECLARE uid_list VARCHAR(21845);
                        SELECT '' into uid_list;
                        SELECT GROUP_CONCAT( cc_recruitc611ctivity_idb SEPARATOR ',') into uid_list FROM cc_recruitment_activity_cc_recruitment_request_c WHERE cc_recruitaa64request_ida = recruitment_id AND deleted=0 GROUP BY cc_recruitaa64request_ida;
                        RETURN uid_list;
                    END;";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/***
 * Create a db function to calculate the Get Activity Count
 */
private function declareFunctionCC_Recruitment_Request_Get_Activity_CountQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Recruitment_Request_Get_Activity_Count('','')";
            if($db->query($sql,false,'Creating CC_Recruitment_Request_Get_Activity_Count',true)){
                return;
            }

            $sql = "create function CC_Recruitment_Request_Get_Activity_Count(r_module varchar(100), recruitment_id varchar(40)) returns int
                    BEGIN
                        DECLARE total int;
                        DECLARE uid_list varchar(4096);
                        SET uid_list = CC_Recruitment_Request_Get_Related_Activities(recruitment_id);
                        IF isnull(uid_list)THEN
                            SELECT 0 into total;
                        ELSE
                            SELECT count(id) into total FROM cc_recruitment_activity WHERE FIND_IN_SET(id, uid_list) AND related_module = r_module AND deleted=0;
                        END IF;
                        RETURN total;
                    END;";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/****
 * Create a db function to calculate the Get Modules Related Ids
 */
private function declareFunctionCC_Recruitment_Request_Get_Modules_Related_IdsQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Recruitment_Request_Get_Modules_Related_Ids('','')";
            if($db->query($sql,false,'Creating CC_Recruitment_Request_Get_Modules_Related_Ids',true)){
                return;
            }

            $sql = "create function CC_Recruitment_Request_Get_Modules_Related_Ids(r_module varchar(100), recruitment_id varchar(40)) returns varchar(21845)
                    BEGIN
                        DECLARE uid_list varchar(21845);
                        SELECT '' into uid_list;
                        SET uid_list = CC_Recruitment_Request_Get_Related_Activities(recruitment_id);
                        IF not isnull(uid_list)THEN
                            SELECT GROUP_CONCAT( related_id SEPARATOR ',') into uid_list FROM cc_recruitment_activity WHERE FIND_IN_SET(id, uid_list) AND related_module = r_module AND deleted=0;
                        END IF;
                        RETURN uid_list;
                    END;
                    ";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/****
 * Create a db function to calculate the Get Last Activity Date
 */
private function declareFunctionCC_Recruitment_Request_Last_Activity_DateQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Recruitment_Request_Last_Activity_Date('')";
            if($db->query($sql,false,'Creating CC_Recruitment_Request_Last_Activity_Date',true)){
                return;
            }

            $sql = "create function CC_Recruitment_Request_Last_Activity_Date(recruitment_id varchar(40)) returns datetime
                    BEGIN
                        DECLARE result datetime;
                        DECLARE uid_list varchar(4096);
                        SET uid_list = CC_Recruitment_Request_Get_Related_Activities(recruitment_id);
                        IF isnull(uid_list)THEN
                            SELECT null into result;
                        ELSE
                            SELECT date_entered into result FROM cc_recruitment_activity WHERE FIND_IN_SET(id, uid_list) AND deleted=0 ORDER BY date_entered DESC LIMIT 1;
                        END IF;
                        RETURN result;
                    END;";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/***
 * Create a db function to calculate the Last Job Offer Status
 */
private function declareFunctionCC_Recruitment_Request_Last_Job_Offer_StatusQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Recruitment_Request_Last_Job_Offer_Status('')";
            if($db->query($sql,false,'Creating CC_Recruitment_Request_Last_Job_Offer_Status',true)){
                return;
            }

            $sql = "create function CC_Recruitment_Request_Last_Job_Offer_Status(recruitment_id varchar(40)) returns varchar(100)
                    BEGIN
                        DECLARE result varchar(100);
                        DECLARE uid_list varchar(4096);
                        SET uid_list = CC_Recruitment_Request_Get_Related_Activities(recruitment_id);
                        IF isnull(uid_list)THEN
                            SELECT null into result;
                        ELSE
                            SELECT CONCAT(name,' (',date_entered,')') into result FROM cc_recruitment_activity WHERE FIND_IN_SET(id, uid_list) AND related_module = 'CC_Job_Offer' AND deleted=0 ORDER BY date_entered DESC LIMIT 1;
                        END IF;
                        RETURN result;
                    END;";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/****
 * Create a db function to calculate the Get json Request Closed STATUS
 */
private function declareFunctionCC_Recruitment_Request_ClosedQuery()
{
    global $sugar_config;
    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    switch ($sugar_config['dbconfig']['db_type']) {
        case 'mssql':
            $sql = '';
            break;
        case 'mysql':
        default:
            $sql = "SELECT CC_Recruitment_Request_Closed('')";
            if($db->query($sql,false,'Creating CC_Recruitment_Request_Closed',true)){
                return;
            }

            $sql = "create function CC_Recruitment_Request_Closed(item_id varchar(40)) returns varchar(200)
                    BEGIN
                        DECLARE recruitment_id varchar(40);
                        DECLARE applications longtext;
                        DECLARE total int;
                        DECLARE approved int;
                        DECLARE lost int;
                        DECLARE inprogress int;
                        DECLARE related_ids text;
                        SET total = 0;
                        SET approved = 0;
                        SET lost = 0;
                        SET inprogress = 0;
                        SET related_ids = CC_Recruitment_Request_Get_Modules_Related_Ids('CC_Job_Applications',item_id);
                        SELECT
                           item_id,
                           GROUP_CONCAT(DISTINCT cc_job_applications.id),
                           count(DISTINCT cc_job_applications.id) total,
                           sum(case when aps.closed_state = 1 then 1 else 0 end) Approved,
                           sum(case when aps.closed_state = 2 then 1 else 0 end) NotApproved
                        INTO recruitment_id,applications, total, approved, lost FROM cc_job_applications
                            join cc_job_applications_cc_application_stage_c aps
                                ON cc_job_applications.id = aps.cc_job_applications_cc_application_stagecc_job_applications_ida
                        WHERE FIND_IN_SET(cc_job_applications.id,related_ids) group BY 1 order by 2;
                        RETURN CONCAT('{','\"Approved\"',':', approved,',','\"NotApproved\"',':',lost, ',','\"InProgress\"',':',total-lost-approved, '}');
                    END;";
            break;
    }

    // Perform the query
    $db->query($sql);
}

/**
 * Create the candidate Recruitment Request View on DB
 */
private function createRecruitmentRequestView()
{
    $sql = "create or replace view cc_recruitment_request_view as
            select `cc_recruitment_request`.`id`                                                              AS `id`,
                    `cc_recruitment_request`.`name`                                                            AS `name`,
                    `cc_recruitment_request`.`date_entered`                                                    AS `date_entered`,
                    `cc_recruitment_request`.`date_modified`                                                   AS `date_modified`,
                    `cc_recruitment_request`.`modified_user_id`                                                AS `modified_user_id`,
                    `cc_recruitment_request`.`created_by`                                                      AS `created_by`,
                    `cc_recruitment_request`.`description`                                                     AS `description`,
                    `cc_recruitment_request`.`deleted`                                                         AS `deleted`,
                    `cc_recruitment_request`.`assigned_user_id`                                                AS `assigned_user_id`,
                    `cc_recruitment_request`.`open_positions`                                                  AS `open_positions`,
                    `cc_recruitment_request`.`closed_on`                                                        AS `closed_on`,
                    `cc_recruitment_request`.`closing_reason`                                                  AS `closing_reason`,
                    `cc_recruitment_request`.`closed_recuitment`                                               AS `closed_recruitment`,
                    `CC_Recruitment_Request_Get_Activity_Count`('CC_Candidate',
                                                                `cc_recruitment_request`.`id`)                 AS `total_candidates`,
                    `CC_Recruitment_Request_Get_Activity_Count`('CC_Job_Applications',
                                                                `cc_recruitment_request`.`id`)                 AS `total_applications`,
                    `CC_Recruitment_Request_Get_Activity_Count`('CC_Interviews',
                                                                `cc_recruitment_request`.`id`)                 AS `total_interviews`,
                    `CC_Recruitment_Request_Closed`(`cc_recruitment_request`.`id`)                             AS `json_applications_status`,
                    `CC_Recruitment_Request_Last_Job_Offer_Status`(`cc_recruitment_request`.`id`)              AS `job_offer_status`,
                    `CC_Recruitment_Request_Last_Activity_Date`(`cc_recruitment_request`.`id`) AS `last_activity_date`
            from `cc_recruitment_request`;";

    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $db->query($sql);

}


public function CC_Recruitment_RequestController()
{
    $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
    if (isset($GLOBALS['log'])) {
        $GLOBALS['log']->deprecated($deprecatedMessage);
    } else {
        trigger_error($deprecatedMessage, E_USER_DEPRECATED);
    }
    self::__construct();
}


public function getJobDescription($searchTerm){

    $GetBeans = BeanFactory::getBean('CC_Job_Description');
    $where       = !empty($searchTerm) ? "cc_job_description.name like '%".$searchTerm."%'" : '';
    $beanList    = $GetBeans->get_full_list('name',$where,false,0);
    
    if($beanList){
            foreach($beanList as $key => $item){
                $results[] = (object) [
                    'id'            => $beanList[$key]->id,
                    'name'          => $beanList[$key]->name,
                ];        
            }
        }

    return $results;

}


public function getJobOfferClone($searchTerm){

    $GetBeans = BeanFactory::getBean('CC_Job_Offer');
    $where       = !empty($searchTerm) ? "cc_job_offer.name like '%".$searchTerm."%'" : '';
    $beanList    = $GetBeans->get_full_list('name',$where,false,0);
    
    if($beanList){
            foreach($beanList as $key => $item){
                $results[] = (object) [
                    'id'            => $beanList[$key]->id,
                    'name'          => $beanList[$key]->name,
                ];        
            }
        }

    return $results;

}


public function saveRecruitmentRequest($data){

    if($data['recruitmentID'] != 'null'){
        $RatesBean = BeanFactory::getBean('CC_Recruitment_Request',$data['recruitmentID']); 
    }else{
        $RatesBean = BeanFactory::newBean('CC_Recruitment_Request');
    }

    $RatesBean->name  = $data['position_name'];
    $RatesBean->description  = $data['description'];
    $RatesBean->work_time_id = $data['work_time_id'];
    $RatesBean->currency_id  = $data['currency_id'];
    $RatesBean->open_positions  = $data['open_positions'];
    $idnew = $RatesBean->save();

    if($RatesBean){

        $cc_recruitment_request_accounts = 'cc_recruitment_request_accounts';
        $cc_recruitment_request_project  = 'cc_recruitment_request_project';
        $cc_recruitment_request_cc_job_description = 'cc_recruitment_request_cc_job_description'; 
        $cc_recruitment_request_cc_profile = 'cc_recruitment_request_cc_profile';

        $RatesBean->load_relationship($cc_recruitment_request_accounts);
        $RatesBean->cc_recruitment_request_accounts->delete($RatesBean->id);
        $idsAccount = explode(',',$data['account']);
        for($i=0; $i < count($idsAccount); $i ++){
            $RatesBean->$cc_recruitment_request_accounts->add($idsAccount[$i]);
        }

        $RatesBean->load_relationship($cc_recruitment_request_project);
        $RatesBean->cc_recruitment_request_project->delete($RatesBean->id);
        $RatesBean->$cc_recruitment_request_project->add($data['project']);

        $IDRR = $data['recruitmentID'] == "null" ? $idnew : $data['recruitmentID']; 
        $JobDescProfileDifference = new JobDescriptionProfileDifferenceHandler($data['charge'],$IDRR);
        $dataDifference = $JobDescProfileDifference->computesDifferenceProfile();
      
        $RatesBean->load_relationship($cc_recruitment_request_cc_job_description);
        $RatesBean->cc_recruitment_request_cc_job_description->delete($RatesBean->id);
        $RatesBean->$cc_recruitment_request_cc_job_description->add($data['charge']);

        $registry = (new CC_Recruitment_Activity_Handler($RatesBean,"Recruitment request was created","A new Recruitment request was created: ".$data['position_name']))->saveRecruitmentActivity();

        return  $idnew;
    }else{
        return 0;
    }


}


public function getRecruitmentRequest($data){

    $Beans = BeanFactory::getBean('CC_Recruitment_Request', $data['RecruitmentID']);
    
    $Beans->load_relationships();

    $Beans->load_relationship("cc_recruitment_request_accounts"); 
    $dataAccount = $Beans->cc_recruitment_request_accounts->get();

    $Beans->load_relationship("cc_recruitment_request_project"); 
    $dataProject = current($Beans->cc_recruitment_request_project->getBeans());

    $Beans->load_relationship("cc_recruitment_request_cc_job_description"); 
    $dataDescription = current($Beans->cc_recruitment_request_cc_job_description->getBeans());

    $Beans->load_relationship("cc_recruitment_request_cc_profile"); 
    $dataProfile = $Beans->cc_recruitment_request_cc_profile->getBeans();

    $Beans->load_relationship("cc_recruitment_request_cc_employee_information"); 
    $dataEmployeeInformation = current($Beans->cc_recruitment_request_cc_employee_information->getBeans());

    $Beans->load_relationship("cc_recruitment_request_cases"); 
    $dataCase = current($Beans->cc_recruitment_request_cases->getBeans());
    if($dataCase){ 
        $BeansUser = BeanFactory::getBean('Users', $dataCase->assigned_user_id);
    }

    $results_profile = [];
    if($dataProfile){
        foreach($dataProfile as $key => $item){
            $results_profile[] = (object) [
                'id'            => $dataProfile[$key]->id,
                'name'          => $dataProfile[$key]->name,
            ];        
        }
    }

    $results[] = (object)[  'ids_account' => $dataAccount,
                            'id_project' => $dataProject->id, 'name_project' => $dataProject->name, 
                            'id_job_decription' => $dataDescription->id, 'name_job_decription' => $dataDescription->name,
                            'id_employee_information' => $dataEmployeeInformation->id, 'name_employe_information' => $dataEmployeeInformation->name,
                            'id_assigned_to_id' => $BeansUser->id, 'assigned_to_name' => $BeansUser->name,  
                            'data_profile' =>  $results_profile,
                            'closing_reason' => $Beans->closing_reason, 'closed_recuitment' => boolval($Beans->closed_recuitment),
                            'closed_on' => $Beans->closed_on,
                            ];

    return $results;

}

public function closeRecruitmentAndCase($data)
{
    $state = "Closed";
    $status = "Closed_Closed";

    $recruitmentRequestBean = BeanFactory::getBean('CC_Recruitment_Request', $data["recruitment_id"]);
    if(!$recruitmentRequestBean) return "The Recruitmen Request Id don't exist.";

    $recruitmentRequestBean->load_relationship("cc_recruitment_request_cases"); 
    $caseIds = $recruitmentRequestBean->cc_recruitment_request_cases->get();
    if(is_null($caseIds) || count($caseIds) == 0) return "Error the recruitment don't have case.";

    for($i = 0; $i < count($caseIds); $i++){
        $caseRelatedBean = BeanFactory::getBean('Cases', $caseIds[$i]);
        $caseRelatedBean->state = $state;
        $caseRelatedBean->status = $status;
        $caseRelatedBean->save();
    }
    
    $recruitmentRequestBean->closing_reason = $data["closing_reason"];
    $recruitmentRequestBean->closed_on = $data["closed_on"];
    $recruitmentRequestBean->closed_recuitment = 1;
    $recruitmentRequestBean->save();

    return 1;
}

public function getProfile($searchTerm = ''){

    $GetProfile  = BeanFactory::getBean('CC_Profile');
    $where       = !empty($searchTerm) ? "cc_profile.name like '%".$searchTerm."%' and cc_profile.systemonly = 0" : 'cc_profile.systemonly = 0';
    $beanList    = $GetProfile->get_full_list('name',$where,false,0);
    
    if($beanList){
                    
            foreach($beanList as $key => $item){
                $results[] = (object) [
                    'id'            => $beanList[$key]->id,
                    'name'          => $beanList[$key]->name,
                ];        
            }
        }

    return $results;

}

public function getSkill($searchTerm){

    $GetBeans = BeanFactory::getBean('CC_Skill');
    $where       = !empty($searchTerm) ? "cc_skill.name like '%".$searchTerm."%'" : '';
    $beanList    = $GetBeans->get_full_list('name',$where,false,0);
    
    if($beanList){
            foreach($beanList as $key => $item){
                $results[] = (object) [
                    'id'            => $beanList[$key]->id,
                    'name'          => $beanList[$key]->name,
                ];        
            }
        }

    return $results;

}

public function getQualification($searchTerm,$includeAll=true){
    $justFavourites = "";
    if(!$includeAll){
        $justFavourites = "cc_qualification.favourite = 1";
    }
    $GetBeans = BeanFactory::getBean('CC_Qualification');
    $where       = !empty($searchTerm) ? "cc_qualification.name like '%".$searchTerm."%' and ".$justFavourites : empty($justFavourites) ? "" : " ".$justFavourites;
    $beanList    = $GetBeans->get_full_list('name',$where,false,0);
    
    if($beanList){
            foreach($beanList as $key => $item){
                $results[] = (object) [
                    'id'            => $beanList[$key]->id,
                    'name'          => $beanList[$key]->name,
                ];        
            }
        }

    return $results;

}


public function getProfileSkills($inputpProfileIds = []){

    $result = array();
    $profileIds = Array();

    foreach ($inputpProfileIds as $key => $value) {
        if (strstr($key,'profile')){
            $extract_id = explode("|",$value);
            array_push($profileIds,$extract_id[0]);
            } else {
            continue;
            }
    }
        
            
    if($profileIds){
        
        $cc_profile_cc_skill = "cc_profile_cc_skill";
        for ($i=0; $i < count($profileIds); $i++) { 
            
                $ProfileRelationsTable = BeanFactory::getBean('CC_Profile',$profileIds[$i]);
                $ProfileRelationsTable->load_relationship($cc_profile_cc_skill);
                $skillFields = $ProfileRelationsTable->cc_profile_cc_skill->getBeans();
                    
                if($skillFields) { 
                    foreach($skillFields as $key => $item){
                        $skillFieldsRelations = new CC_SkillCC_ProfileRelationship();
                        $skillFieldsRelations = $skillFieldsRelations->get_relation_row($profileIds[$i], $skillFields[$key]->id);
                    
                        $array = array();
                        $array['id_skills']           = $skillFields[$key]->id;
                        $array['id_profile']          = $ProfileRelationsTable->id;
                        $array['name_profile']        = $ProfileRelationsTable->name;
                        $array['object_name_profile'] = $ProfileRelationsTable->object_name;
                        $array['object_name_skills']  = $skillFields[$key]->object_name;
                        $array['name']                = $skillFields[$key]->name;
                        $array['date_entered']        = $skillFields[$key]->date_entered;
                        $array['date_modified']       = $skillFields[$key]->date_modified;
                        $array['description']         = $skillFields[$key]->description;
                        $array['skill_type']          = $skillFields[$key]->skill_type;
                        $array['rating']              = $skillFieldsRelations->rating ?? "";
                        $array['years']               = $skillFieldsRelations->years ?? "";
                        $result[] = $array; 
                        
                    }
                    
                }    
        }
        }


        $json_data = array(
        "data" => $result
        );

        return $json_data;
    }


    public function GetProfileQualifications($inputpProfileIds = []){

    $result = array();
    $profileIds = Array();

    foreach ($inputpProfileIds as $key => $value) {
        if (strstr($key,'profile')){
            $extract_id = explode("|",$value);
            array_push($profileIds,$extract_id[0]);
            } else {
            continue;
            }
    }
        
    if($profileIds){ 
        $cc_profile_cc_qualification = "cc_profile_cc_qualification";
        for ($i=0; $i < count($profileIds); $i++) { 
        $ProfileRelationsTable = BeanFactory::getBean('CC_Profile',$profileIds[$i]);
        $ProfileRelationsTable->load_relationship($cc_profile_cc_qualification);
        $qualificationsFields = $ProfileRelationsTable->cc_profile_cc_qualification->getBeans();
    
            if($qualificationsFields){
            foreach($qualificationsFields as $key => $item){
                $array = array();
                $array['id_qualifications']  = $qualificationsFields[$key]->id;
                $array['name_profile']       = $ProfileRelationsTable->name;
                $array['id_profile']         = $ProfileRelationsTable->id;
                $array['object_name_profile']       = $ProfileRelationsTable->object_name;
                $array['object_name_qualifications'] = $qualificationsFields[$key]->object_name;
                $array['name']               = $qualificationsFields[$key]->name;
                $array['date_entered']       = $qualificationsFields[$key]->date_entered;
                $array['date_modified']      = $qualificationsFields[$key]->date_modified;
                $array['description']        = $qualificationsFields[$key]->description;
                $array['mininum_requiered']  = $qualificationsFields[$key]->mininum_requiered;
        
                $result[] = $array;
            }
            }       
        }
        
        }

        $json_data = array(
        "data" => $result
        );

        return $json_data;

    }


    public function getStepRecruitmentRequest($data){

    $RatesBean = BeanFactory::getBean('CC_Recruitment_Request',$data['RecruitmentID']);

    $RatesBean->load_relationship("cc_recruitment_request_cc_profile"); 
    $dataProfile = $RatesBean->cc_recruitment_request_cc_profile->getBeans();

    $RatesBean->load_relationship("cc_recruitment_request_cases"); 
    $dataCase = current($RatesBean->cc_recruitment_request_cases->getBeans());

    $RatesBean->load_relationship("cc_recruitment_request_cc_job_description"); 
    $JobDescriptionRR = current($RatesBean->cc_recruitment_request_cc_job_description->getBeans());


    $JobDescProfileDifference = new JobDescriptionProfileDifferenceHandler($JobDescriptionRR->id,$data['RecruitmentID']);
    $dataDifference = $JobDescProfileDifference->getDifferenceProfile();

    $step1 = ($RatesBean) ? 1 : 0;
    $step2 = ($dataProfile && $dataDifference == 1) ? 1 : 0;
    $step3 = ($dataCase) ? 1 : 0;

    $sql = "SELECT COUNT(*) AS result FROM cc_job_offer_cc_recruitment_request_c r
            WHERE r.deleted = 0 AND r.cc_job_offer_cc_recruitment_requestcc_recruitment_request_idb  = '".$data['RecruitmentID']."' ";

    $db = DBManagerFactory::getInstance();
    $result = $db->fetchOne($sql);


    $json_data = array(
        "step1" => $step1,
        "step2" => $step2,
        "step3" => $step3,
        "has_a_job_offer" => intval($result['result'])
    );

    return $json_data;


    }


    public function getValidationRecruitmentRequest($data){

    $RatesBean = BeanFactory::getBean('CC_Recruitment_Request',$data['RecruitmentID']);

    if($RatesBean){
        return 1;
    }else{
        return 0;
    }

    }


    public function editRecruitmentRequest($data){

    $RatesBean = BeanFactory::getBean('CC_Recruitment_Request',$data['recruitment_id']);

    if($RatesBean){
        $cc_recruitment_request_cc_profile = 'cc_recruitment_request_cc_profile';

        $RatesBean->load_relationship($cc_recruitment_request_cc_profile);
        $RatesBean->cc_recruitment_request_cc_profile->delete($RatesBean->id);
        /// addd profile_existing
        foreach ($data['options'] as $key => $value) {
            if (strstr($key,'profile')){
                $extract_id = explode("|",$value);
                $RatesBean->$cc_recruitment_request_cc_profile->add($extract_id[0]);
            }
        }

            /// addd profile_new
        $id_skill   = $data['id_skill'];
        $year_skill = $data['year_skill'];
        $rating_skill = $data['rating_skill'];
        $id_qualification    = $data['id_qualification'];

        if($data['id_skill'] != "" || $data['id_qualification'] != ""){
            
            $BeanNew = BeanFactory::getBean('CC_Profile');
            $name_profile = "Profile name ".$data['position_name']." ".$data['recruitment_id'];
            $BeanNew = $BeanNew->retrieve_by_string_fields(
                array(
                    'name' =>  $name_profile
                )
            );

            if(!is_null($BeanNew)){
                $BeanNew->name  = $name_profile;
                $BeanNew->systemonly = 1;
                $id_profile_new = $BeanNew->save();
                
            }else{
                $BeanNewInsert = BeanFactory::newBean('CC_Profile');
                $BeanNewInsert->name  = $name_profile;
                $BeanNewInsert->systemonly = 1;
                $id_profile_new = $BeanNewInsert->save();
                
            }


            for ($i=0; $i < count($id_skill); $i++) { 
                $insert = "INSERT INTO cc_profile_cc_skill_c(id,date_modified,cc_profile_cc_skillcc_profile_ida,cc_profile_cc_skillcc_skill_idb,rating,years,amount) VALUES (UUID(),NOW(),'".$id_profile_new."','".$id_skill[$i]."', ".$rating_skill[$i].",  ".$year_skill[$i].",0);";
                $db = DBManagerFactory::getInstance();
                $db->query($insert);
            }
                
            for ($i=0; $i < count($id_qualification); $i++) { 
                $insert_q = "INSERT INTO cc_profile_cc_qualification_c(id,date_modified,cc_profile_cc_qualificationcc_profile_ida,cc_profile_cc_qualificationcc_qualification_idb) VALUES (UUID(),NOW(),'".$id_profile_new."','".$id_qualification[$i]."');";
                
                $db = DBManagerFactory::getInstance();
                $db->query($insert_q);
            }

            $RatesBean->load_relationship($cc_recruitment_request_cc_profile);
            $RatesBean->$cc_recruitment_request_cc_profile->add($id_profile_new);
        }

        return 1;
    }else{
        return 0;
    }


}

public function getListRequiredQualifications($data){

    global $app_list_strings;
    $result = array();
    $array['actual_qualification_list'] = $app_list_strings['actual_qualification_list'];
    $result[] = $array;     

    $json_data = array(
        "data" => $result
    );

    return $json_data;
}


public function getPriority(){

    global $app_list_strings;
    $result = array();
    $array['case_priority_dom'] = $app_list_strings['case_priority_dom'];
    $result[] = $array;     

    $json_data = array(
        "data" => $result
    );

    return $json_data;
}


public function getAssignedTo($searchTerm){

    $GetEmployee  = BeanFactory::getBean('Users');
    $where       = !empty($searchTerm) ? "CONCAT_WS(' ',users.first_name,users.last_name) LIKE '%".$searchTerm."%'" : '';
    $beanList    = $GetEmployee->get_full_list('name',$where,false,0);

    if($beanList){
        foreach($beanList as $key => $item){
            $results[] = (object) [
                'id'            => $beanList[$key]->id,
                'name'          => $beanList[$key]->name,
            ];        
        }
    }

    return $results;

}


public function createCaseRecruitment($data){

    $sql = "SELECT COALESCE(r.cc_recruitment_request_casescases_idb,'') AS record FROM cc_recruitment_request_cases_c r
    WHERE r.deleted = 0 AND r.cc_recruitment_request_casescc_recruitment_request_ida  = '".$data['recruitment_id']."' ";

    $db = DBManagerFactory::getInstance();
    $result = $db->fetchOne($sql);

    if($result['record'] != ""){
        $Bean = BeanFactory::newBean('Cases');
    }else{
        $Bean = BeanFactory::getBean('Cases',$result['record']);
    }

    $Bean->name  = $data['name'];
    $Bean->description  = $data['description'];
    //$Bean->account_id = $data['account'];
    $Bean->assigned_user_id  = $data['assigned_to'];
    $Bean->priority  = $data['priority'];
    $Bean->status    = 'Open_Assigned';
    $Bean->type      = 'Administration';
    $idNewCase = $Bean->save();

    if($idNewCase){

        $BeansRecruitment = BeanFactory::getBean('CC_Recruitment_Request', $data['recruitment_id']);
        $BeansRecruitment->assigned_user_id = $data['assigned_to'];
        $BeansRecruitment->save();

        $cc_recruitment_request_cases = 'cc_recruitment_request_cases';
        $cc_recruitment_request_cc_employee_information = 'cc_recruitment_request_cc_employee_information';
        
        $BeansRecruitment->load_relationship($cc_recruitment_request_cases);
        $BeansRecruitment->$cc_recruitment_request_cases->delete($BeansRecruitment->id);
        $BeansRecruitment->$cc_recruitment_request_cases->add($idNewCase);

        $BeansRecruitment->load_relationship("cc_recruitment_request_project");
        $dataProject = current($BeansRecruitment->cc_recruitment_request_project->get());

        if($dataProject){ 
            $BeanProject = BeanFactory::getBean('Project', $dataProject);
            $BeanProject->load_relationship("cc_employee_information_project"); 
            $BeanProjectData = $BeanProject->cc_employee_information_project->get();

            if($BeanProjectData){
                $BeansRecruitment->load_relationship($cc_recruitment_request_cc_employee_information); 
                $BeansRecruitment->cc_recruitment_request_cc_employee_information->delete($BeansRecruitment->id);
                for($i = 0; $i<count($BeanProjectData); $i ++){
                $BeansRecruitment->$cc_recruitment_request_cc_employee_information->add($BeanProjectData[$i]);
                } 
            }
            
        }
        
        $registry = (new CC_Recruitment_Activity_Handler($Bean,"The case is created","The case is created: ".$data['name']))->saveRecruitmentActivity();
        return  1;
    }else{
        return 0;
    }

}


public function search_on_object($Obj,$parent_id){

    // $data = array_column($Obj, 'related_id','related_module');
    //$found_key = array_search($parent_id, $data);

    foreach($Obj as $key => $item){
        if($parent_id == $Obj[$key]->related_id){
            if($Obj[$key]->related_module == 'CC_Job_Applications'){
                return $Obj[$key]->related_module ."_".$Obj[$key]->id;
            }else{
                return $Obj[$key]->related_module;
            }
        }
    }

}

public function change_label($label_module){

    switch ($label_module) {
        case 'Case':
            $label_module_out = "1 - Case processes";
        break;
        case 'CC_Job_Offer':
            $label_module_out = "2 - Job offer process";
        break;
        case 'CC_Job_Applications':
            $label_module_out = " 3 -Job Applications processes";
        break;
        case 'CC_Candidate':
            $label_module_out = "4 - Candidate processes";
        break;
        default:
            $label_module_out = "5 - ".$label_module;
        break;
    }
    return $label_module_out;

}



public function getActionRecruitmentRequest($data){
    
    global $app_list_strings;
    $result = array();
    $result_widgets = array();
    $unique_job_application = array();
    $candidates_registered = 0;
    $candidates_interviewed = 0;
    $candidates_rejected = 0;
    $candidates_hired = 0;
    $cc_recruitment_activity_cc_recruitment_request = 'cc_recruitment_activity_cc_recruitment_request';          
    $Recruitment = BeanFactory::getBean('CC_Recruitment_Request',$data['IdRecruitmentRequest']);

    if($Recruitment){ 
        $Recruitment->load_relationship($cc_recruitment_activity_cc_recruitment_request);


        
        $DataActivity = $Recruitment->$cc_recruitment_activity_cc_recruitment_request->getBeans();
        
        $date_order  = array_column($DataActivity, 'event_date');
        array_multisort($date_order, SORT_ASC, $DataActivity);
        $date_interval_before = "";
        
        if($DataActivity){ 
            foreach($DataActivity as $key => $item){
                $array = array();
                // data for widgets
                $data_result = json_decode(html_entity_decode($DataActivity[$key]->event_data));
                if(empty($DataActivity[$key]->parent_index)){
                    $array['field_group'] = $this->change_label($DataActivity[$key]->related_module); 
                }else{
                    $array['field_group'] = $this->change_label($this->search_on_object($DataActivity,$DataActivity[$key]->parent_index));
                }
                $array['id']             = $DataActivity[$key]->id;
                $array['name']           = $DataActivity[$key]->name;
                $array['date_entered']   = $DataActivity[$key]->date_entered;
                $array['date_modified']  = $DataActivity[$key]->date_modified;
                $array['description']    = $DataActivity[$key]->description;
                $array['related_module'] = $DataActivity[$key]->related_module;
                $array['related_id']     = $DataActivity[$key]->related_id;
                $array['parent_index']   = $DataActivity[$key]->parent_index;
                $array['event_date']     = $DataActivity[$key]->event_date;
                $array['event_data']     = $DataActivity[$key]->event_data;
                $array['comment']        = isset($data_result->note) ? $data_result->note : "";
                $dateTimeObject1 = date_create(date("Y-m-d", strtotime($date_interval_before))); 
                $dateTimeObject2 = date_create(date("Y-m-d", strtotime($DataActivity[$key]->date_entered))); 
                $difference = date_diff($dateTimeObject1, $dateTimeObject2); 
                $array['time_lapsed'] = ($date_interval_before != "") ? $difference->format('%R%a days') : "+0 days";
                $result[] = $array;

                $date_interval_before = $DataActivity[$key]->date_entered;

               
            
                if($DataActivity[$key]->related_module == 'CC_Candidate'){
                    $candidates_registered = $candidates_registered + 1;
                }

                if($DataActivity[$key]->related_module == 'CC_Job_Application_Interview'){
                    if(!in_array($DataActivity[$key]->parent_index, $unique_job_application)){
                        $candidates_interviewed = $candidates_interviewed + 1;
                        array_push($unique_job_application,$DataActivity[$key]->parent_index);
                    }
                }

                if($DataActivity[$key]->related_module == 'CC_Job_Applications_CC_application_stage' && isset($data_result->date_of_admission) && isset($data_result->approved)){
                    if($data_result->approved == "Not Approved" || $data_result->approved == "Close Lost"){
                        $candidates_rejected = $candidates_rejected + 1;
                    }else{
                        $candidates_hired = $candidates_hired + 1;
                    }
                }
                
            } // end foreach  
            
            $array_data_widgets = array();
            $array_data_widgets['candidates_registered']   = $candidates_registered;   
            $array_data_widgets['candidates_interviewed']  = $candidates_interviewed;
            $array_data_widgets['candidates_rejected']     = $candidates_rejected;
            $array_data_widgets['candidates_hired']        = $candidates_hired;
            $result_widgets[] = $array_data_widgets;
            
            }
        }

        $json_data = array(
           "data" => $result,
           "widgets" => $result_widgets
        );

        return $json_data;
    }


    public function createCloneJobOffer($data){
    
        $BeanJobOffer = BeanFactory::getBean('CC_Job_Offer',$data['id_job_offer']);
        $cc_job_offer_cc_recruitment_request = 'cc_job_offer_cc_recruitment_request';
        $cc_job_offer_accounts = 'cc_job_offer_accounts';
        $cc_recruitment_request_accounts = 'cc_recruitment_request_accounts';
        $cc_profile_cc_job_offer = 'cc_profile_cc_job_offer';
        $cc_recruitment_request_cc_profile = 'cc_recruitment_request_cc_profile';
        //$cc_recruitment_request_project  = 'cc_recruitment_request_project';
        //$cc_recruitment_request_cc_job_description = 'cc_recruitment_request_cc_job_description'; 
        
        $BeanJobOffer->load_relationship($cc_job_offer_cc_recruitment_request);
        $dataJobOfferRR = $BeanJobOffer->cc_job_offer_cc_recruitment_request->get();

        if($dataJobOfferRR && $data['option_clone'] == 1){
            return "exists";
        }
        
        
        $RRBean = BeanFactory::newBean('CC_Recruitment_Request');
        $RRBean->name  = $BeanJobOffer->name;
        $RRBean->description  = $BeanJobOffer->description;
        $RRBean->open_positions  = 1;
        $idNew = $RRBean->save();

        if($idNew){
          
            $RRBean->load_relationship($cc_recruitment_request_accounts);
            $BeanJobOffer->load_relationship($cc_job_offer_accounts);
            $DataJobOfferAccount = $BeanJobOffer->cc_job_offer_accounts->get();
            foreach($DataJobOfferAccount as $key => $item){
                $RRBean->$cc_recruitment_request_accounts->add($DataJobOfferAccount[$key]);
            }

            $BeanJobOffer->load_relationship($cc_profile_cc_job_offer);
            $RRBean->load_relationship($cc_recruitment_request_cc_profile);
            $DataJobOfferProfile = $BeanJobOffer->cc_profile_cc_job_offer->get();

            if($data['option_clone'] == 1){
                $BeanJobOffer->$cc_job_offer_cc_recruitment_request->add($idNew);
                foreach($DataJobOfferProfile as $key => $item){
                    $RRBean->$cc_recruitment_request_cc_profile->add($DataJobOfferProfile[$key]);
                }
                // create lof of activity 
                $registry = (new CC_Recruitment_Activity_Handler($RRBean,"Recruitment request was created cloned","A new Recruitment request was created cloned: ".$BeanJobOffer->name))->reconstruct_log_data($idNew,$data['id_job_offer']);
            }

            if($data['option_clone'] == 2){
                $interval = 1;
                foreach($DataJobOfferProfile as $key => $item){
                    $BeanProfile = BeanFactory::newBean('CC_Profile');
                    $BeanProfile->name = "Profile name ".$BeanJobOffer->name." ".$idNew. "_".$interval;
                    $BeanProfile->systemonly = 1;
                    $idProfile = $BeanProfile->save();
                    if($idProfile){
                        $RRBean->$cc_recruitment_request_cc_profile->add($idProfile);

                        $insert_skills = "INSERT INTO cc_profile_cc_skill_c
                                            (id,date_modified,cc_profile_cc_skillcc_profile_ida,cc_profile_cc_skillcc_skill_idb,type,amount,years,rating)
                                          SELECT 
                                            UUID(),NOW(),'".$idProfile."',cc_profile_cc_skillcc_skill_idb,type,amount,years,rating
                                          FROM 
                                            cc_profile_cc_skill_c p_s
                                          WHERE 
                                            p_s.deleted = 0 AND p_s.cc_profile_cc_skillcc_profile_ida = '".$DataJobOfferProfile[$key]."';";
                
                        $db = DBManagerFactory::getInstance();
                        $db->query($insert_skills);

                        $insert_qualifications = "INSERT INTO cc_profile_cc_qualification_c
                                                        (id,date_modified,cc_profile_cc_qualificationcc_profile_ida,cc_profile_cc_qualificationcc_qualification_idb)
                                                    SELECT 
                                                        UUID(),NOW(),'".$idProfile."',cc_profile_cc_qualificationcc_qualification_idb
                                                    FROM 
                                                        cc_profile_cc_qualification_c p_s
                                                    WHERE 
                                                        p_s.deleted = 0 AND p_s.cc_profile_cc_qualificationcc_profile_ida = '".$DataJobOfferProfile[$key]."';";
                      
                        $db = DBManagerFactory::getInstance();
                        $db->query($insert_qualifications);

                    }
                    $interval = $interval +1;
                }

            }

            return  $idNew;
        }else{
            return 0;
        }


    }


    public function cloneRecruitmentRequest($data){

    $Beans = BeanFactory::getBean('CC_Recruitment_Request', $data->Id);
    
    $Beans->load_relationships();

    $Beans->load_relationship("cc_recruitment_request_accounts"); 
    $dataAccount = current($Beans->cc_recruitment_request_accounts->getBeans());

    $Beans->load_relationship("cc_recruitment_request_project"); 
    $dataProject = current($Beans->cc_recruitment_request_project->getBeans());

    $Beans->load_relationship("cc_recruitment_request_cc_job_description"); 
    $dataDescription = current($Beans->cc_recruitment_request_cc_job_description->getBeans());

    $Beans->load_relationship("cc_recruitment_request_cc_profile"); 
    $dataProfile = $Beans->cc_recruitment_request_cc_profile->getBeans();

    $Beans->load_relationship("cc_recruitment_request_cc_employee_information"); 
    $dataEmployeeInformation = current($Beans->cc_recruitment_request_cc_employee_information->getBeans());

    $NewBean = BeanFactory::newBean('CC_Recruitment_Request');
    $NewBean->name  = $data->Name;
    $NewBean->description  = $Beans->description;
    $NewBean->work_time_id = $Beans->work_time_id;
    $NewBean->currency_id  = $Beans->currency_id;
    $NewBean->open_positions  = $Beans->open_positions;
    $idnew = $NewBean->save();

    

    if($NewBean){
        $cc_recruitment_request_accounts = 'cc_recruitment_request_accounts';
        $cc_recruitment_request_project  = 'cc_recruitment_request_project';
        $cc_recruitment_request_cc_job_description = 'cc_recruitment_request_cc_job_description'; 
        $cc_recruitment_request_cc_profile = 'cc_recruitment_request_cc_profile'; 
        $cc_recruitment_request_cc_employee_information = 'cc_recruitment_request_cc_employee_information'; 

        $NewBean->load_relationship($cc_recruitment_request_accounts);
        $NewBean->$cc_recruitment_request_accounts->add($dataAccount->id);

        $NewBean->load_relationship($cc_recruitment_request_project);
        $NewBean->$cc_recruitment_request_project->add($dataProject->id);

        $NewBean->load_relationship($cc_recruitment_request_cc_job_description);
        $NewBean->$cc_recruitment_request_cc_job_description->add($dataDescription->id);

        $NewBean->load_relationship($cc_recruitment_request_cc_profile);

        if($dataProfile){
            foreach($dataProfile as $key => $item){
                $NewBean->$cc_recruitment_request_cc_profile->add($dataProfile[$key]->id);     
            }
        }

        $NewBean->load_relationship($cc_recruitment_request_cc_employee_information);
        
        if($dataEmployeeInformation){
            foreach($dataEmployeeInformation as $key => $item){
                $NewBean->$cc_recruitment_request_cc_employee_information->add($dataEmployeeInformation[$key]->id);     
            }
        }

        $registry = (new CC_Recruitment_Activity_Handler($NewBean,"The recruitment request was clonated","Recruitment request was clonated from: ".$Beans->name))->saveRecruitmentActivity();


        return  $idnew;
    }else{
        return 0;
    }


}


public function getRecruitmentRequestDataAll($start, $length, $search, $draw, $column_orden, $column_orden_type, $column_order_data, $filterColumData, $hide_closed){

    // search 
    $srch = "  r_r.deleted = 0 ".($hide_closed ? "AND r_r.closed_recruitment = 0 " : "");
    if($search){
        $srch.= "AND (r_r.name LIKE '%".trim($search)."%' OR 
                        u.user_name LIKE '".trim($search)."%' OR
                        ac.name LIKE '".trim($search)."%'
                        )";
    }

    // filters columns
    $order_by = ($draw != "1") ? $column_order_data ." ". $column_orden_type : " r_r.date_entered DESC";
    
    /***************************************************************************
     This was done like this because I found an error when using two tables with 
        the same field name, the aliases are not read by the where, and when custom 
        filtering gives an error, this solves it
    *****************************************************************************/

    // SELECT FILDS  key = sql, value = alias
    $query_select_array = array(
        'r_r.id'             => 'id_rec_req',
        'r_r.name'           => 'name_rec_req',
        'r_r.description'    => 'description',
        'r_r.open_positions'    => 'open_positions',
        'r_r.closed_on'       => 'closed_on',
        'r_r.closing_reason' => 'closing_reason',
        'r_r.closed_recruitment' => 'closed_recruitment',
        'r_r.job_offer_status'         => 'job_offer_status',
        'r_r.total_candidates'         => 'total_candidates',
        'r_r.total_applications'       => 'total_applications',
        'r_r.total_interviews'         => 'total_interviews',
        'r_r.json_applications_status' =>  'json_applications_status',
        'r_r.last_activity_date'       => 'last_activity_date',
        'COALESCE(u.user_name,"")'     =>  'user_assigned',
        'u.id'                         =>  'id_user',
        'group_concat(CONCAT_WS("|",ac.id,REPLACE(ac.name, ",", " ")))' =>  'data_accounts',
        'pro.name'                     =>  'name_project',
        'pro.id'                       =>  'id_project',
        'des.name'                     =>  'name_description',
        'des.id'                       =>  'id_description',
        'COALESCE(ofer.is_published,0)' =>  'is_published' 
    );

    $query_select_sql = "";
    foreach ($query_select_array as $key => $value) {
        $query_select_sql.=" $key $value,";
    }
    $query_select_sql = substr($query_select_sql, 0, -1);

    // custom filters 
    $query_custom_filters = "";
    for ($i=0; $i < count($filterColumData); $i++) { 
            $field_name     = $_POST['columns'][$filterColumData[$i]['column']]['data'];
            $field_name_array = array_flip($query_select_array);
            $field_name_sql   = $field_name_array[$field_name];
            $field_value      = $filterColumData[$i]['text'];

            $query_custom_filters.= " AND $field_name_sql = '".$field_value."' ";
    } 


    $sql = "SELECT 
                    $query_select_sql
            FROM 
                    cc_recruitment_request_view r_r
            LEFT JOIN 
                cc_recruitment_request_accounts_c r_r_p
                    ON r_r_p.cc_recruitment_request_accountscc_recruitment_request_ida = r_r.id AND r_r_p.deleted = 0
            LEFT JOIN 
                accounts ac
                    ON r_r_p.cc_recruitment_request_accountsaccounts_idb = ac.id
            LEFT JOIN 
                cc_recruitment_request_project_c r_r_proj
                    ON r_r_proj.cc_recruitment_request_projectcc_recruitment_request_ida = r_r.id AND r_r_proj.deleted = 0
            LEFT JOIN 
                project pro
                    ON r_r_proj.cc_recruitment_request_projectproject_idb = pro.id
            LEFT JOIN 
                cc_recruitment_request_cc_job_description_c r_r_des
                    ON r_r_des.cc_recruit6751request_ida = r_r.id AND r_r_des.deleted = 0
            LEFT JOIN 
                cc_job_description des
                    ON r_r_des.cc_recruitment_request_cc_job_descriptioncc_job_description_idb = des.id 
            LEFT JOIN 
                users u
                    ON r_r.assigned_user_id = u.id 
            LEFT JOIN 
                cc_job_offer_cc_recruitment_request_c r_r_job_ofer
                    ON r_r_job_ofer.cc_job_offer_cc_recruitment_requestcc_recruitment_request_idb = r_r.id AND r_r_job_ofer.deleted = 0
            LEFT JOIN 
                cc_job_offer ofer
                    ON r_r_job_ofer.cc_job_offer_cc_recruitment_requestcc_job_offer_ida = ofer.id     
            WHERE
                ".$srch."
                ".$query_custom_filters." 
            GROUP BY 
                 r_r.id
            ORDER BY
                    $order_by
            LIMIT 
                $start,$length ";

    $db = DBManagerFactory::getInstance();
    // use this line to remove ONLY_FULL_GROUP_BY on sessions
    //$void = $db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    $rows = $db->query($sql);

    $results = array();
    while ($row = $db->fetchRow($rows)) {
        $is_closed = $row['closed_on'] ? 1: 0;
        $smarty = new Sugar_Smarty();
        $smarty->assign('data',$row['id_rec_req']);
        $smarty->assign('url_edit','index.php?module=CC_Recruitment_Request&return_module=CC_Recruitment_Request&action=EditView&record='.$row['id_rec_req'].'');
        $smarty->assign('url_detail','index.php?module=CC_Recruitment_Request&return_module=CC_Recruitment_Request&action=DetailView&record='.$row['id_rec_req'].'');
        $smarty->assign('closed', $is_closed);
        $viewAction = $smarty->fetch('modules/CC_Recruitment_Request/tpls/actionsDatatables.tpl');
        $array = array();
        $array['id_rec_req']        = $row['id_rec_req'];
        $array['name_rec_req']      = $row['name_rec_req'];
        $array['description']       = $row['description'];
        $array['open_positions']    = $row['open_positions'];
        $array['closed_on']          = $row['closed_on'];
        $array['closing_reason']    = $row['closing_reason'];
        $array['closed_recruitment']=  $row['closed_recruitment'];
        $array['job_offer_status']  = $row['job_offer_status'];
        $array['total_candidates']  = $row['total_candidates'];
        $array['total_applications']= $row['total_applications'];
        $array['total_interviews']  = $row['total_interviews'];
        $array['json_applications_status']   = $row['json_applications_status'];
        $array['user_assigned']     = $row['user_assigned'];
        $array['data_accounts']     = $row['data_accounts'];
        $array['name_project']      = $row['name_project'];
        $array['name_description']  = $row['name_description'];
        $json_application_status    = json_decode($array['json_applications_status']);
        $array['Approved']          = $json_application_status->Approved;
        $array['NotApproved']       = $json_application_status->NotApproved;
        $array['InProgress']        = $json_application_status->InProgress;
        $array['id_user']           = $row['id_user'];
        $array['id_project']        = $row['id_project'];
        $array['id_description']    = $row['id_description'];
        $array['is_published']      = $row['is_published'];
        $array['viewAction']        = $viewAction;

        $dateTimeObject1 = date_create(date("Y-m-d", strtotime($row['last_activity_date']))); 
        $dateTimeObject2 = date_create(date("Y-m-d")); 
        $difference = date_diff($dateTimeObject1, $dateTimeObject2); 
        $array['last_activity_date'] = ($row['last_activity_date'] != "") ? $difference->format('%R%a days') : "+0 days";
        $results[] = $array;
    }


    /*** Query COUNT  ALL Registry****/
        $sql_tot = "SELECT 
                        COUNT(DISTINCT(r_r.id)) AS cant
                FROM 
                    cc_recruitment_request_view r_r
                INNER JOIN 
                    cc_recruitment_request_accounts_c r_r_p
                        ON r_r_p.cc_recruitment_request_accountscc_recruitment_request_ida = r_r.id AND r_r_p.deleted = 0
                INNER JOIN 
                    accounts ac
                        ON r_r_p.cc_recruitment_request_accountsaccounts_idb = ac.id
                INNER JOIN 
                    cc_recruitment_request_project_c r_r_proj
                        ON r_r_proj.cc_recruitment_request_projectcc_recruitment_request_ida = r_r.id AND r_r_proj.deleted = 0
                INNER JOIN 
                    project pro
                        ON r_r_proj.cc_recruitment_request_projectproject_idb = pro.id
                INNER JOIN 
                    cc_recruitment_request_cc_job_description_c r_r_des
                        ON r_r_des.cc_recruit6751request_ida = r_r.id AND r_r_des.deleted = 0
                INNER JOIN 
                    cc_job_description des
                        ON r_r_des.cc_recruitment_request_cc_job_descriptioncc_job_description_idb = des.id 
                LEFT JOIN 
                    users u
                    ON r_r.assigned_user_id = u.id
                LEFT JOIN 
                    cc_job_offer_cc_recruitment_request_c r_r_job_ofer
                    ON r_r_job_ofer.cc_job_offer_cc_recruitment_requestcc_recruitment_request_idb = r_r.id AND r_r_job_ofer.deleted = 0
                LEFT JOIN 
                    cc_job_offer ofer
                    ON r_r_job_ofer.cc_job_offer_cc_recruitment_requestcc_job_offer_ida = ofer.id     
                WHERE
                    ".$srch." 
                    ".$query_custom_filters." 
                    ";
            
        $db = DBManagerFactory::getInstance();
        $result_total = $db->fetchOne($sql_tot);


        $return = array(
        'data' => $results,
        'total_fila' => $result_total['cant'] 
        );

        return $return;

    }


    public function getAccount($searchTerm = ''){
       
        $GetAccounts  = BeanFactory::getBean('Accounts');
        $where       = !empty($searchTerm) ? "accounts.name like '%".$searchTerm."%'" : "";
        $beanList    = $GetAccounts->get_full_list('name',$where,false,0);
        
        if($beanList){
                        
                foreach($beanList as $key => $item){
                    $results[] = (object) [
                        'id'            => $beanList[$key]->id,
                        'name'          => $beanList[$key]->name,
                    ];        
                }
            }

        return $results;
    }

    public function checkProfilePosition($position){

        $getRelationProfile = "cc_job_description_cc_profile";
       
        $Getprofiles  = BeanFactory::getBean('CC_Job_Description', $position);
        $Getprofiles->load_relationship($getRelationProfile);

        
        if($Getprofiles){
            $profiles = $Getprofiles->$getRelationProfile->get();
            return $profiles;

        }

    }



}