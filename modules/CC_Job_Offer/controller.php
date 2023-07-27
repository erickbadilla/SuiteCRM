<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/CC_Profile/controller.php';
require_once 'modules/CC_Job_Offer/CC_Job_OfferCC_ProfileRelationship.php';
require_once 'modules/CC_Job_Offer/CC_Job_OfferCC_Employee_InformationRelationship.php';
require_once 'modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php';
require_once 'modules/CC_Skill/controller.php';
require_once 'custom/Extension/application/Include/cc_recruitment_activity_handler.php';
require_once 'custom/Extension/application/Ext/Language/en_us.Careers.php';

use Api\V8\Config\Common as Common;
use Api\V8\Utilities;

class CC_Job_OfferController extends SugarController{

    const PERMISSION_ERROR_MESSAGE = 'You don\'t have permission to publish this job offer';
    const JOB_OFFER_PUBLISH_OBJECT_RELATION_NAME = 'cc_published_object_cc_job_offer';
    const CAREER_SYSTEM_ACCOUNT_NAME = 'CareersSystemAccount';

    private $careers_default_user = 'ca4ee4s0-cccc-4000-cccc-b352d6b25516';

    /**
     *
     */
    public function __construct(){
        parent::__construct();
        $users_bean = BeanFactory::getBean('Users');
        $users_list = $users_bean->get_list('name',"users.user_name='".self::CAREER_SYSTEM_ACCOUNT_NAME."'",0,1);
        if($users_list && count($users_list['list'])>0){
            $this->careers_default_user = $users_list['list'][0]->id;
        }
    }

    /**
     *
     */
    public function CC_Job_OfferController()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    /**
     * @param string $id
     * @return array|false
     *
     */
    public function getJobOfferRecordById(string $id){

        if($id == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT j.name 'Name', j.id 'Id', j.expire_on 'ExpireOn', j.description 'Description',
            j.contract_type 'ContractType', j.assigned_location 'AssignedLocation', j.file_url 'PublicUrl',
            j.file_mime_type 'FileExtension'    
            FROM ".Common::$joTable." j
            WHERE j.deleted = 0
            AND j.id = '".$id."' ";

        $sql .= " ORDER BY j.name";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $result = $db->fetchOne($sql);

        $result['Attachments'] = self::getJobOfferAttachById($id);

        $result['Interviewers'] = self::getJobOfferInterviewersById($id);

        $profiles = (new CC_ProfileController)->getRecordsByJobOfferId($id);

        $result['Profiles'] = $profiles;
        return $result;
    }

    public function getJobOfferAttachById(string $id){

        if($id == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT j.name 'Title',  j.file_url 'file_Url', j.file_mime_type 'File_Extension'
            FROM ".Common::$joTable." j
            WHERE j.deleted = 0
            AND j.id = '".$id."' ";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);

        $host = $_SERVER['HTTP_HOST'] . '/';
        $request = explode("/", $_SERVER['REQUEST_URI']);
        $host2 = $request[1] . '/';
        
        while ($row = $db->fetchRow($rows)) {
            $fileMime = explode("/", $row["File_Extension"]);
            
            $row["PublicUrl"] = $host.$host2.$row["file_Url"] ;
            $row["FileExtension"] = $fileMime[1] ;

            unset($row["file_Url"]);
            unset($row["File_Extension"]);
            $result[] = $row;
        }

        return $result;
    }

    public function getJobOfferInterviewersById(string $id){

        if($id == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT e.id 'Employee_id', e.current_email 'Email', e.name 'Name' 
                FROM cc_interviewer_cc_job_offer_c ino
                LEFT JOIN cc_interviewer_cc_employee_information_c ine ON ino.cc_interviewer_cc_job_offercc_interviewer_idb=ine.cc_interviewer_cc_employee_informationcc_interviewer_idb
                LEFT JOIN cc_employee_information e ON ine.cc_intervi9533rmation_ida= e.id
                WHERE ino.deleted = 0 AND cc_interviewer_cc_job_offercc_job_offer_ida= '".$id."' ";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);

        while ($row = $db->fetchRow($rows)) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param string $id
     * @return array
     *
     */
    public function getJobOffersByName(string $name){

        $sql = "SELECT j.id, j.name ,  j.expire_on , j.description, j.contract_type, j.assigned_location ".
            "FROM ".Common::$joTable." j WHERE j.deleted = 0 AND j.name like '%".$name."%' ";
        $sql .= " ORDER BY j.expire_on DESC";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();

        $result = [];
        // Perform the query
        $rows = $db->query($sql);
        while ($row = $db->fetchRow($rows)) {
            if(key_exists("id",$row)){
                $profiles = (new CC_ProfileController)->getRecordsByJobOfferId($row['id']);
                $row['Attachments'] = self::getJobOfferAttachById($row['id']);
                $row['Interviewers'] = self::getJobOfferInterviewersById($row['id']);
                $row['profiles'] = $profiles;
            }
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getJobOfferRecords(){
        $sql = "SELECT j.name 'Name', j.id 'Id', j.expire_on 'ExpireOn', j.description 'Description',
            j.contract_type 'ContractType', j.assigned_location 'AssignedLocation'
            FROM ".Common::$joTable." j
            WHERE j.deleted = 0";

        $sql .= " ORDER BY j.name";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            
            $row['Attachments'] = self::getJobOfferAttachById($row['Id']);
            $row['Interviewers'] = self::getJobOfferInterviewersById($row['Id']);

            $profiles = (new CC_ProfileController)->getRecordsByJobOfferId($row['Id']);
            $row['Profiles'] = $profiles;
            $result[] = $row;
        }
        return $result;
    }

    /**
     * @param object $jobOffer
     * @return bool|SugarBean
     */
    public function saveJobOfferRecord(object $jobOffer, $id=null){

        $jobOfferBean = BeanFactory::getBean('CC_Job_Offer',$id);

        if($jobOfferBean===false || is_null($id)){
            $jobOfferBean = BeanFactory::newBean('CC_Job_Offer');
            if(!is_null($id)){
                $jobOfferBean->id= $id;
                $jobOfferBean->new_with_id = true;
            }
        }

        $jobOfferBean->name = $jobOffer->Name;
        $jobOfferBean->description = $jobOffer->Description;
        $jobOfferBean->assigned_location = $jobOffer->AssignedLocation;
        $jobOfferBean->contract_type = $jobOffer->ContractType;
        $jobOfferBean->expire_on = $jobOffer->ExpireOn;
        $jobOfferBean->save();


        if($jobOffer->Profiles) {
            $this->saveJobProfile($jobOfferBean, $jobOffer->Profiles);
        }

        return $jobOfferBean;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $jobProfiles
     * @return array
     */
    public function saveJobProfile(\SugarBean $parentBean, array $jobProfiles){
        global $app_list_strings;
        // this variable is not fully loaded
        $dependency_list = ['required'=>'Required','desired'=>'Desired','inherited'=>'Inherited'];
        $app_list_strings['dependency_list'] = $dependency_list;


        $pjRelation = 'cc_profile_cc_job_offer';

        $parentBean->load_relationship($pjRelation);
        $result = [];

        foreach($jobProfiles as $jobProfile){
            
            $profileBean = Utilities::getCustomBeanByName('CC_Profile', $jobProfile->Profile->Name);
            if (!isset($profileBean)) {
                $newProfile = (new CC_ProfileController)->saveProfileRecord($jobProfile->Profile);
                $profileBean = Utilities::getCustomBeanByName('CC_Profile', $newProfile['Name']);
            }

            if (!array_key_exists(strtolower($jobProfile->Dependency),$app_list_strings['dependency_list'])) {
                throw new \InvalidArgumentException(sprintf(
                    'Dependency with name %s does not exist',
                    $jobProfile->Dependency
                ));
            }

            $parentBean->$pjRelation->add($profileBean);

            $rProfileBean = new CC_Job_OfferCC_ProfileRelationship();

            $relatedRow = $rProfileBean->get_relation_row($parentBean->id, $profileBean->id);

            if ($relatedRow) {
                $relatedRow->dependency = strtolower($jobProfile->Dependency);
                $rSave = $relatedRow->save();
                if($rSave){
                    $result[] = $rSave;
                }
            }

            $result[] = $parentBean;
        }

        return $result;
    }

    /**
     * @param string $record
     * @param string $module
     * @return object
     */
    private function relatedObject($record, string $module){
        return (object) ["record" => $record, "module" => $module];
    }

    /**
     * @param array $actualprofiles
     * @return array
     */
    public function getRelatedRows(array $actualprofiles):array{
        $result = [];
        foreach ($actualprofiles as $jobProfile){
            $result[] = self::relatedObject($jobProfile['Id'],'CC_Job_Profile');
            foreach ($jobProfile['Profile'] as $Profile){
                $result[] = self::relatedObject($Profile['Id'], 'CC_Profile');
                foreach ($Profile['skills'] as $skill) {
                    $result[] = self::relatedObject($skill['Id'], 'CC_Skill');
                }
                foreach ($Profile['qualifications'] as $qualification) {
                    $result[] = self::relatedObject($qualification['Id'], 'CC_Qualification');
                }
            }
        }
        return $result;
    }

    /**
     * @param string $related_offer
     * @param string $module_name
     * @param string $record_id
     * @param $oJobOffer
     * @param bool $imPublishing
     * @return bool false if fail | true success
     */
    public function mark_record_as_published_unpublished(
        string $related_offer,
        string $module_name,
        string $record_id,
        $oJobOffer,
        bool $imPublishing = false):bool
    {
        global $current_user;
        // Get an instance of the database manager

        $db = DBManagerFactory::getInstance();

        $user = BeanFactory::getBean('Users', $current_user->id);

        $relatedBean = BeanFactory::getBean($module_name,$record_id);
        if(property_exists($relatedBean,'published')){
            $isRelatedPublished = $relatedBean->published;
            if($imPublishing){
                if(!$isRelatedPublished){
                    $relatedBean->created_by = $this->careers_default_user;
                    $relatedBean->published = 1;
                    if(property_exists($relatedBean,'is_published')){
                        $relatedBean->is_published = 1;
                    }
                    $relatedBean->save();
                }
                $oPublishedObject = BeanFactory::getBean('CC_Published_Object');
                $oPublishedObject->related_object_id = $record_id;
                $oPublishedObject->deleted = ($imPublishing)?0:1;
                $oPublishedObject->name = $module_name;
                $oPublishedObject->created_by = $this->careers_default_user;
                $oPublishedObject->modified_user_id = $this->careers_default_user;
                $resultPublished = $oPublishedObject->save();
                $rName = self::JOB_OFFER_PUBLISH_OBJECT_RELATION_NAME;
                $oJobOffer->$rName->add($resultPublished);
            } else {
                $rName = self::JOB_OFFER_PUBLISH_OBJECT_RELATION_NAME;
                $offerRelatedRows = $oJobOffer->$rName->get();
                $sql = "SELECT id,related_object_id FROM cc_published_object WHERE related_object_id='".$record_id."' and deleted = 0";
                $rows = $db->query($sql);
                $publishedResult = [];
                while ($row = $db->fetchRow($rows)) {
                    $relatedRecordId = $row['id'];
                    if(in_array($relatedRecordId,$offerRelatedRows)){
                        $oPublishedObject = BeanFactory::getBean('CC_Published_Object',$relatedRecordId);
                        $oPublishedObject->deleted = 1;
                        $oPublishedObject->save();
                    } else {
                        $publishedResult["{$relatedRecordId}"] = $row['related_object_id'];
                    }
                }
                if(count($publishedResult)==0){
                    $relatedBean = BeanFactory::getBean($module_name,$record_id);
                    $relatedBean->created_by = $current_user->id;
                    $relatedBean->published = 0;
                    if(property_exists($relatedBean,'is_published')){
                        $relatedBean->is_published = 0;
                    }
                    $relatedBean->save();
                }
            }
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function action_publish(){
        $record = $_REQUEST['record'];
        $jobOffer = self::getJobOfferRecordById($record);
        $flag = 0;
       
        if(ACLController::checkAccess('CC_Job_Offer', 'edit', true)){
            
            $moduloName = $this->bean->module_name ?? "CC_Job_Offer";
            $oJobOffer = BeanFactory::getBean($moduloName, $record);
          
            $result = self::published_unpublished($record,$jobOffer,$oJobOffer, true);
            if($result){
                $flag = 1;
                $registry = (new CC_Recruitment_Activity_Handler($oJobOffer,"The job offer was published","The job offer was published: ".$oJobOffer->name,null,$oJobOffer->id))->saveRecruitmentActivity();
                if(!empty($_GET['record'])){
                    SugarApplication::appendSuccessMessage('The job offer was published');
                }
            }
        } else {
            SugarApplication::appendErrorMessage(self::PERMISSION_ERROR_MESSAGE);
        }
        if(!empty($_GET['record'])){
             SugarApplication::redirect("index.php?action=DetailView&module={$_REQUEST['module']}&record={$record}");
        }else{
             echo $flag;
        }
    }

    /**
     *
     */
    public function action_unpublish(){
        $record = $_REQUEST['record'];
        $jobOffer = self::getJobOfferRecordById($record);
        $flag = 0;

        if(ACLController::checkAccess('CC_Job_Offer', 'edit', true)){
            $moduleName = $this->bean->module_name ?? 'CC_Job_Offer';
            $oJobOffer = BeanFactory::getBean($moduleName, $record);
            $result = self::published_unpublished($record,$jobOffer,$oJobOffer, false);
            if($result){
                $flag = 1;
                $registry = (new CC_Recruitment_Activity_Handler($oJobOffer,"The job offer was unpublished","The job offer was unpublished: ".$oJobOffer->name,null,$oJobOffer->id))->saveRecruitmentActivity();
                if(!empty($_GET['record'])){
                    SugarApplication::appendSuccessMessage('The job offer was unpublished');
                }
            }
        } else {
            SugarApplication::appendErrorMessage(self::PERMISSION_ERROR_MESSAGE);
        }
        if(!empty($_GET['record'])){
            SugarApplication::redirect("index.php?action=DetailView&module={$_REQUEST['module']}&record={$record}");
        }else{
            echo $flag;
        }  
    
    }

    /**
     * @param $record
     * @param $jobOffer
     * @param $oJobOffer
     * @param $new_status
     * @return bool
     */
    public function published_unpublished($record, $jobOffer,$oJobOffer, $new_status):bool{
        $result = false;
        if (boolval($oJobOffer->is_published) == $new_status){
            $message = ($new_status)?'published':'unpublished';
            SugarApplication::appendErrorMessage('This job offer is already '.$message);
            return $result;
        }
        if(count($jobOffer['Profiles'])>0){
            $relatedRecords = self::getRelatedRows($jobOffer['Profiles']);
            $relatedRecords[] = self::relatedObject($record, 'CC_Job_Offer');
            $relationExist = $oJobOffer->load_relationship(self::JOB_OFFER_PUBLISH_OBJECT_RELATION_NAME);
            
    
            if($relationExist){
                foreach ($relatedRecords as $rowRelated){
                   if($rowRelated->record != "" && !is_null($rowRelated->record)){
                      $result = self::mark_record_as_published_unpublished($record,$rowRelated->module,$rowRelated->record,$oJobOffer, $new_status);
                   } 
                }
            } else {
                SugarApplication::appendErrorMessage('There was an error loading related objects.');
            }
        } else {
            // Error publishing without profile
            SugarApplication::appendErrorMessage('This job offer doesn\'t have Linked Profiles.');
        }
        return $result;
    }

    public function getRecordsByCandidateId(string $candidateId) {
        
        $sql = "SELECT id, date_modified, deleted, cc_candidate_cc_job_offercc_candidate_ida 'candidate_id', 
        cc_candidate_cc_job_offercc_job_offer_idb 'job_offer_id', stage, general_rating, 
        skill_rating, qualification_rating, application_state
        FROM cc_candidate_cc_job_offer_c AS rel 
        WHERE rel.deleted = 0 AND rel.cc_candidate_cc_job_offercc_candidate_ida ='$candidateId'";

        $db = DBManagerFactory::getInstance();

        $rows = $db->query($sql);

        $results = [];

        while ($row = $db->fetchRow($rows)) {
            $results[] = $row;
        }

        return $results;

    }

    function action_SubPanelViewer() {
        
        require_once 'include/SubPanel/SubPanelViewer.php';

        if ($_REQUEST["module"] == 'CC_Job_Offer' &&
            $_REQUEST["subpanel"] == "cc_profile_cc_job_offer" &&
            $_REQUEST["action"] == "SubPanelViewer") {
            $js="
                <script>

                    showSubPanel('cc_candidate_cc_job_offer', null, true);

                </script>";
            echo $js;
        }

    }


    function getJobApplicationsOffer($JobApplicationsId){

       
      $result = [];
      $cc_candidate_cc_job_offer = 'cc_candidate_cc_job_offer';
                                      
      $JobOfferApplications = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

      if($JobOfferApplications){ 
         
         $JobOfferApplications->load_relationship($cc_candidate_cc_job_offer);
         $CandidatesJobOfferFiles = $JobOfferApplications->$cc_candidate_cc_job_offer->getBeans();

            foreach($CandidatesJobOfferFiles as $key => $item){

                $ratingFieldsRelations = new CC_Job_OfferCC_CandidateRelationship();
                $ratingFieldsRelations = $ratingFieldsRelations->get_relation_row($CandidatesJobOfferFiles[$key]->id, $JobApplicationsId);
                
                $array = array();
                $array['id_candidate']          = $CandidatesJobOfferFiles[$key]->id;
                $array['object_name_candidate'] = $CandidatesJobOfferFiles[$key]->object_name;
                $array['name']            = $CandidatesJobOfferFiles[$key]->name;
                $array['document_number'] = $CandidatesJobOfferFiles[$key]->document_number;
                $array['date_entered']    = $CandidatesJobOfferFiles[$key]->date_entered;
                $array['date_modified']   = $CandidatesJobOfferFiles[$key]->date_modified;
                $array['description']     = $CandidatesJobOfferFiles[$key]->description;
                $array['id_job_application'] = $ratingFieldsRelations->id ?? "";
                $array['skill_rating']    = $ratingFieldsRelations->skill_rating ?? "";
                $array['general_rating']  = $ratingFieldsRelations->general_rating ?? "";
                $array['qualification_rating']  = $ratingFieldsRelations->qualification_rating ?? "";
                $array['type']            = $ratingFieldsRelations->type ?? "None";
                $array['stage']           = $ratingFieldsRelations->stage ?? "None";
                $result[] = $array;

            }
       }

       $json_data = array(
        "data" => $result
       );
       return $json_data;
    }

    function getAccount($searchTerm, $jobOfferId){
        

        /*$GetAccounts = BeanFactory::getBean('Accounts');
        $where       = !empty($searchTerm) ? "accounts.name like '%".$searchTerm."%'" : '';
        $beanList    = $GetAccounts->get_full_list('name',$where,false,0);
        foreach($beanList as $key => $item){*/
       
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT 
                        a.id,a.name
                FROM 
                        accounts a
                WHERE 
                    a.deleted = 0 AND 
                        NOT EXISTS(SELECT 
                                        * 
                                        FROM
                                            cc_job_offer_accounts_c c_s   
                                WHERE 
                                        a.id = c_s.cc_job_offer_accountsaccounts_idb AND
                                            c_s.cc_job_offer_accountscc_job_offer_ida ='".$jobOfferId."'
                                        AND c_s.deleted = 0) 
                                        AND a.name like '%".$searchTerm."%' GROUP BY a.id";

        
        $rows = $db->query($sql);
            while ($row = $db->fetchRow($rows)) {
                   $results[] = (object) [
                        'id'            => $row["id"],
                        'name'          => $row["name"]
                   ];        
               }
          return $results;
      }

      function getRecruitment($searchTerm){

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        $sql = "SELECT c.id, c.name, c.description, GROUP_CONCAT(a.id) account ".
        "FROM cc_recruitment_request c".
        " LEFT JOIN  cc_job_offer_cc_recruitment_request_c joc ON c.id = joc.cc_job_offer_cc_recruitment_requestcc_recruitment_request_idb".
        " LEFT JOIN  cc_recruitment_request_accounts_c ra ON c.id = ra.cc_recruitment_request_accountscc_recruitment_request_ida".
        " LEFT JOIN  accounts a ON a.id = ra.cc_recruitment_request_accountsaccounts_idb".
        " WHERE c.deleted = 0  AND joc.id IS NULL".
        " AND c.name like '%".$searchTerm."%' GROUP BY c.id";
   
        
        $rows = $db->query($sql);

        while ($row = $db->fetchRow($rows)) {
            $results[] = (object) [
                'id'            => $row["id"],
                'name'          => $row["name"],
                'description'   => $row["description"],
                'account'       => $row["account"],
            ];  
        }
          return $results;
      }


      function getJobOffer($JobApplicationsId,$IdAccount){
       
        $result = [];
        
        $GetAccounts = BeanFactory::getBean('Accounts');
        $beanList    = $GetAccounts->get_full_list('',"accounts.id = '".$IdAccount."'");
       
        if($beanList){ 
           $results[] = (object)['name' => $beanList[0]->name];        
         }
         return $results;

      }

      function GetJobOfferPosition($JobApplicationsId){
       
        $result = [];
        $cc_job_offer_cc_job_description = "cc_job_offer_cc_job_description";
        $PositionRelationsTable = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);
        $PositionRelationsTable->load_relationship($cc_job_offer_cc_job_description);
        $PositionFields = current($PositionRelationsTable->cc_job_offer_cc_job_description->getBeans());
       
        if($PositionFields){ 
           $results[] = (object)[
                'id'            => $PositionFields->id,
                'name'          => $PositionFields->name
           ];        
         }
         return $results;

      }

      function EditJobOffer($JobApplicationId,$position_name,$expire_on,$assigned_location,$contact_type,$account,$description,$position, $old_position,$jobfile){
        global  $sugar_config;

        $expire_on = date("Y-m-d", strtotime($expire_on));

        $GetOffer = BeanFactory::getBean('CC_Job_Offer',$JobApplicationId);
        $GetOffer->name              = $position_name;
        $GetOffer->expire_on         = $expire_on;
        $GetOffer->assigned_location = $assigned_location;
        $GetOffer->contract_type     = $contact_type;
        $GetOffer->description       = $description;

        $new_url = 1;
        if(!empty($jobfile['file']['tmp_name'])){ 
            
            $path_invoice = "publicUpload/";
            if (!is_dir($path_invoice)) {
                mkdir($path_invoice, 0775, true);
            }

            rename($path_invoice.$JobApplicationId, $path_invoice . $JobApplicationId . "__1");
              
            $name_file_attached = $jobfile['file']['name'];
            $type_file_attached = $jobfile['file']['type'];
            $ext_file_attached  = explode('/',$jobfile['file']['type']);
            $size_file_attached = $jobfile['file']['size'];
            $tmp_file_attached  = $jobfile['file']['tmp_name'];
            //$new_name_attached  = $JobApplicationId;

            //$path_invoice = $sugar_config['upload_dir']."invoice_attachments/";
            
            

            $attached_file = move_uploaded_file($tmp_file_attached,$path_invoice.$name_file_attached);
            if($attached_file){
                chmod($path_invoice.$name_file_attached, 0666);
                $GetOffer->file_mime_type = $type_file_attached;
                $GetOffer->file_url = $path_invoice.$name_file_attached;   
                $new_url = $path_invoice.$name_file_attached;                 
            }else{
                echo "attached error";
            }   
            
        }

        $GetOffer->save();

        if($GetOffer){

            $getRelationProfile = "cc_job_description_cc_profile";
            $relation2 = "cc_profile_cc_job_offer";
            $relation4 = "cc_job_offer_cc_job_description";
        
            $GetOffer->load_relationship($relation2);
            
            $JobDescriptionBean = BeanFactory::getBean('CC_Job_Description', $position);
        
            $JobDescriptionBean->load_relationship($getRelationProfile);
        
            if($JobDescriptionBean){
                $profiles = $JobDescriptionBean->$getRelationProfile->get();
                foreach($profiles as $profile){
                    $GetOffer->$relation2->delete($GetOffer->id,$profile);
                    $GetOffer->$relation2->add($profile);
                }
            }
        
        
            $GetOffer->load_relationship($relation4);
            $GetOffer->$relation4->delete($GetOffer->id, $old_position);
                $GetOffer->$relation4->add($position);


            return $new_url;
        }else{
            return 0;
        }

      }

      function DeleteJobOffer($JobApplicationId){
       
        $GetOffer = BeanFactory::getBean('CC_Job_Offer',$JobApplicationId);
        $GetOffer->deleted = 1;
        $GetOffer->save();

        if($GetOffer){
            $registry = (new CC_Recruitment_Activity_Handler($GetOffer,"The job offer was deleted","The job offer was deleted: ".$GetOffer->name,null,$GetOffer->id))->saveRecruitmentActivity();
            return 1;
        }

      }


      function GetProfileQualifications($JobApplicationsId){
        
        global $app_list_strings;
        $result = array();
        $cc_profile_cc_job_offer = 'cc_profile_cc_job_offer';          
        $Profile = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($Profile){ 
           
           $Profile->load_relationship($cc_profile_cc_job_offer);
           $profileIds = $Profile->cc_profile_cc_job_offer->get();
           
            if($profileIds){ 
                $cc_profile_cc_qualification = "cc_profile_cc_qualification";
                for ($i=0; $i < count($profileIds); $i++) { 
                        $ProfileRelationsTable = BeanFactory::getBean('CC_Profile',$profileIds[$i]);
                        $ProfileRelationsTable->load_relationship($cc_profile_cc_qualification);
                        $qualificationsFields = $ProfileRelationsTable->cc_profile_cc_qualification->getBeans();
                    
                        //relationship to bring the dependency
                        $profileRelations = new CC_Job_OfferCC_ProfileRelationship();
                        $profileFieldsRelation = $profileRelations->get_relation_row($JobApplicationsId, $profileIds[$i]);
                         
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
                                $array['dependecy']          = $app_list_strings['dependency_list'][$profileFieldsRelation->dependency] ?? 'Select in the profile';
                                $result[] = $array;
                            }
                        }       
                }
            }
         }
  
         $json_data = array(
            "data" => $result
         );

          return $json_data;
      }


      function GetProfileSkills($JobApplicationsId){

        $result = array();
        $result_repeated = array();
        $cc_profile_cc_job_offer = 'cc_profile_cc_job_offer';          
        $Profile = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($Profile){ 
                  
           $Profile->load_relationship($cc_profile_cc_job_offer);
           $profileIds = $Profile->cc_profile_cc_job_offer->get();
        
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
    
                             //skill can be repeated in profiles, remove duplicate duplicates
                             //if(!in_array($skillFields[$key]->id, $result_repeated)) {
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
                              //}
                              //array_push($result_repeated,$skillFields[$key]->id);
                          }
                            
                        }    
                }
            }
        }
  
         $json_data = array(
            "data" => $result
         );

          return $json_data;
      }


      function GetProfileJobOffer($JobApplicationsId){
        global $app_list_strings;
        $result = array();
        $cc_profile_cc_job_offer = 'cc_profile_cc_job_offer';          
        $Profile = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($Profile){   
           $Profile->load_relationship($cc_profile_cc_job_offer);

           $profileFields = $Profile->cc_profile_cc_job_offer->getBeans();
           
           if($profileFields){
              foreach($profileFields as $key => $item){
                $profileRelations = new CC_Job_OfferCC_ProfileRelationship();
                $profileFieldsRelation = $profileRelations->get_relation_row($JobApplicationsId, $profileFields[$key]->id);
                
                $array = array();
                $array['id']         = $profileFields[$key]->id;
                $array['object_name']= $profileFields[$key]->object_name;
                $array['name']       = $profileFields[$key]->name;
                $array['dependency'] = $profileFieldsRelation->dependency;
                $array['dependency_list'] = $app_list_strings['dependency_list'];
                $result[] = $array; 
              }
           }
        }
  
         $json_data = array(
            "data" => $result
         );

          return $json_data;
      }


      function getProfile($searchTerm){
       
        $GetProfile  = BeanFactory::getBean('CC_Profile');
        $where       = !empty($searchTerm) ? "cc_profile.name like '%".$searchTerm."%'" : '';
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


      function addProfile($JobApplicationId,$id_profile){

        $res = [];
      
        $actualRelation = 'cc_profile_cc_job_offer';
        $profileBean = BeanFactory::newBean('CC_Profile');
        $profileBean->load_relationship($actualRelation);
        $profileBean->id = $id_profile;
        $profileJobOfferBean = BeanFactory::getBean('CC_Job_Offer', $JobApplicationId);
        $status = $profileBean->$actualRelation->add($profileJobOfferBean);
        $res['data']->status = $status;

        if($res['data']->status){
            $updateProfileJobOffer       = new CC_Job_OfferCC_CandidateRelationship();
            $updateProfileJobOfferRespu  = $updateProfileJobOffer->updateJobOfferProfilesRating($JobApplicationId);
            return $res['data']->status;
        }
            
        

      }

      public function deleteProfile($JobApplicationId,$idProfile){
            
        $res = [];

        $profileObj = new CC_Job_OfferCC_ProfileRelationship();
        $profileObj->get_relation_row($idProfile, $JobApplicationId);
        $res['data']->status = $profileObj->remove_related_profile_job_application($idProfile, $JobApplicationId);
        if($res['data']->status){
            $updateProfileJobOffer       = new CC_Job_OfferCC_CandidateRelationship();
            $updateProfileJobOfferRespu  = $updateProfileJobOffer->updateJobOfferProfilesRating($JobApplicationId);
            return $res['data'];
        }
            
         
             
      }

      public function updateDependecyProfileJobOffer($JobApplicationId,$idProfile,$dependecy){

        $profileRelations = new CC_Job_OfferCC_ProfileRelationship();
        $profileFieldsRelation = $profileRelations->get_relation_row($JobApplicationId, $idProfile);
        $profileFieldsRelation->dependency = $dependecy;
        $profileFieldsRelation->save();
        $res['data'] = $profileFieldsRelation;


      }


      function GetCandidateRatingJobOffer($JobApplicationsId){

        $result = array();
        $cc_candidate_cc_job_offer = 'cc_candidate_cc_job_offer';          
        $Candidate = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($Candidate){ 
                  
           $Candidate->load_relationship($cc_candidate_cc_job_offer);
           $candidateIds = $Candidate->cc_candidate_cc_job_offer->getBeans();

            if($candidateIds){
        
                foreach($candidateIds as $key => $item){

                    $ratingFieldsRelations = new CC_Job_OfferCC_CandidateRelationship();
                    $ratingFieldsRelations = $ratingFieldsRelations->get_relation_row($candidateIds[$key]->id, $JobApplicationsId);
        
                    $array = array();
                    $array['object_name_candidate'] = $candidateIds[$key]->object_name ?? "";
                    $array['id_candidate']          = $candidateIds[$key]->id ?? "";
                    $array['name']                  = $candidateIds[$key]->name ?? "";
                    $array['skill_rating']          = $ratingFieldsRelations->skill_rating ?? "";
                    $array['general_rating']        = $ratingFieldsRelations->general_rating ?? "";
                    $array['qualification_rating']  = $ratingFieldsRelations->qualification_rating ?? "";
                    $array['type']                  = $ratingFieldsRelations->type ?? "";
                    $array['stage']                 = $ratingFieldsRelations->stage ?? "";
                    $result[] = $array; 
                }    
            }
  
            $json_data = array(
                "data" => $result
            );
         }
            return $json_data;

      }


      function GetApplicationsRatingobOffer($JobApplicationsId){

        $result = array();
        $cc_candidate_cc_job_offer = 'cc_candidate_cc_job_offer';          
        $Candidate = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($Candidate){ 
                  
           $Candidate->load_relationship($cc_candidate_cc_job_offer);
           $candidateIds = $Candidate->cc_candidate_cc_job_offer->getBeans();

            if($candidateIds){
        
                foreach($candidateIds as $key => $item){

                    $ratingFieldsRelations = new CC_Job_OfferCC_CandidateRelationship();
                    $ratingFieldsRelations = $ratingFieldsRelations->get_relation_row($candidateIds[$key]->id, $JobApplicationsId);
        
                    $array = array();
                    $array['object_name_candidate'] = $candidateIds[$key]->object_name ?? "";
                    $array['id_candidate']          = $candidateIds[$key]->id ?? "";
                    $array['name']                  = $candidateIds[$key]->name ?? "";
                    $array['skill_rating']          = $ratingFieldsRelations->skill_rating ?? "";
                    $array['general_rating']        = $ratingFieldsRelations->general_rating ?? "";
                    $array['qualification_rating']  = $ratingFieldsRelations->qualification_rating ?? "";
                    $array['type']                  = $ratingFieldsRelations->type ?? "";
                    $array['stage']                 = $ratingFieldsRelations->stage ?? "";
                    $result[] = $array; 
                }    
            }
  
            $json_data = array(
                "data" => $result
            );
         }
            return $json_data;

      }


      function GetEmployeeRatingJobOffer($allProfilesJobOffer){

        $result = array();         
        $EmployeesInformation = BeanFactory::getBean('CC_Employee_Information');
        $EmployeesInformationFields = $EmployeesInformation->get_full_list('',"cc_employee_information.active=1");

        if($EmployeesInformationFields){ 
            
            $skill_rating         = 0;
            $qualification_rating = 0;
            $general_rating       = 0;

            foreach($EmployeesInformationFields as $key => $item){
              $allProfilesJobOfferArray = explode(",",$allProfilesJobOffer);
                for($i=0; $i < count($allProfilesJobOfferArray); $i++) { 
                    $controllerProfile = new CC_ProfileController();
                    $res = $controllerProfile->rateProfile($EmployeesInformationFields[$key]->object_name, $EmployeesInformationFields[$key]->id, $allProfilesJobOfferArray[$i]);
                    $skill_rating         +=  $res['skill_rating'];
                    $general_rating       +=  $res['general_rating'];
                    $qualification_rating +=  $res['qualification_rating'];
                }

                $array = array();
                $array['object_name_employees'] = $EmployeesInformationFields[$key]->object_name ?? "";
                $array['id_employees']          = $EmployeesInformationFields[$key]->id ?? "";
                $array['name_employees']        = $EmployeesInformationFields[$key]->name ?? "";
                $array['skill_rating']          = $skill_rating / count($allProfilesJobOfferArray);
                $array['general_rating']        = $general_rating  / count($allProfilesJobOfferArray);
                $array['qualification_rating']  = $qualification_rating / count($allProfilesJobOfferArray);

                $result[] = $array; 
                $skill_rating         = 0;
                $qualification_rating = 0;
                $general_rating       = 0;
            }    
        }
  
         $json_data = array(
                "data" => $result
         );
         
         return $json_data;
      }


      public function CreateJobOffer($jobOffer, $jobfile){

        global  $sugar_config;

        $jobOfferBean = BeanFactory::newBean('CC_Job_Offer');

        $jobOfferBean->name = $jobOffer['position_name'];
        $jobOfferBean->description = $jobOffer['description'];
        $jobOfferBean->assigned_location = $jobOffer['assigned_location'];
        $jobOfferBean->contract_type = $jobOffer['contact_type'];
        $jobOfferBean->expire_on = $jobOffer['expire_on'];

        
        if(!empty($jobfile['file']['tmp_name'])){ 
              
            $name_file_attached = $jobfile['file']['name'];
            $type_file_attached = $jobfile['file']['type'];
            $ext_file_attached  = explode('/',$jobfile['file']['type']);
            $size_file_attached = $jobfile['file']['size'];
            $tmp_file_attached  = $jobfile['file']['tmp_name'];
            //$new_name_attached  = $name_file_attached;

            //$path_invoice = $sugar_config['upload_dir']."invoice_attachments/";
            //$path_invoice = $sugar_config['upload_dir'];
            $path_invoice = "publicUpload/";
                if (!is_dir($path_invoice)) {
                    if(!mkdir($path_invoice, 0775, true)) {
                        die('Fallo al crear las carpetas...');
                    }
                }
            $attached_file = move_uploaded_file($tmp_file_attached,$path_invoice.$name_file_attached);
            if($attached_file){
                chmod($path_invoice.$name_file_attached, 0666);
                $jobOfferBean->file_mime_type = $type_file_attached;
                $jobOfferBean->file_url = $path_invoice.$name_file_attached;   
                               
            }else{
                echo "attached error";
            }   
            
        }
        $jobOfferBean->save();

        if($jobOfferBean){
            $accounts = explode(',', $jobOffer['account']);
            for($i=0; $i<count($accounts); $i++ ){
                $this->addAccount($jobOfferBean->id,$accounts[$i]);
            }

            $getRelationProfile = "cc_recruitment_request_cc_profile";
            $getRelationEmployees = "cc_recruitment_request_cc_employee_information";
            $getRelationJobDescription = "cc_recruitment_request_cc_job_description";
            $relation2 = "cc_profile_cc_job_offer";
            $relation3 = "cc_job_offer_cc_employee_information";
            $relation4 = "cc_job_offer_cc_job_description";

            $jobOfferBean->load_relationship($relation2);
            
            $recruitmentBean = BeanFactory::getBean('CC_Recruitment_Request', $jobOffer['recruitment']);
 
            $recruitmentBean->load_relationship($getRelationProfile);

            if($recruitmentBean){
                $profiles = $recruitmentBean->$getRelationProfile->get();
                foreach($profiles as $profile){
                    $jobOfferBean->$relation2->add($profile);
                }
            }

            $jobOfferBean->load_relationship($relation3);
            $recruitmentBean->load_relationship($getRelationEmployees);

            if($recruitmentBean){
                $employees = $recruitmentBean->$getRelationEmployees->get();
                foreach($employees as $employee){
                    $jobOfferBean->$relation3->add($employee);
                }
            }

            $jobOfferBean->load_relationship($relation4);
            $recruitmentBean->load_relationship($getRelationJobDescription);

            if($recruitmentBean){
                $descriptions = $recruitmentBean->$getRelationJobDescription->get();
                foreach($descriptions as $description){
                    $jobOfferBean->$relation4->add($description);
                }
            }

            $relation1 = "cc_job_offer_cc_recruitment_request";
            $jobOfferBean->load_relationship($relation1);
            $jobOfferBean->$relation1->add($jobOffer['recruitment']);

            $registry = (new CC_Recruitment_Activity_Handler($jobOfferBean,"The Job Offer was created"))->saveRecruitmentActivity();

            $json_respu = array("module" => $jobOfferBean->object_name, "id" => $jobOfferBean->id);
        }
        return $json_respu;
    }

    public function createStructurePublish($jobOfferId)
    {
        global $sugar_config;
        $jobOfferSelf = $this->getJobOfferRecordById($jobOfferId);
        $location = ucwords(str_replace("_", " ", $jobOfferSelf["AssignedLocation"]));
        $contractType = ucwords(str_replace("_", " ", $jobOfferSelf["ContractType"]));
        $publicUrl = $sugar_config['site_url'] ."/". $jobOfferSelf["PublicUrl"];
        $jobOfferSelf["FileExtension"] = explode("/", $jobOfferSelf["FileExtension"]);
        $fileExtension = $jobOfferSelf["FileExtension"][1];

        $jobOfferPayload = [
            "Profiles" => [],
            "Name" => $jobOfferSelf["Name"],
            "Id" => $jobOfferSelf["Id"],
            "ExpireOn" => $jobOfferSelf["ExpireOn"],
            "Description" => $jobOfferSelf["Description"],
            "ContractType" => $contractType,
            //This attachments are fake...
            "Attachments" => [
                [
                    "Title" => $jobOfferSelf["Name"],
                    "PublicUrl" => $publicUrl,
                    "FileExtension" => $fileExtension,
                    "ContentVersionId" => "0683t00000BziRYAAZ"
                    // "PublicUrl" => "https://careers-integration-dev-ed--c.documentforce.com/sfc/dist/version/renditionDownload?rendition=ORIGINAL_Jpeg&versionId=0684W000008FhdRQAS&d=/a/4W000000bxs0/QjLIUIJVA77PZ4eaVtZuWMx65.CBcv3NunRJfXJYfyc&oid=00D4W000004B8aDUAS",
                    // "FileExtension" => "jpeg"
                ]
            ],
            "AssignedLocation" => $location,
            "Account" => null
        ];
        //Set up Profiles
        foreach($jobOfferSelf["Profiles"] as $profileSelf){
            $profileInformation = $profileSelf["Profile"][0];
            $profile = [
                "Profile" => [
                    "Skills" => [],
                    "Qualifications" => [],
                    "Name" => $profileInformation["Name"],
                    "Id" => $profileInformation["Id"]
                ],
                "Name" => $profileInformation["Name"],
                "Dependency" => $profileSelf["Dependency"]//The Dependency is null
            ];

            //Set up the Skills
            foreach($profileInformation["Skills"] as $skillSelf){
                $skillInformation = $skillSelf["Skill"];
                $relation = ucwords(str_replace("_", " ", $skillSelf["Type"]));
                $type = ucwords(str_replace("_", " ", $skillInformation["Type"]));
                $skill = [
                    "Skill" => [
                        "Type" => $type,
                        "Skill_Related_Id" => null,//$skillInformation["Parent_Id"],
                        "Skill_Related" => null,//$skillInformation["Parent_Name"],
                        "Name" => $skillInformation["Name"],
                        "Id" => $skillInformation["Id"]
                    ],
                    "Relation" => $relation,
                    "Name" => $skillInformation["Name"],
                    "Id" => $skillInformation["Id"],
                    "Amount" => ceil($skillSelf["Amount"])
                ];
                array_push($profile["Profile"]["Skills"], $skill);
            }
            //Set up the Qualifications
            foreach($profileInformation["Qualifications"] as $qualiSelf){
                $quali = [
                    "Qualification" => [
                        "Parent_Name" => null,
                        "Parent_Id" => null,
                        "Name" => $qualiSelf["Name"],
                        "Minimum_Required" => $qualiSelf["MinimumRequired"],
                        "Id" => $qualiSelf["Id"],
                        "Digital_Support" => $qualiSelf["DigitalSupportRequired"],
                        "Description" => $qualiSelf["Description"]
                    ],
                    "Name" => $qualiSelf["Name"],
                    "Id" => $qualiSelf["Id"],
                ];
                array_push($profile["Profile"]["Qualifications"], $quali);
            }

            array_push($jobOfferPayload["Profiles"], $profile);
        }

        $array = array();
        $array['Name'] = "careers_salesforce_after_joboffer_published";
        $array['Payload'] = array();
        array_push($array['Payload'], $jobOfferPayload);
        $array['Timestamp'] = (new DateTime)->format('Y-m-d H:i:s');
        $array['ExtraParams'] = array("name" => "value");
        $array['IsExternal'] = "true";

        return json_encode($array);
    }

    public function createStructureUnpublish($jobOfferId)
    {
        $array = [
            "Name" => "careers_salesforce_after_joboffer_unpublished",
            "Payload" => [$jobOfferId],
            "Timestamp" => (new DateTime)->format('Y-m-d H:i:s'),
            "ExtraParams" => ["name" => "value"],
            "IsExternal" => "true"
        ];
        return json_encode($array);
    }
    
    /**************** interviewer-role *******************/
    function GetInterviewersJobOffer($JobApplicationsId){
        global $app_list_strings;
        $result = array();
        $cc_interviewer_cc_job_offer = 'cc_interviewer_cc_job_offer';          
        $jobOffer = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($jobOffer){   
        $jobOffer->load_relationship($cc_interviewer_cc_job_offer);

        $interviewerFields = $jobOffer->cc_interviewer_cc_job_offer->getBeans();
        
        if($interviewerFields){
            foreach($interviewerFields as $key => $item){
                
                $array = array();
                $array['id']         = $interviewerFields[$key]->id;
                $array['object_name']= $interviewerFields[$key]->object_name;
                $array['name']       = $interviewerFields[$key]->name;
                $array['role'] = $interviewerFields[$key]->interviewer_role;
                $array['role_list'] = $app_list_strings['assigned_role_list'];
                $result[] = $array; 
            }
        }
        }

        $json_data = array(
            "data" => $result
        );

        return $json_data;
    }


    function getInterviewer($searchTerm){
    
        $GetEmployee  = BeanFactory::getBean('CC_Employee_Information');
        $where       = !empty($searchTerm) 
                        ? "cc_employee_information.name like '%".$searchTerm."%' AND cc_employee_information.active like 1" 
                        : 'cc_employee_information.active like 1';
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


    function addInterviewer($params){
        $res = [];
        $relation1 = 'cc_interviewer_cc_job_offer';
        $relation2 = 'cc_interviewer_cc_employee_information';

        $jobOfferRecord = new CC_Job_Offer();
        $jobOfferRecord->retrieve($params['JobApplicationId']);
        $jobName = $jobOfferRecord->name;

        $employeeRecord = new CC_Employee_Information();
        $employeeRecord->retrieve($params['id_employee']);
        $employeeName = $employeeRecord->name;

        $interviewerBean = BeanFactory::newBean('CC_Interviewer');
        $interviewerBean->name = $employeeName . ' / ' . $jobName;
        $interviewer = $interviewerBean->save();


        if($interviewer){
            $jobOfferRecord->load_relationship($relation1);
            $jobOfferRecord->$relation1->add($interviewer);
            $jobOfferRecord->save();

            $employeeRecord->load_relationship($relation2);
            $employeeRecord->$relation2->add($interviewer);
            $employeeRecord->save();
        }

        $res['data']->interviewer = $interviewer;
        return $res;
    }


    public function deleteInterviewer($params){
            
        /*$relation1 = 'cc_interviewer_cc_job_offer';
        $relation2 = 'cc_interviewer_cc_employee_information';

        $jobOfferRecord = new CC_Job_Offer();
        $jobOfferRecord->retrieve($params['JobApplicationId']);

        $interviewerRecord = new CC_Interviewer();
        $interviewerRecord->retrieve($params['id_interviewer']);
            
        $jobOfferRecord->load_relationship($relation1);
        $jobOfferRecord->$relation1->delete($params['JobApplicationId'], $params['id_interviewer']);

        $interviewerRecord->load_relationship($relation2);
        $employeeFieldId =  current($interviewerRecord->$relation2->get());
        $interviewerRecord->$relation2->delete($params['id_interviewer'], $employeeFieldId);
        $interviewerRecord->deleted = 1;
        $interviewerRecord->save(); 
        */
        $res = [];
        $interviewerRecord  = BeanFactory::getBean('CC_Interviewer');
        $done = $interviewerRecord->mark_deleted($params['id_interviewer']);

            $res['data']->interviewer = $done;
            return $res;
        }

    public function updateRoleInterviewerJobOffer($params){

        $interviewerJobOffer = BeanFactory::getBean('CC_Interviewer', $params['id_interviewer']);
        $interviewerJobOffer->interviewer_role = $params['role'];
        $interviewerJobOffer->save();

    }

       /********************* employee-related************/

 
       function GetRelatedEmployees($JobApplicationsId){
        global $app_list_strings;
        $result = array();
        $cc_job_offer_cc_employee_information = 'cc_job_offer_cc_employee_information';          
        $jobOffer = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($jobOffer){ 
            
        $jobOffer->load_relationship($cc_job_offer_cc_employee_information);

        if($jobOffer->cc_job_offer_cc_employee_information){
            $employeeFields = $jobOffer->cc_job_offer_cc_employee_information->getBeans();
            foreach($employeeFields as $key => $item){
                if($employeeFields[$key]->active == 1){
                    $employeeRelations = new CC_Job_OfferCC_Employee_InformationRelationship();
                    $employeeFieldsRelation = $employeeRelations->get_relation_row($JobApplicationsId, $employeeFields[$key]->id);
                    
                    $array = array();
                    $array['id']         = $employeeFields[$key]->id;
                    $array['object_name']= $employeeFields[$key]->object_name;
                    $array['name']       = $employeeFields[$key]->name;
                    $array['type'] = $employeeFieldsRelation->type;
                    $array['role_list'] = $app_list_strings['assigned_role_list'];
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


    function addRelatedEmployee($params){
        $res = [];
        $relation1 = 'cc_job_offer_cc_employee_information';
            $jobOfferRecord = new CC_Job_Offer();
            $jobOfferRecord->retrieve($params['JobApplicationId']);
            $jobOfferRecord->load_relationship($relation1);
            $jobOfferRecord->$relation1->add($params['id_employee']);
            $jobOfferRecord->save();

        $res['data']->employee = $params['id_employee'];
        return $res;
    }



    public function deleteRelatedEmployee($JobApplicationId,$id_employee){
       $res = [];
            $profileObj = new CC_Job_OfferCC_Employee_InformationRelationship();
            $profileObj->get_relation_row($id_employee, $JobApplicationId);
            $res['data']->status = $profileObj->remove_related_profile_job_application($id_employee, $JobApplicationId);
            return $res['data'];
        
             
      }

      public function updateRoleRelatedEmployee($JobApplicationId,$id_employee,$type){

        $profileRelations = new CC_Job_OfferCC_Employee_InformationRelationship();
        $profileFieldsRelation = $profileRelations->get_relation_row($JobApplicationId, $id_employee);
        $profileFieldsRelation->type = $type;
        $profileFieldsRelation->save();
        $res['data'] = $profileFieldsRelation;


      }

      
      /********************* Accounts************/

 
      function GetAccountJobOffer($JobApplicationsId){
        global $app_list_strings;
        $result = array();
        $cc_job_offer_accounts = 'cc_job_offer_accounts';          
        $jobOffer = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($jobOffer){ 
            
        $jobOffer->load_relationship($cc_job_offer_accounts);

        if($jobOffer->cc_job_offer_accounts){
            $accountFields = $jobOffer->cc_job_offer_accounts->getBeans();
            foreach($accountFields as $key => $item){                
                $array = array();
                $array['id']         = $accountFields[$key]->id;
                $array['object_name']= $accountFields[$key]->module_dir;
                $array['name']       = $accountFields[$key]->name;
                $result[] = $array; 
              }
        }
        }

        $json_data = array(
            "data" => $result
        );

        return $json_data;
    }


    function addAccount($JobApplicationId, $id_account){
        $res = [];
        $relation1 = 'cc_job_offer_accounts';
            $jobOfferRecord = new CC_Job_Offer();
            $jobOfferRecord->retrieve($JobApplicationId);
            $jobOfferRecord->load_relationship($relation1);
            $jobOfferRecord->$relation1->add($id_account);
            $jobOfferRecord->save();

        $res['data']->employee = $id_account;
        return $res;
    }



    public function deleteAccount($params){
       $res = [];            
        $relation1 = 'cc_job_offer_accounts';

        $jobOfferRecord = new CC_Job_Offer();
        $jobOfferRecord->retrieve($params['JobApplicationId']);
            
        $jobOfferRecord->load_relationship($relation1);
        $jobOfferRecord->$relation1->delete($params['JobApplicationId'], $params['id_account']);

        $res['data']->status = $params['id_account'];
        return $res['data'];        
      }

      /***************************Profiles*******************************/
      function GetProfilesJobOffer($JobApplicationsId){
        $result = array();
        $cc_profile_cc_job_offer = 'cc_profile_cc_job_offer';          
        $cc_profile_cc_qualification = 'cc_profile_cc_qualification';          
        $cc_profile_cc_skill = 'cc_profile_cc_skill';          
        $jobOffer = BeanFactory::getBean('CC_Job_Offer',$JobApplicationsId);

        if($jobOffer){   
        $jobOffer->load_relationship($cc_profile_cc_job_offer);
        $relatedProfiles = $jobOffer->cc_profile_cc_job_offer->getBeans();
        
        $array = array();

        if($relatedProfiles){
            foreach($relatedProfiles as $key => $item){
                $rProfileBean = new CC_Job_OfferCC_ProfileRelationship();
                $relatedRow = $rProfileBean->get_relation_row($JobApplicationsId, $relatedProfiles[$key]->id);
                
                if($relatedRow->dependency == NULL || $relatedRow->dependency == ""){
                    $array['NoDependency'] = true;
                }
                $profile = BeanFactory::getBean('CC_Profile',$relatedProfiles[$key]->id);
                $profile->load_relationship($cc_profile_cc_qualification);
                $relatedQua = $profile->cc_profile_cc_qualification->getBeans();     
                $profile->load_relationship($cc_profile_cc_skill);
                $relatedSkill = $profile->cc_profile_cc_skill->getBeans(); 
                if(!$relatedQua && !$relatedSkill){
                    $array['NoSkillyQua'] = true;
                }
            }
        }else{
            $array['NoProfile'] = true;
        }
        $result[] = $array;
        }

        $json_data = array(
            "data" => $result
        );

        return $json_data;
    }


}