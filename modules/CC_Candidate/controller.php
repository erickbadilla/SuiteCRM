<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';

use \BeanFactory;
use Api\V8\Config\Common as Common;
use Api\V8\Utilities;
use CC_SkillController;
use CC_QualificationController;
use CC_Candidate_AvailabilityController;
use CC_SkillCC_CandidateRelationship;
use CC_QualificationCC_CandidateRelationship;
use CC_Personality_TestController;
use CC_Candidate_Track_LogController;

require_once 'modules/CC_Skill/controller.php';
require_once 'modules/CC_Skill/CC_SkillCC_CandidateRelationship.php';
require_once 'modules/CC_Qualification/controller.php';
require_once 'modules/CC_Qualification/CC_QualificationCC_CandidateRelationship.php';
require_once 'modules/CC_Candidate_Availability/controller.php';
require_once 'modules/CC_Personality_Test/controller.php';
require_once 'modules/CC_Candidate_Track_Log/controller.php';
class CC_CandidateController extends SugarController {

    private static $customModuleName = 'CC_Candidate';

    private static $candidateRelationKeyMap = [
        "cc_candidate_cc_job_offer" => null,
        "cc_candidate_cc_candidate_note" => null,
        "cc_candidate_availability_cc_candidate" => ["CandidateAvailability"],
        "cc_candidate_track_log_cc_candidate" => null,
        "cc_candidate_cc_skill" => ["CandidateSkills"],
        "cc_interviews_cc_candidate" => null,
        "cc_candidate_notes" => null,
        "cc_candidate_cc_personality_test" => ["PersonalityTest"],
        "cc_candidate_cc_qualification" => ["CandidateQualifications"],
    ];

    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_CandidateController() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    public function candidateMap($candidateBean){

        $candidate['Years_Experience'] = $candidateBean->years_of_experience;
        $candidate['Street_Address_1'] = $candidateBean->street_address_1;
        $candidate['Street_Address_2'] = $candidateBean->street_address_2;
        $candidate['State'] = $candidateBean->state_province;
        $candidate['Postal_Code'] = $candidateBean->zip_postal_code;
        $candidate['Phone'] = $candidateBean->phone;
        $candidate['Mobile'] = $candidateBean->mobile;
        $candidate['First_Name'] = $candidateBean->first_name;
        $candidate['Last_Name'] = $candidateBean->last_name;
        $candidate['Name'] = $candidateBean->name;
        $candidate['Id'] = $candidateBean->id;
        $candidate['Has_Visa'] = ($candidateBean->has_visa == '1');
        $candidate['Has_Passport'] = ($candidateBean->has_passport == '1');
        $candidate['Email'] = $candidateBean->email;
        $candidate['Document_Number'] = $candidateBean->document_number;
        $candidate['Currently_Employed'] = ($candidateBean->currently_employed == '1');
        $candidate['Current_Employer'] = $candidateBean->current_employer;
        $candidate['Country'] = $candidateBean->country;
        $candidate['City'] = $candidateBean->city;

        $education = str_replace(array("^","_"),array("", "/"),$candidateBean->education);
        $candidate['Education'] = $education;

        $personalityTest = (new CC_Personality_TestController)->getRecordsByCandidateId($candidate['Id']);
        $candidate['PersonalityTest'] = $personalityTest;

        $skills = (new CC_SkillController)->getRecordsByCandidateId($candidate['Id']);
        $candidate['CandidateSkills'] = $skills;

        $qualifications = (new CC_QualificationController)->getRecordsByCandidateId($candidate['Id']);
        $candidate['CandidateQualifications'] = $qualifications;

        $candidateAvailability = (new CC_Candidate_AvailabilityController)->getRecordsByCandidateId($candidateBean, $candidate['Id']);
        $candidate['CandidateAvailability'] = $candidateAvailability;

        return $candidate;

    }

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getRecordsByIds(array $arrIds) {

        if($arrIds) {
            $bean = BeanFactory::getBean(self::$customModuleName, $arrIds[0]); 
            $candidateBeanList[] = $bean;
        } else {
            $bean = BeanFactory::getBean(self::$customModuleName); 
            $candidateBeanList =  $bean->get_full_list('name','',false,0);
        }

        // Initialize an array with the results
        $result = [];

        foreach($candidateBeanList as $candidateBean) {
            $result[] = self::candidateMap($candidateBean);
        }

        return $result;
    }

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getCandidatesByName(string $name): array
    {

        $bean = BeanFactory::getBean(self::$customModuleName);
        $where = "name like '%".$name."%'";
        $candidateBeanList =  $bean->get_full_list('name', $where ,false,0);

        // Initialize an array with the results
        $result = [];

        foreach($candidateBeanList as $candidateBean) {
            $result[] = self::candidateMap($candidateBean);
        }

        return $result;
    }

    /**
     * @param object $candidate
     * @return bool|SugarBean
     */
    public function saveCandidateRecord(object $candidate, $candidate_id=null){


        $candidateBean = BeanFactory::getBean(self::$customModuleName,$candidate_id);

        if($candidateBean===false || is_null($candidate_id)){
            $candidateBean = BeanFactory::newBean(self::$customModuleName);
        }

        if(!is_null($candidate_id) && is_null($candidateBean->id)){
            $candidateBean->id= $candidate_id;
            $candidateBean->new_with_id = $candidate_id;
        }

        $candidateBean->years_of_experience = $candidate->Years_Experience;
        $candidateBean->street_address_1 = $candidate->Street_Address_1;
        $candidateBean->street_address_2 = $candidate->Street_Address_2;
        $candidateBean->state_province = $candidate->State;
        $candidateBean->zip_postal_code = $candidate->Postal_Code;
        $candidateBean->phone = $candidate->Phone;
        $candidateBean->mobile = $candidate->Mobile;
        $candidateBean->first_name = $candidate->First_Name;
        $candidateBean->last_name = $candidate->Last_Name;
        $candidateBean->name = $candidateBean->first_name.", ".$candidateBean->last_name;
        $candidateBean->last_name = $candidate->Last_Name;
        $candidateBean->education = $this->formatCandidateEducation($candidate->Education);
        $candidateBean->has_passport = $candidate->Has_Passport;
        $candidateBean->has_visa = $candidate->Has_Visa ;
        $candidateBean->email = $candidate->Email;
        $candidateBean->document_number = $candidate->Document_Number;
        $candidateBean->currently_employed =  $candidate->Currently_Employed;
        $candidateBean->current_employer = $candidate->Current_Employer;
        $candidateBean->country = $candidate->Country;
        $candidateBean->city = $candidate->City;      
          
          
        $candidateID = $candidateBean->save();

        if(property_exists($candidate,'CandidateSkills') && is_array($candidate->CandidateSkills)){
            $this->saveCandidateSkill($candidateBean, $candidate->CandidateSkills);
        }
        if(property_exists($candidate,'CandidateQualifications') && is_array($candidate->CandidateQualifications)){
            $this->saveCandidateQualification($candidateBean, $candidate->CandidateQualifications);
        }
        if(property_exists($candidate,'PersonalityTests') && is_array($candidate->PersonalityTests)){
            $this->saveCandidatePersonalityTest($candidateBean, $candidate->PersonalityTests);
        }
        if(property_exists($candidate,'CandidateAvailability') && is_array($candidate->CandidateAvailability)){
            $this->saveCandidateAvailability($candidateBean, $candidate->CandidateAvailability);
        }

        return $candidateBean;
    }

            /**
     * @param object $candidate
     * @param string $candidate_id
     * @return bool|SugarBean
     */
    public function saveAttachment(array $attachment = null, string $candidate_id){

        global  $sugar_config;
        $candidateBean = BeanFactory::getBean(self::$customModuleName,$candidate_id);     

        if(is_array($attachment)){
            foreach ($attachment as $attachment) {
                if($attachment->PublicUrl){
                    $url = $attachment->PublicUrl;
                    $ch = curl_init($url);
                    $file_name = basename($url);
                    $dir = $sugar_config['upload_dir'];
                    $save_file_loc = $dir . $file_name;
                    $fp = fopen($save_file_loc, 'wb');  
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
    
                    $time = new \DateTime();
    
                    $notesBean = BeanFactory::newBean('Notes');
                    $notesBean->date_entered     = $time->format('Y-m-d H:i:s');
                    $notesBean->date_modified    = $time->format('Y-m-d H:i:s');
                    $notesBean->parent_id        = $candidate_id;
                    $notesBean->parent_type      = 'CC_Candidate';
                    $notesBean->assigned_user_id = $_SESSION['authenticated_user_id'];
    
                    $notesBean->name        = 'Candidate Upload';
                    $notesBean->description = 'PDF Upload'; 
    
                    chmod($save_file_loc, 0777);
                    $notesBean->file_mime_type = 'application/pdf';
                    $notesBean->filename = $file_name;  
    
    
                $noteID = $notesBean->save();
    
                if($noteID !== false){
                    // rename file of folder,to use the suitecrm methods
                    rename($save_file_loc, $sugar_config['upload_dir'] . $notesBean->id);
                }
    
                $relation1 = 'cc_candidate_notes';
    
                $candidateBean->load_relationship($relation1);
                $candidateBean->$relation1->add($notesBean->id);
                $candidateBean->save();
        
                    return $noteID;
                }
            }
        }
    }

    /**
     * @param object $candidate
     * @param string $candidate_id
     * @return bool|SugarBean
     */
    public function updateCandidate(object $candidate, string $candidate_id){

        $candidateBean = BeanFactory::getBean(self::$customModuleName,$candidate_id);
        $linked_fields = $candidateBean->get_linked_fields();
        $linked_beans_related = [];

        foreach ($linked_fields as $key => $value){
            if(key_exists($key,self::$candidateRelationKeyMap) && !is_null(self::$candidateRelationKeyMap[$key])){
                $linked_beans_related[] = $key;
            }
        }

        $candidateBean->load_relationships();
        foreach ( $linked_beans_related as $linked_bean ) {
            $actualRelatedBean = $candidateBean->$linked_bean->getBeans();
            foreach ($actualRelatedBean as $actualRelated){
                $candidateBean->$linked_bean->delete($candidateBean->id, $actualRelated);
            }
        }

        return self::saveCandidateRecord($candidate, $candidate_id);

    }


    /**
     * @param string $candidateEducation
     * @return string $result
     */
    public function formatCandidateEducation(string $candidateEducation){

        $educationArray = explode(",", $candidateEducation);

        foreach($educationArray as $education){
        	$education = "^".str_replace("/","_",$education)."^,";
            $result = $result.$education;
        }
        $result = rtrim($result, ",");

        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $candidateSkills
     * @return array
     */
    public function saveCandidateSkill(\SugarBean $parentBean, array $CandidateSkills) {
        $csRelation = 'cc_candidate_cc_skill';
        global $current_user;
        $user = BeanFactory::getBean('Users', $current_user->id);

        $parentBean->load_relationship($csRelation);

        $result = [];

        foreach ($CandidateSkills as $candidateSkill) {
            $candidateSkill->Skill->SkillName = $candidateSkill->Skill->Name;
            $candidateSkill->Skill->SkillType = $candidateSkill->Skill->Type;
            if (strtolower($candidateSkill->Type)=="years of experience") {
                $candidateSkill->Type = 'years_of_experience';
            }

            $skillBean = (new CC_SkillController)->saveSkillRecord($candidateSkill->Skill, $candidateSkill->Skill->Id);

            $parentBean->$csRelation->add($skillBean);

            $rSkillBean = new CC_SkillCC_CandidateRelationship();

            $relatedRow = $rSkillBean->get_relation_row($parentBean->id, $skillBean->id);

            if ($relatedRow) {
                if(strtolower($candidateSkill->Type)=='years_of_experience'){
                    $relatedRow->years = $candidateSkill->Amount;
                }
                if(strtolower($candidateSkill->Type)=='rating'){
                    $relatedRow->rating = $candidateSkill->Amount;
                    if($candidateSkill->Amount>5){
                        $relatedRow->rating = 5;
                    }
                    if($candidateSkill->Amount<0){
                        $relatedRow->rating = 0;
                    }
                }
                $relatedRow->modified_user_id = $user->id;
                $rSave = $relatedRow->save();
                if($rSave){
                    $result[] = $rSave;
                }
            }
        }
        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $employeeQuas
     * @return array
     */
    public function saveCandidateQualification(\SugarBean $parentBean, array $candidateQuas) {
        $cqRelation = 'cc_candidate_cc_qualification';

        $parentBean->load_relationship($cqRelation);

        $result = [];

        foreach ($candidateQuas as $cQua) {
            $cQua->Qualification->MinimumRequired = $cQua->Qualification->Minimum_Required;
            $cQua->Qualification->DigitalSupportRequired = $cQua->Qualification->Digital_Support;

            $cQuaBean = (new CC_QualificationController)->saveQualificationRecord($cQua->Qualification, $cQua->Qualification->Id);

            $parentBean->$cqRelation->add($cQuaBean);

            $rQuaBean = new CC_QualificationCC_CandidateRelationship();

            $relatedRow = $rQuaBean->get_relation_row($parentBean->id, $cQuaBean->id);
            if ($relatedRow) {
                $relatedRow->actual_qualification = $cQua->ActualQualification;
                $relatedRow->has_digital_support = $cQua->HasDigitalSupport;
                $relatedRow->save();
            }

            $result[] = $parentBean;
        }
        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $CandidatePersonalityTests
     * @return array
     */
    public function saveCandidatePersonalityTest(\SugarBean $parentBean, array $CandidatePersonalityTests) {
        return (new CC_Personality_TestController)->saveRelatedPersonalityTest(self::$customModuleName, $parentBean->id, $CandidatePersonalityTests);
    }

    /**
     * @param SugarBean $parentBean
     * @param array $CandidateAvailability
     * @return array
     */
    public function saveCandidateAvailability(\SugarBean $parentBean, array $CandidateAvailabilities) {
        $ccaRelation = 'cc_candidate_availability_cc_candidate';
        $parentBean->load_relationship($ccaRelation);

        $result = [];

        foreach ($CandidateAvailabilities as $CandidateAvailability) {
            $cAvailabilityBean = (new CC_Candidate_AvailabilityController)->saveCandidateAvailabilityRecord($CandidateAvailability, $parentBean, $CandidateAvailability->Id);
            
            if($cAvailabilityBean){
                $parentBean->$ccaRelation->add($cAvailabilityBean);
                $result [] = $cAvailabilityBean;
            }
        }
        
        return $result;
    }
    
    /**
     * @param SugarBean $parentBean
     * @param array $CandidateTrackLogs
     * @return array
     */
    public function saveCandidateTrackLog(\SugarBean $parentBean, array $CandidateTrackLogs) {
        $ctlRelation = 'cc_candidate_track_log_cc_candidate';
        $parentBean->load_relationship($ctlRelation);

        $result = [];

        foreach ($CandidateTrackLogs as $CandidateTrackLog) {
            $cTrackLogBean = (new CC_Candidate_Track_LogController)->saveTrackLogRecord($CandidateTrackLog);
            $parentBean->$ctlRelation->add($cTrackLogBean);

            if($cTrackLogBean){
                $result [] = $cTrackLogBean;
            }
        }
        
        return $result;
    }

    function action_SubPanelViewer() {
        
        require_once 'include/SubPanel/SubPanelViewer.php';
        
        if ($_REQUEST["module"] == 'CC_Candidate' &&
            $_REQUEST["subpanel"] == "cc_candidate_cc_qualification" &&
            $_REQUEST["action"] == "SubPanelViewer") {
            $js="
            <script>
                showSubPanel('cc_candidate_cc_job_offer', null, true);
            </script>";
            echo $js;
        }

        if ($_REQUEST["module"] == 'CC_Candidate' &&
            $_REQUEST["subpanel"] == "cc_candidate_cc_skill" &&
            $_REQUEST["action"] == "SubPanelViewer") {
            $js="
            <script>
                showSubPanel('cc_candidate_cc_job_offer', null, true);
            </script>";
            
            echo $js;
        }
    }


    function GetCandidates(){
        global $app_list_strings;
        $result = array();
        $candidate = BeanFactory::getBean('CC_Candidate');

        if($candidate){ 
            $candidateFields = $candidate->get_full_list();
            foreach($candidateFields as $key => $item){
               
                $array = array();
                $array['id']            = $candidateFields[$key]->id;
                $array['object_name']   = $candidateFields[$key]->object_name;
                $array['name']          = $candidateFields[$key]->name;
                $array['first_name']    = $candidateFields[$key]->first_name;
                $array['last_name']     = $candidateFields[$key]->last_name;
                $array['city']          = $candidateFields[$key]->city;
                $array['country']       = $candidateFields[$key]->country;

                $result[] = $array; 
              }
        
        }

        $json_data = array(
            "data" => $result
        );

        return $json_data;
    }


    public function quickEditCandidate($params){

        $candidateInformation = BeanFactory::getBean('CC_Candidate', $params['id_candidate']);
        if($params['change'] == 1){
            $candidateInformation->name = $params['info'];
        }else if($params['change'] == 2){
            $candidateInformation->first_name = $params['info'];
        }
        else if($params['change'] == 3){
            $candidateInformation->last_name = $params['info'];
        }
        else if($params['change'] == 4){
            $candidateInformation->city = $params['info'];
        }
        else {
            $candidateInformation->country = $params['info'];
        }

        $candidateInformation->save();

    }

    public function candidateUploadResume($notesData,$notesFile){
        global $app_list_strings;
        $ch = curl_init();

        $postField = array();
        $tmpfile = $notesFile['file']['tmp_name'];
        $filename = basename($notesFile['file']['name']);
        $postField['file'] =  curl_file_create($tmpfile, $notesFile['file']['type'], $filename);
        $postField['lan'] = 'en';

       
        curl_setopt($ch, CURLOPT_URL,"http://167.172.244.38/upload");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postField);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = [
            'Content-Type: multipart/form-data',
            'Host: http://167.172.244.38'
        ];


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
        $array = array();
        $array['candidate'] = json_decode($server_output);
        $array['education_list'] = $app_list_strings['education_list'];
        $result[] = $array;

        $json_data = array(
            "data" => $result
        );

        return $json_data;

        //return  json_decode($server_output);
    }


    function candidateUploadCreate($candidateInfo,$notesFile){
    global  $sugar_config;

    $candidateInfoBean = BeanFactory::newBean('CC_Candidate');

    $candidateInfoBean->name = $candidateInfo['name'];
    $candidateInfoBean->first_name = $candidateInfo['first_name'];
    $candidateInfoBean->last_name = $candidateInfo['last_name'];
    $candidateInfoBean->city = $candidateInfo['city'];
    $candidateInfoBean->country = $candidateInfo['country'];
    $candidateInfoBean->document_number = $candidateInfo['document_number'];
    $candidateInfoBean->email = $candidateInfo['email'];
    $candidateInfoBean->education = $candidateInfo['education'];
    $candidateInfoBean->has_passport = $candidateInfo['has_passport'];
    $candidateInfoBean->has_visa = $candidateInfo['has_visa'];
    $candidateInfoBean->mobile = $candidateInfo['mobile'];
    $candidateInfoBean->phone = $candidateInfo['phone'];
    $candidateInfoBean->current_employer = $candidateInfo['current_employer'];
    $candidateInfoBean->currently_employed = $candidateInfo['currently_employed'];
    $candidateInfoBean->state_province = $candidateInfo['state_province'];
    $candidateInfoBean->street_address_1 = $candidateInfo['street_address_1'];
    $candidateInfoBean->street_address_2 = $candidateInfo['street_address_2'];
    $candidateInfoBean->years_of_experience = $candidateInfo['years_of_experience'];
    $candidateInfoBean->zip_postal_code = $candidateInfo['zip_postal_code'];
    $candidateID = $candidateInfoBean->save();

    if($candidateInfoBean){

        $objSkill = json_decode(html_entity_decode($candidateInfo['skills']));
        $objQuali = json_decode(html_entity_decode($candidateInfo['quali']));

        foreach ($objSkill as $key => $value) {
            $relationskill =$objSkill[$key]->Relation;
            $skillId =$objSkill[$key]->Id;
            $skillAmount =$objSkill[$key]->Amount;
            self::createSkills($candidateID,$relationskill, $skillId, $skillAmount, $candidateInfoBean);
        }

        foreach ($objQuali as $key => $value) {
            $qualiId =$objQuali[$key]->Id;
            $qualiAct =$objQuali[$key]->ActualQualification;
            foreach ($value as $keyI => $valueI) {
                $qualiDigi = $valueI->HasDigitalSupport == true ? 1 : 0;
           }
            self::createQualifications($candidateID, $qualiId, $qualiAct, $qualiDigi, $candidateInfoBean);
         }

        self::createCandidateNote($candidateID,$notesFile, $candidateInfoBean);
    }
    
    $json_respu = array("module" => $candidateInfoBean->object_name, "id" => $candidateID);
        

    return $json_respu;
}

function createSkills($candidateID,$relationskill, $skillId, $skillAmount, $candidateInfoBean){
    $res =array();

    $relation1 = 'cc_candidate_cc_skill';
        $candidateInfoBean->load_relationship($relation1);
        $candidateInfoBean->$relation1->add($skillId);
        $candidateInfoBean->save();

        $rSkillBean = new CC_SkillCC_CandidateRelationship();

        $relatedRow = $rSkillBean->get_relation_row($candidateID, $skillId);

        if ($relatedRow) {
            if(strtolower($relationskill)=='years_of_experience'){
                $relatedRow->years = $skillAmount;
            }
            if(strtolower($relationskill)=='rating'){
                $relatedRow->rating = $skillAmount;
                if($skillAmount>5){
                    $relatedRow->rating = 5;
                }
                if($skillAmount<0){
                    $relatedRow->rating = 0;
                }
            }
            $relatedRow->save();
        }
    $res['data']->skill = $skillId;
    return $res;
}

function createQualifications($candidateID, $qualiId, $qualiAct, $qualiDigi, $candidateInfoBean){
    $res =array();

    $relation1 = 'cc_candidate_cc_qualification';

        $candidateInfoBean->load_relationship($relation1);
        $candidateInfoBean->$relation1->add($qualiId);
        $candidateInfoBean->save();

        $rQuaBean = new CC_QualificationCC_CandidateRelationship();

        $relatedRow = $rQuaBean->get_relation_row($candidateID, $qualiId);
        if ($relatedRow) {
            $relatedRow->actual_qualification = $qualiAct;
            $relatedRow->has_digital_support = $qualiDigi;
            $relatedRow->save();
        }
    $res['data']->skill = $qualiId;
    return $res;
}



    public function createCandidateNote($candidateID,$notesFile, $candidateInfoBean){
        
        global  $sugar_config;
		$time = new \DateTime();

			$notesBean = BeanFactory::newBean('Notes');
			$notesBean->date_entered     = $time->format('Y-m-d H:i:s');
			$notesBean->date_modified    = $time->format('Y-m-d H:i:s');
			$notesBean->parent_id        = $candidateID;
			$notesBean->parent_type      = 'CC_Candidate';
			$notesBean->assigned_user_id = $_SESSION['authenticated_user_id'];

		$notesBean->name        = 'Candidate Upload';
		$notesBean->description = 'PDF Upload'; 
		


            if(!empty($notesFile['file']['tmp_name'])){ 
              
                $name_file_attached = $notesFile['file']['name'];
                $type_file_attached = $notesFile['file']['type'];
                $ext_file_attached  = explode('/',$notesFile['file']['type']);
                $size_file_attached = $notesFile['file']['size'];
                $tmp_file_attached  = $notesFile['file']['tmp_name'];
                $new_name_attached  = $candidateID."_".date('Y-m-d_H:m:s').".".end($ext_file_attached);

                //$path_invoice = $sugar_config['upload_dir']."invoice_attachments/";
                $path_invoice = $sugar_config['upload_dir'];


                $attached_file = move_uploaded_file($tmp_file_attached,$path_invoice.$new_name_attached);
                if($attached_file){
                    chmod($path_invoice.$new_name_attached, 0777);
                    $notesBean->file_mime_type = $type_file_attached;
                    $notesBean->filename = $notesFile['file']['name'];                  
                }else{
                    echo "attached error";
                }   
				
            }

			$noteID = $notesBean->save();

			if($noteID !== false){
				// rename file of folder,to use the suitecrm methods
				rename($path_invoice.$new_name_attached, $sugar_config['upload_dir'] . $notesBean->id);
			}


				$relation1 = 'cc_candidate_notes';

				$candidateInfoBean->load_relationship($relation1);
				$candidateInfoBean->$relation1->add($notesBean->id);
				$candidateInfoBean->save();

            $json_respu = array("module" => $notesBean->object_name, "id" => $notesBean->id);
        

        return $json_respu;
    }

}