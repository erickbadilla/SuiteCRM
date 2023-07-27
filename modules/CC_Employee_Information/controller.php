<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'custom/include/careersQueryBuilder.php';
require_once 'custom/Extension/application/Include/inside_skill_notification.php';
require_once 'custom/Extension/application/Include/careers_bean2template.php';

use Api\V8\BeanDecorator\BeanManager;
use \BeanFactory;
use Api\V8\Config\Common as Common;
use Api\V8\Utilities;
use CC_Job_DescriptionController;
use CC_SkillController;
use CC_QualificationController;
use CC_Contact_AddressController;

require_once 'modules/CC_Job_Description/controller.php';
require_once 'modules/CC_Skill/controller.php';
require_once 'modules/CC_Skill/CC_SkillCC_Employee_InformationRelationship.php';
require_once 'modules/CC_Qualification/controller.php';
require_once 'modules/CC_Qualification/CC_QualificationCC_Employee_InformationRelationship.php';
require_once 'modules/CC_Contact_Address/controller.php';
require_once 'modules/CC_Professional_Experience/controller.php';

$errorLevelStored = error_reporting();
error_reporting(0);
require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
require_once('modules/AOS_PDF_Templates/templateParser.php');
require_once('modules/AOS_PDF_Templates/sendEmail.php');
require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');
error_reporting($errorLevelStored);

class CC_Employee_InformationController extends SugarController {

    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Employee_InformationController() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    private function array_change_key_case_recursive($arr)
    {
        return array_map(function($item){
            if(is_array($item))
                $item = self::array_change_key_case_recursive($item);
            return $item;
        },array_change_key_case($arr,CASE_LOWER));
    }

    /**
     *
     * @param array $arrIds
     * @param bool $format = true;
     * @return array
     */
    public function getRecordsByIds(array $arrIds, bool $format = true) {

        if($format){
            $sql = "SELECT ei.visa_expiration 'Visa_Expiration', ei.has_visa 'Visa', ei.status 'Status', ei.phone_number 'Phone', ei.passport_expiration 'Passport_Expiration', 
        ei.has_passport 'Passport', ei.name 'Name', ei.is_married 'Married', ei.identity_document 'Identity_Document', ei.id 'Id', ei.gender 'Gender', 
        ei.current_email 'Email', ei.country_law 'Country_Law', ei.children 'Children', ei.car_plate 'Car_Plate', ei.date_of_birth 'Birthdate', 
        ei.bank_account 'Bank_Account', ei.assigned_role 'Assign_Role', ei.home_address 'Address', ei.".Common::$employeePositionField." 'positionId',
        ei.description 'Description', ei.is_assigned 'Assigned', ei.active 'Active', ei.project_id_c 'Project', ei.is_professional_service 'isProfessionalService'  
      FROM ".Common::$employeeTable." ei
      WHERE ei.deleted = 0";
        } else {
            $sql = "SELECT ei.visa_expiration,  ei.has_visa, ei.status, ei.phone_number, ei.passport_expiration, 
        ei.has_passport, ei.name, ei.is_married, ei.identity_document, ei.id, ei.gender, 
        ei.current_email, ei.country_law, ei.children, ei.car_plate, ei.date_of_birth, 
        ei.bank_account, ei.assigned_role, ei.home_address, ei.".Common::$employeePositionField." 'positionId',
        ei.description, ei.is_assigned, ei.active, ei.project_id_c, ei.is_professional_service FROM ".Common::$employeeTable." ei WHERE ei.deleted = 0";
        }

        if (!empty($arrIds))  {
            $sql .= " AND ei.id IN ('".implode("', '", $arrIds)."')";
        }

        $sql .= " ORDER BY ei.name";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            if($format){
                $row['Visa'] = ($row['Visa'] == '1');
                $row['Passport'] = ($row['Passport'] == '1');
                $row['Married'] = ($row['Married'] == '1');
                $row['Assigned'] = ($row['Assigned'] == '1');
                $row['Active'] = ($row['Active'] == '1');
                $row['isProfessionalService'] = ($row['isProfessionalService'] == '1');
            } else {
                $row['has_visa'] = ($row['has_visa'] == '1');
                $row['has_passport'] = ($row['has_passport'] == '1');
                $row['is_married'] = ($row['is_married'] == '1');
                $row['is_assigned'] = ($row['is_assigned'] == '1');
                $row['active'] = ($row['active'] == '1');
                $row['is_professional_service'] = ($row['is_professional_service'] == '1');
            }

            $position = (new CC_Job_DescriptionController)->getRecordsByIds(explode(",", $row['positionId']));
            if($format){
                $row['Position'] = $position[0];
            } else {
                $row['position'] = $position[0];
            }
            unset($row["positionId"]);

            $id = ($format)? $row['Id']:$row['id'];

            $skills = (new CC_SkillController)->getRecordsByEmployeeId($id);
            if($format) {
                $row['EmployeeSkills'] = $skills;
            } else {
                $row['skills'] = $this->array_change_key_case_recursive($skills);
            }

            $quas = (new CC_QualificationController)->getRecordsByEmployeeId($id);
            if($format) {
                $row['EmployeeQualifications'] = $quas;
            } else {
                $row['qualifications'] = $this->array_change_key_case_recursive($quas);
            }


            $exp = (new CC_Professional_ExperienceController)->getRecordsByEmployeeId($id);
            if($format) {
                $row['EmployeeProfessionalExperience'] = $exp;
            } else {
                $row['experience'] = $this->array_change_key_case_recursive($exp);
            }

            $result[] = $row;
        }

        return $result;
    }


    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getPersonalityTestRecordsByIds(array $arrIds) {

        $sql = "SELECT ei.name 'name', pt.id AS 'pattern_id', pt.pattern AS 'pattern', pt.score_index AS 'score' 
        FROM ".Common::$employeeTable." AS ei 
        JOIN ".Common::$eiptRelationName." AS rel ON ei.id = rel.".Common::$eiptRelationFieldA." 
        JOIN ".Common::$personalitytestTable." AS pt ON pt.id = rel.".Common::$eiptRelationFieldB." 
        WHERE ei.deleted = 0 and rel.deleted = 0";

        if (!empty($arrIds))  {
            $sql .= " AND ei.id IN ('".implode("', '", $arrIds)."')";
        }

        $sql .= " ORDER BY ei.name";

        // Get an instance of the database manager
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

    /**
     * @param object $employee
     * @return bool|SugarBean
     */
    public function saveEmployeeRecord(object $employee){
        if (is_null($employee->Position)) {

        }

        $jdId = null;
        if($employee->Position){
            $jdId = $this->saveJobDescription($employee->Position);
            if(!$jdId){
                throw new \InvalidArgumentException('Invalid Position');
            }
        }

        $employeePositionField = Common::$employeePositionField;

        if(!property_exists($employee,'Id')){
            $employeeBean = (Utilities::getCustomBeanByName('CC_Employee_Information', $employee->Name)) ? Utilities::getCustomBeanByName('CC_Employee_Information', $employee->Name) : BeanFactory::newBean('CC_Employee_Information');
        } else {
            $employeeBean = BeanFactory::getBean('CC_Employee_Information');
            $employeeBean->id = $employee->Id;
            $employeeBean->new_with_id = true;
        }

        $isProfessionalServices = "0";
        if(property_exists($employee,'ProfessionalServices')){
            $isProfessionalServices = ($employee->ProfessionalServices)?"1":"0";
        }

        $employeeBean->name = $employee->Name;
        $employeeBean->visa_expiration = $employee->Visa_Expiration;
        $employeeBean->has_visa = $employee->Visa;
        // $employeeBean->status = $employee->Status;
        $employeeBean->phone_number = $employee->Phone;
        $employeeBean->passport_expiration = $employee->Passport_Expiration;
        $employeeBean->has_passport = $employee->Passport;
        $employeeBean->is_married = $employee->Married;
        $employeeBean->identity_document = $employee->Identity_Document;
        $employeeBean->id = $employee->Id;
        $employeeBean->gender = $employee->Gender;
        $employeeBean->current_email = $employee->Email;
        $employeeBean->country_law = $employee->Country_Law;
        $employeeBean->children = $employee->Children;
        $employeeBean->car_plate = $employee->Car_Plate;
        $employeeBean->date_of_birth = $employee->Birthdate;
        // $employeeBean->bank_account = $employee->Bank_Account;
        $employeeBean->assigned_role = $employee->Assign_Role;
        $employeeBean->home_address = $employee->Address;
        $employeeBean->description = $employee->Description;
        $employeeBean->is_assigned = ($employee->Assigned)?1:0;
        $employeeBean->active = ($employee->Active)?1:0;
        // $employeeBean->project_id_c = $employee->Project;
        $employeeBean->start_date = $employee->StartDateStr;
        $employeeBean->state = $employee->AddressState;
        $employeeBean->city = $employee->AddressCity;
        $employeeBean->territory = $employee->AddressDistrict;
        $employeeBean->tshirt_size = $employee->TShirtSize;
        $employeeBean->blood_type = $employee->BloodType;
        $employeeBean->end_date = $employee->ContractEndDateStr;
        $employeeBean->end_date_reason = $employee->ReasonEndDate;
        $employeeBean->timetask_api_token = $employee->TimetaskApiToken;
        $employeeBean->timetask_person_id = $employee->TimetaskPersonId;
        $employeeBean->is_professional_service = $isProfessionalServices;


        if(!is_null($jdId)){
            $employeeBean->$employeePositionField = $jdId;
        }
        $result = $employeeBean->save();

        $this->saveEmployeeSkill($employeeBean, $employee->EmployeeSkills);
        $this->saveEmployeeQualification($employeeBean, $employee->EmployeeQualifications);   
       
        $this->saveEmployeeExperience($employeeBean, $employee->Experiences);
        if(property_exists($employee,'OtherAddress')){
            $this->saveEmployeeOtherAddress($employeeBean, $employee->OtherAddress);
        }

        return $employeeBean;
    }

    /**
     * @param object $jobDescription
     * @return string
     */
    public function saveJobDescription(object $jobDescription) {
        $exists = Utilities::getCustomBeanByName('CC_Job_Description', $jobDescription->Name);
        if ($exists) {
            $result = $exists->id;
        } else {
            $jdBean= BeanFactory::newBean('CC_Job_Description');
            $jdBean->name = $jobDescription->Name;
            $jdBean->relate_role = $jobDescription->Role;
            $jdBean->objectives = $jobDescription->Objectives;
            $result = $jdBean->save();
        }

        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $employeeSkills
     * @return array
     */
    public function saveEmployeeSkill(\SugarBean $parentBean, array $employeeSkills) {
        $eisRelation = 'cc_employee_information_cc_skill';
        global $current_user;
        $user = BeanFactory::getBean('Users', $current_user->id);
        // TODO check for permissions
        $parentBean->load_relationship($eisRelation);

        $result = [];

        foreach ($employeeSkills as $employeeSkill) {
            $employeeSkill->Skill->SkillName = $employeeSkill->Skill->Name;
            $employeeSkill->Skill->SkillType = $employeeSkill->Skill->Type;

            $skillBean = BeanFactory::getBean('CC_Skill', $employeeSkill->Skill->Id);

            if(!$skillBean){
                $skillBean = (new CC_SkillController)->saveSkillRecord($employeeSkill->Skill);
            }

            $parentBean->$eisRelation->add($skillBean);

            $rSkillBean = new CC_SkillCC_Employee_InformationRelationship();

            $relatedRow = $rSkillBean->get_relation_row($parentBean->id, $skillBean->id);

            if ($relatedRow) {
                if(strtolower($employeeSkill->Type)=='years_of_experience'){
                    $relatedRow->years = $employeeSkill->Amount;
                }
                if(strtolower($employeeSkill->Type)=='rating'){
                    $relatedRow->rating = $employeeSkill->Amount;
                    if($employeeSkill->Amount>5){
                        $relatedRow->rating = 5;
                    }
                    if($employeeSkill->Amount<0){
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

    public function syncEmployeeSkill(\SugarBean $parentBean, $employeeSkillsData) {
        $eisRelation = 'cc_employee_information_cc_skill';
        global $current_user;
        $user = BeanFactory::getBean('Users', is_null($current_user->id)?1:$current_user->id);
        // TODO check for permissions
        $parentBean->load_relationship($eisRelation);

        $result = [];
        $dataSkill = $employeeSkillsData->DataSkill;

        $SkillType = ($dataSkill['Rating'] != 0 && $dataSkill['Rating'] !== null) ? "rating" : "years_of_experience";
        $skillBean = BeanFactory::getBean('CC_Skill', $dataSkill['IdSkill']);
        $newSkill = false;
        if(!$skillBean){
            $employeeSkill = new stdClass();
            $employeeSkill->SkillName = $dataSkill['SkillName'];
            $employeeSkill->skill_type = $SkillType;             
            $skillBean = (new CC_SkillController)->saveSkillRecord($employeeSkill, $dataSkill['IdSkill']);
            $newSkill = $skillBean->id;
        }

        $rowResult = $parentBean->$eisRelation->add($skillBean);
        $rSkillBean = new CC_SkillCC_Employee_InformationRelationship();
        $relatedRow = $rSkillBean->get_relation_row($parentBean->id, $skillBean->id);

        $result = false;
        if ($relatedRow) {
            $relatedRow->type = $SkillType;
            if(strtolower($SkillType)=='years_of_experience'){
                $relatedRow->years = $dataSkill['YearsExperience'];
            }
            if(strtolower($SkillType)=='rating'){
                $relatedRow->rating = $dataSkill['Rating'];
            }
            $relatedRow->modified_user_id = $user->id;

            $rSave = $relatedRow->save();
            if($rSave){
                $result = array(
                    "row"=>$rSave,
                    "skill"=> (object) array(
                        "name"=>$skillBean->name,
                        "id"=>$skillBean->id,
                        "new" => (bool) $newSkill
                     )
                );
            }
        }
        
        return $result;
    }


    /**
     * @param SugarBean $parentBean
     * @param array $employeePersonalityTests
     * @return array
     */
    public function saveEmployeePersonalityTest(\SugarBean $parentBean, array $employeePersonalityTests) {
        $eiptRelation = 'cc_employee_information_cc_personality_test';
        global $current_user;
        $user = BeanFactory::getBean('Users', $current_user->id);
        $pattern_list = [
            'adviser' => 'Adviser',
            'agent' => 'Agent',
            'baffling' => 'Baffling',
            'creative' => 'Creative',
            'encouraging' => 'Encouraging',
            'evaluator' => 'Evaluator',
            'investigator' => 'Investigator',
            'objective' => 'Objective',
            'overactive' => 'Overactive',
            'perfectionist' => 'Perfectionist',
            'persuasive' => 'Persuasive',
            'producer' => 'Producer',
            'professional' => 'Professional',
            'promoter' => 'Promoter',
            'resolutive' => 'Resolutive',
            'results-oriented' => 'Results Oriented',
            'specialist' => 'Specialist',
            'subactive' => 'Subactive'
        ];

        $parentBean->load_relationship($eiptRelation);

        $result = [];

        foreach ($employeePersonalityTests as $empPersonalityTest) {
            $personalityTestData = Utilities::keysToLower($empPersonalityTest);
            $personalitytestBean = BeanFactory::newBean('CC_Personality_Test');
            $personalitytestBean->pattern = $personalityTestData->pattern;
            $personalitytestBean->score_index = $personalityTestData->score;
            $personalitytestBean->modify_by_user = $user->id;

            if(array_key_exists(strtolower($personalitytestBean->pattern),$pattern_list) && preg_match('/[0-7]{4}/', $personalitytestBean->score_index) == 1){
                $personalitytestBean->save();
                $parentBean->$eiptRelation->add($personalitytestBean);
                $result[] = $personalitytestBean;
            }else {
                throw new \InvalidArgumentException('Invalid personality test.');
            }
        }
        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $employeeQuas
     * @return array
     */
    public function saveEmployeeQualification(\SugarBean $parentBean, array $employeeQuas) {
        $eiqRelation = 'cc_employee_information_cc_qualification';

        $parentBean->load_relationship($eiqRelation);

        $result = [];

        foreach ($employeeQuas as $eQua) {
            $eQua->Qualification->MinimumRequired = $eQua->Qualification->Minimum_Required;
            $eQua->Qualification->DigitalSupportRequired = $eQua->Qualification->Digital_Support;

            $eQuaBean = (new CC_QualificationController)->saveQualificationRecord($eQua->Qualification);

            $parentBean->$eiqRelation->add($eQuaBean);

            $rQuaBean = new CC_QualificationCC_Employee_InformationRelationship();

            $relatedRow = $rQuaBean->get_relation_row($parentBean->id, $eQuaBean->id);
            if ($relatedRow) {
                $relatedRow->actual_qualification = $eQua->ActualQualification;
                $relatedRow->has_digital_support = $eQua->HasDigitalSupport;
                $relatedRow->save();
            }

            $result[] = $parentBean;
        }
        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $otherAddress
     * @return array
     */
    public function saveEmployeeOtherAddress(\SugarBean $parentBean, array $otherAddress)
    {
        $relationName = "cc_employee_information_cc_contact_address";
        $parentBean->load_relationship($relationName);
        $result = [];

        $actualRelatedBean = $parentBean->$relationName->getBeans();
        foreach ($actualRelatedBean as $actualRelated){
            // $parentBean->$relationName->delete($parentBean->id, $actualRelated);
            $actualRelated->mark_deleted($actualRelated->id);
            $actualRelated->save();
        }

        foreach($otherAddress as $address){
            $addressBean = (new CC_Contact_AddressController)->saveAddressRecord($address);
            if($addressBean->id){
                $parentBean->$relationName->add($addressBean);
                $result[] = $addressBean;
            }
        }
        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $employeeExp
     * @return array
     */
    public function saveEmployeeExperience(\SugarBean $parentBean, array $employeeExp) {
        $eiqRelation = 'cc_professional_experience_cc_employee_information';

        $parentBean->load_relationship($eiqRelation);

        $result = [];

        foreach ($employeeExp as $eQua) {
            $eQuaBean = (new CC_Professional_ExperienceController)->saveProfessional_ExperienceRecord($eQua);
            $parentBean->$eiqRelation->add($eQuaBean);

            $result[] = $parentBean;
        }
        return $result;
    }
    /**
     * @param object $employee
     * @param string $id
     * @return bool|SugarBean
     */
    public function updateEmployeeRecord(object $employee, string $id) {

        $jdId = null;
        if($employee->Position){
            $jdId = $this->saveJobDescription($employee->Position);
            if(!$jdId){
                throw new \InvalidArgumentException('Invalid Position');
            }
        }

        $employeePositionField = Common::$employeePositionField;

        $employeeBean = BeanFactory::getBean('CC_Employee_Information', $id);

        if (!$employeeBean) {
            throw new \InvalidArgumentException('Employee not found');
        }

        $isProfessionalServices = "0";
        if(property_exists($employee,'ProfessionalServices')){
            $isProfessionalServices = ($employee->ProfessionalServices)?"1":"0";
        }

        $employeeBean->name = $employee->Name;
        $employeeBean->visa_expiration = $employee->Visa_Expiration;
        $employeeBean->has_visa = $employee->Visa;
        // $employeeBean->status = $employee->Status;
        $employeeBean->phone_number = $employee->Phone;
        $employeeBean->passport_expiration = $employee->Passport_Expiration;
        $employeeBean->has_passport = $employee->Passport;
        $employeeBean->is_married = $employee->Married;
        $employeeBean->identity_document = $employee->Identity_Document;
        $employeeBean->gender = $employee->Gender;
        $employeeBean->current_email = $employee->Email;
        $employeeBean->country_law = $employee->Country_Law;
        $employeeBean->children = $employee->Children;
        $employeeBean->car_plate = $employee->Car_Plate;
        $employeeBean->date_of_birth = $employee->Birthdate;
        // $employeeBean->bank_account = $employee->Bank_Account;
        $employeeBean->assigned_role = $employee->Assign_Role;
        $employeeBean->home_address = $employee->Address;
        $employeeBean->description = $employee->Description;
        $employeeBean->is_assigned = ($employee->Assigned)?1:0;
        $employeeBean->active = ($employee->Active)?1:0;
        // $employeeBean->project_id_c = $employee->Project;
        $employeeBean->$employeePositionField = $jdId;
        $employeeBean->start_date = $employee->StartDateStr;
        $employeeBean->state = $employee->AddressState;
        $employeeBean->city = $employee->AddressCity;
        $employeeBean->territory = $employee->AddressDistrict;
        $employeeBean->tshirt_size = $employee->TShirtSize;
        $employeeBean->blood_type = $employee->BloodType;
        $employeeBean->end_date = $employee->ContractEndDateStr;
        $employeeBean->end_date_reason = $employee->ReasonEndDate;
        $employeeBean->timetask_api_token = $employee->TimetaskApiToken;
        $employeeBean->timetask_person_id = $employee->TimetaskPersonId;
        $employeeBean->is_professional_service = $isProfessionalServices;


        $employeeBean->save();

        $this->deleteEmployeeSkillByEmployeeId($employeeBean, $employee->EmployeeSkills);
        //$this->deleteEmployeeQualificationByEmployeeId($employeeBean, $employee->EmployeeQualifications);
        $this->saveEmployeeSkill($employeeBean, $employee->EmployeeSkills);
        $this->saveEmployeeQualification($employeeBean, $employee->EmployeeQualifications);
        if(property_exists($employee,'OtherAddress')){
            $this->saveEmployeeOtherAddress($employeeBean, $employee->OtherAddress);
        }
 
        if($employee->Experiences){

        $this->deleteEmployeeProfesionalExpirience($employeeBean);
        $this->saveEmployeeExperience($employeeBean, $employee->Experiences);
        }
        return $employeeBean;
    }

    private function mapSkillIdFromArray(array $employeeSkills){
        $result = [];
        foreach ($employeeSkills as $skillItem){
            if(property_exists($skillItem,'Skill')){
                if(!key_exists($skillItem->Skill->Id,$result)){
                    $result[$skillItem->Skill->Id] = $skillItem;
                }
            }
        }
        return $result;
    }

    private function mapSkillIdFromBean(array $beanSkills){
        $result = [];
        foreach ($beanSkills as $skillItem){
            if(!key_exists($skillItem->id,$result) && $skillItem->skill_type==='hard_skill' ){
                $result[$skillItem->id] = $skillItem;
            }
        }
        return $result;
    }

    /**
     * @param SugarBean $parentBean
     * @param array $employeeSkills
     * @return array
     */
    public function deleteEmployeeSkillByEmployeeId(\SugarBean $parentBean, array $employeeSkills) {
        $eisRelation = 'cc_employee_information_cc_skill';

        $parentBean->load_relationship($eisRelation);
        $actualSkills = $parentBean->get_linked_beans($eisRelation,'CC_Skill');
        $arrayKeyMap= $this->mapSkillIdFromArray($employeeSkills);
        $beanKeyMap = $this->mapSkillIdFromBean($actualSkills);
        $arrayKeysEmployee = array_keys($arrayKeyMap);
        $arrayKeysActualSkills = array_keys($beanKeyMap);
        $differences = array_diff($arrayKeysActualSkills,$arrayKeysEmployee);
        $skillIds = $parentBean->$eisRelation->get();

        foreach ($differences as $skillId) {
            $rSkillBean = new CC_SkillCC_Employee_InformationRelationship();
            $relationBean = $rSkillBean->get_relation_row($parentBean->id, $skillId);
            if ($relationBean) {
                $relationBean->mark_deleted($relationBean->id);
                $relationBean->save();
            }
        }
    }

    /**
     * @param SugarBean $parentBean
     * @param array $employeeQuas
     * @return array
     */
    public function deleteEmployeeQualificationByEmployeeId(\SugarBean $parentBean, array $employeeQuas) {
        $eiqRelation = 'cc_employee_information_cc_qualification';

        $parentBean->load_relationship($eiqRelation);

        $quaIds = $parentBean->$eiqRelation->get();

        foreach ($quaIds as $quaId) {
            $rQuaBean = new CC_QualificationCC_Employee_InformationRelationship();
            $relationBean = $rQuaBean->get_relation_row($parentBean->id, $quaId);
            if ($relationBean) {
                $relationBean->mark_deleted($relationBean->id);
                $relationBean->save();
            }
        }
    }


    public function deleteEmployeeProfesionalExpirience(\SugarBean $parentBean){

        $eiqRelation = 'cc_professional_experience_cc_employee_information';
        $parentBean->load_relationship($eiqRelation);
        $expIds = $parentBean->$eiqRelation->get();

        foreach ($expIds as $expId) {
        
            $parentBean->$eiqRelation->delete($parentBean->id, $expId);
        }   

    }
    /**
     * @param string $id
     * @return bool|SugarBean
     */
    public function deleteEmployeeRecord(string $id) {

        $employeeBean = BeanFactory::getBean('CC_Employee_Information', $id);

        if (!$employeeBean) {
            throw new \InvalidArgumentException('Employee not found');
        }

        $employeeBean->mark_deleted($employeeBean->id);
        $employeeBean->save();

        return $employeeBean;
    }

    /**
     * creates a Employee PDF using selected template
     * SOME EXTRA VARIABLES WERE ADDED
     * $cc_employee_information_soft_skills
     * $cc_employee_information_hard_skills
     * $cc_employee_information_skill_names
     * $cc_employee_information_skill_parent_languages
     * $cc_employee_information_skill_parent_operating
     * $cc_employee_information_skill_parent_other
     * $cc_employee_information_qualification_names
     * $cc_employee_information_project_table
     * @param null $employeeId
     * @param null $templateId
     * @throws Exception
     */
    public function action_CreateResume($employeeId = null, $templateId = null){
        $record = ($_REQUEST['uid'])? $_REQUEST['uid'] : $employeeId;
        global $mod_strings, $sugar_config;
        if(ACLController::checkAccess('CC_Employee_Information', 'edit', true)){
            $bean = BeanFactory::getBean('CC_Employee_Information', $record);
            $beanFields = $bean->getFieldDefinitions();
            if (!$bean) {
                sugar_die("Invalid Record");
            }
            $file_name = str_replace(" ", "_", $bean->name) . ".pdf";
            $template = BeanFactory::newBean('AOS_PDF_Templates');
            // We should use a pop-up for select this
            $template->retrieve(($_REQUEST['templateID'])?$_REQUEST['templateID'] : $templateId);

            $object_arr['CC_Employee_Information'] = $bean->id;
            $object_arr['CC_Job_Description'] = $bean->cc_job_description_id_c;
            $object_arr['Contacts'] = $bean->contact_id_c;

            $search = array('/<script[^>]*?>.*?<\/script>/si',          // Strip out javascript
                '/<[\/\!]*?[^<>]*?>/si',                                // Strip out HTML tags
                '/([\r\n])[\s]+/',                                      // Strip out white space
                '/&(quot|#34);/i',                                      // Replace HTML entities
                '/&(amp|#38);/i','/&(lt|#60);/i','/&(gt|#62);/i',
                '/&(nbsp|#160);/i','/&(iexcl|#161);/i','/<address[^>]*?>/si',
                '/&(apos|#0*39);/','/&#(\d+);/'
            );

            $bean->load_relationships();

            $replace = array('','','\1','"','&','<','>',' ',chr(161),'<br>',"'",'chr(%1)');

            // Load Skill related Info
            $skill_relation = $bean->load_relationship("cc_employee_information_cc_skill");
            $Skills = [];
            if($skill_relation){
                $skill_relation_fields = $beanFields['cc_employee_information_cc_skill_fields'];
                $rName = $skill_relation_fields['join_link_name'];
                $keyA = "cc_employee_information_cc_skillcc_employee_information_ida";
                $keyB = "cc_employee_information_cc_skillcc_skill_idb";
                $Skills = $this->load_relation_records($rName,$keyA,$keyB,$record,$bean->cc_employee_information_cc_skill);
            }

            // Load Qualification related Info
            $qualification_relation = $bean->load_relationship("cc_employee_information_cc_qualification");
            $qualifications = [];
            if($qualification_relation){
                $qualification_relation_fields = $beanFields['cc_employee_information_cc_qualification_fields'];
                $qName = $qualification_relation_fields['join_link_name'];
                $keyA = "cc_employef198rmation_ida";
                $keyB = "cc_employee_information_cc_qualificationcc_qualification_idb";
                $qualifications = $this->load_relation_records($qName,$keyA,$keyB,$record,$bean->cc_employee_information_cc_qualification);
            }

            // Load Project related Info
            $project_relation = $bean->load_relationship("cc_employee_information_project");
            $projects = [];
            if($project_relation){
                $project_relation_fields = $beanFields['cc_employee_information_project_fields'];
                $pName = $project_relation_fields['join_link_name'];
                $keyA = "cc_employee_information_projectcc_employee_information_ida";
                $keyB = "cc_employee_information_projectproject_idb";
                $projects = $this->load_relation_records($pName,$keyA,$keyB,$record,$bean->cc_employee_information_project);
                $projects = array_filter($projects, function($e) {
                    return (key_exists("description",$e) && !empty($e['description']));
                });
            }

            // Load trainings and certifications related Info
            $training_certification_relation = $bean->load_relationship("cc_training_certifications_cc_employee_information");
            $training_certification = [];
            if($training_certification_relation){
                $pName = "cc_training_certifications_cc_employee_information_c";
                $keyA = "cc_traininff55rmation_idb";
                $keyB = "cc_trainin68e7cations_ida";
                $training_certification = $this->load_relation_records($pName,$keyA,$keyB,$record,$bean->cc_training_certifications_cc_employee_information);
            }

            // Load externalProject related Info
            $external_project_relation = $bean->load_relationship("cc_professional_experience_cc_employee_information");
            $external_projects = [];
            if($external_project_relation){
                $pName = "cc_professional_experience_cc_employee_information_c";
                $keyA = "cc_profess8e9armation_idb";
                $keyB = "cc_professbc84erience_ida";
                $external_projects = $this->load_relation_records($pName,$keyA,$keyB,$record,$bean->cc_professional_experience_cc_employee_information);
            }

            // Prepare header / footer and text
            $header = preg_replace($search, $replace, $template->pdfheader);
            $footer = preg_replace($search, $replace, $template->pdffooter);
            $text = preg_replace($search, $replace, $template->description);
            $text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
            $text = preg_replace_callback('/\{DATE\s+(.*?)\}/', function ($matches) {  return date($matches[1]); }, $text );

            $converted = templateParser::parse_template($text, $object_arr);
            $header = templateParser::parse_template($header, $object_arr);
            $footer = templateParser::parse_template($footer, $object_arr);

            /*
             * Extra Variables replacement
             * $cc_employee_information_soft_skills
             * $cc_employee_information_hard_skills
             * $cc_employee_information_skill_names
             * $cc_employee_information_skill_parent_languages
             * $cc_employee_information_skill_parent_operating
             * $cc_employee_information_skill_parent_other
             * $cc_employee_information_qualification_names
             * $cc_employee_information_project_table
             */

            $tname = "$".$bean->table_name;
            $converted = str_replace( $tname.'_about', $this->get_about_employee($bean),$converted);
            $converted = str_replace( $tname.'_soft_skills', $this->get_string_data(['skill_type'=>'soft_skill'],$Skills,['name'], ', ',0,0,0),$converted);
            $converted = str_replace( $tname.'_soft_list', $this->get_string_data(['skill_type'=>'soft_skill'],$Skills,['name'], '<li>',0,0,12),$converted);

            $converted = str_replace( $tname.'_hard_skills', $this->get_string_data(['skill_type'=>'hard_skill'],$Skills,['name'], ', ',0,0,40),$converted);
            $converted = str_replace( $tname.'_skill_names', $this->get_string_data(null,$Skills,['name'], ', '),$converted);
            $converted = str_replace( $tname.'_skill_parent_languages', $this->get_string_data(['parent_skill'=>'languages'],$Skills,['name'], ', ', 1),$converted);
            $converted = str_replace( $tname.'_skill_parent_operating', $this->get_string_data(['parent_skill'=>'operating'],$Skills,['name'], ', ', 1),$converted);
            $converted = str_replace( $tname.'_skill_parent_other', $this->get_string_data(['parent_skill'=>'other'],$Skills,['name'], ', '),$converted);
            //$converted = str_replace( $tname.'_trainings_education', $this->get_string_data(['training_type'=>'Education'],$training_certification,['issuing_organization','name', 'start_year', 'end_year'], '</br> ',0 ,1),$converted);
            //$converted = str_replace( $tname.'_certification', $this->get_string_data(['training_type'=>'Certification'],$training_certification,['name'], '\n ',0 ,1),$converted);
            $converted = str_replace( $tname.'_qualification_parent_education', $this->get_string_data(['parent_qualification'=>'education'],$qualifications,['name'], ', '),$converted);
            $converted = str_replace( $tname.'_qualification_parent_languages', $this->get_string_data(['parent_qualification'=>'languages'],$qualifications,['name'], ', '),$converted);
            $pTable = $this->get_table_data(null,$external_projects,['name','description','business_name'], ['Project', 'Description','Company']);
            $converted = str_replace( $tname.'_professional_experience', $pTable ,$converted);

            $professionalExperienceResult = self::loop_relation_template(
                $bean,'cc_professional_experience_cc_employee_information',
                'Professional_Experience_EmployeePDF.tpl','Professional Experience');

            $converted = str_replace( $tname.'_professionalexperience_loop', $professionalExperienceResult ,$converted);

            $pTable = $this->get_table_data(null,$projects,['name','description','role'], ['Project', 'Description','Role']);
            $converted = str_replace( $tname.'_related_project_table', $pTable ,$converted);

            $pTable = $this->get_data_template($bean,null, $projects,['name','description','role','start_date','end_date'], 'ProjectDataRelation.tpl', 'Cecropia Experiencies');
            $converted = str_replace( $tname.'_related_project_loop', $pTable ,$converted);

            $pTable = $this->get_data_template($bean,['training_type'=>'Certification'], $training_certification,['name'], '', 'Certifications', 1);
            $converted = str_replace( $tname.'_certification_loop', $pTable ,$converted);

            $pTable = $this->get_data_template($bean,['training_type'=>'Education'], $training_certification,['issuing_organization','name', 'start_year', 'end_year'], '', 'Education', 2);
            $converted = str_replace( $tname.'_trainings_education_loop', $pTable ,$converted);

            $printable = str_replace("\n", "<br />", $converted);

            $orientation = ($template->orientation == "Landscape") ? "-L" : "";

            $pdf = new mPDF('en', $template->page_size . $orientation, 10, 'Lato', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
            $pdf->SetAutoFont();
            $pdf->SetHTMLHeader($header);
            $pdf->SetHTMLFooter($footer);
            $pdf->WriteHTML($printable);
            if($_REQUEST['uid']){
                ob_clean();
                $pdf->Output($sugar_config['upload_dir'] .  $file_name, 'D');
            } else {
                return ["fileName"=>$file_name, "bufferData" => $pdf->Output( $file_name, 'S')];
            }
        } else {
            SugarApplication::appendErrorMessage(self::PERMISSION_ERROR_MESSAGE);
        }
    }
    private function get_about_employee($record){
        global $app_list_strings;
        $options = $app_list_strings['status_list'];
        $result = '<p>';

        $birthDate = explode("/", $record->date_of_birth);
        //get age from date or birthdate

        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));

        if($record->status && $options[$record->status] != ""){
            $result.= $options[$record->status].", ";
        }

        $result.= $age." Years Old, ";

        if($record->is_married){
            $result.="Married, ";
        }

        if($record->children == 1){
            $result.="1 Child";
        }
        if($record->children > 1){
            $result.= $record->children." Children, ";
        }
        if($record->has_passport){
            $result.= "Available for travel ";
            if($record->has_visa){
                $result.= 'to the United States ';
            }
        }

        return $result."</p>";
    }


    private function load_relation_records($relationName,$keyAName, $keyBName, $keyA, $relation){
        $keyB = $relation->get();
        $data = $relation->getBeans();
        $db = DBManagerFactory::getInstance();
        $whereIn = implode("','", $keyB);
        $sql = "SELECT * FROM {$relationName} WHERE {$keyAName} ='".$keyA."' and  {$keyBName} IN ('".$whereIn."') and deleted = 0";
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $relatedRecordId = $row['id'];
            $row['data']= (key_exists($row[$keyBName],$data))?$data[$row[$keyBName]]:null;
            $row['name']= (key_exists($row[$keyBName],$data))?$data[$row[$keyBName]]->name:null;
            if(key_exists($row[$keyBName],$data) && property_exists( $data[$row[$keyBName]] , 'skill_type' )){
                $row['skill_type']= $data[$row[$keyBName]]->skill_type;
                $row['parent_skill'] = $data[$row[$keyBName]]->cc_skill_cc_skill_name;
            }
            if($relationName=='cc_employee_information_cc_qualification_c'){
                $actualQualification = BeanFactory::getBean('CC_Qualification',$row[$keyBName]);
                if($actualQualification){
                    $row['parent_qualification'] = $actualQualification->cc_qualification_cc_qualification_name;
                }
            }
            if($relationName=='cc_professional_experience_cc_employee_information_c'){
                
                $actualExpirience = BeanFactory::getBean('CC_Professional_Experience',$row[$keyBName]);
                if($actualExpirience){
                    $row['description'] = $actualExpirience->description;
                    $row['business_name'] = $actualExpirience->business_name;
                }

            }
            $result["{$relatedRecordId}"] = $row;
        }
        return $result;
    }

    /**
     * @param $filter
     * @param $data
     * @return array Filtered data
     */

    private function filter_data($filter, $data){
        $result = [];
        foreach ($data as $key => $value) {
            $add = true;
            if(!is_null($filter)){
                $add = true;
                foreach ($filter as $fKey => $fVal) {
                    if (!array_key_exists($fKey, $value) || (stripos($value[$fKey],$fVal)===false)) {
                        $add=false;
                    }
                }
                if($add){
                    $result[] = $value;
                }
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $filter
     * @param $data
     * @param $fields
     * @param $separator
     * @return string from array data
     */

    private function get_string_data($filter, $data, $fields, $separator, $flag=0, $inside=0, $max = 8){

        $result = [];
        if($inside == 1){
            foreach($data as $info){
                $anarray = (array) $info['data'];

                if (array_intersect_assoc($filter, $anarray)) {
                    
                    $itemResult = '';
                    $fulldate = '';
                    $values = [];
                    foreach ($fields as $fieldName) {
                        
                        if (array_key_exists($fieldName, $anarray)) {
                            if($fieldName == "start_year"){
                                $fulldate = $anarray[$fieldName];
                            }elseif($fieldName == "end_year"){
                                $fulldate .= $anarray[$fieldName] == '0000' ? '-' : '-' . $anarray[$fieldName] ;
                                $values[] =  $fulldate;
                            }else{
                            $values[] =  $anarray[$fieldName];
                            }
                        }
                    }
                }
                $itemResult = join(', ', $values);
                if(!empty($itemResult)){
                    $result[] = $itemResult;
                }
            }
            
        }else{
            $filtered_data = $this->filter_data($filter,$data);
            foreach ($filtered_data as $item){
                $itemResult = '';
                foreach ($fields as $fieldName) {
                    $values = [];
                    if (array_key_exists($fieldName, $item)) {
                        $values[] = $item[$fieldName];
                    }
                    $itemResult = join(' ', $values);
                }
    
                if(!empty($itemResult)){
                    $result[] = $itemResult;
                }
            }
            if($max>0){
                $result = array_slice($result, 0, $max);
            }

        }
        
            
        $final_result = join($separator, $result); 
            
        $results = ($flag == 1 && strlen($final_result) > 0) ? ($final_result . ", " ) :  $final_result;
        
         return nl2br($results);   
        
        
    }

    /**
     * @param $filter
     * @param $data
     * @param $fields
     * @param $headers
     * @return string table from array data
     */

    private function get_table_data($filter, $data, $fields, $headers){
        $filtered_data = $this->filter_data($filter,$data);
        foreach ($headers as $hItem){
            $cells[] = "<th align='left'>{$hItem}</th>";
        }
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
        foreach ($filtered_data as $row) {
            $cells = array();
            foreach ($fields as $fieldName) {
                if (array_key_exists($fieldName, $row)) {
                    $cells[] = "<td>{$row[$fieldName]}</td>";
                } else{
                    $cells[] = "<td></td>";
                }
            }
            $rows[] = "<tr>" . implode('', $cells) . "</tr>";
        }
        return "<table border='1' style='width: 100%; border-collapse: collapse;'>" . implode('', $rows) . "</table>";
    }

    public function getEmployeesBySkillName($searchString){
        return (new CC_SkillController)->getEmployeesBySkill($searchString);
    }

    public function searchBy($stringNeedle, $fieldsFilter, $sort, $order, $offset, $limit){

        $queryBuilder = new CareersQueryBuilder(Common::$employee, $fieldsFilter);
        $queryBuilder->withLimitOffset($limit,$offset);
        $queryBuilder->withSearch($stringNeedle);
        $queryBuilder->withSort($sort);
        $queryBuilder->withOrder($order);

        $sql = $queryBuilder->getSQL();

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            if(key_exists("active",$row)){
                $row["active"] = $row["active"]=="1";
            }
            if(key_exists("is_assigned",$row)){
                $row["is_assigned"] = $row["is_assigned"]=="1";
            }
            $result[] = $row;
        }
        return $result;
    }

    public function getRelatedProjects($employeeId){
        $sql = "SELECT b.id, b.cc_employee_information_projectproject_idb projectID , project.name,  project.status,  b.start_date, b.end_date, b.description, b.role  FROM cc_employee_information_project_c b JOIN project ON b.cc_employee_information_projectproject_idb = project.id WHERE b.deleted=0 ";
        if (!is_null($employeeId)) {
            $sql.=" AND cc_employee_information_projectcc_employee_information_ida ='$employeeId'";
        }
        $sql.= ' ORDER BY b.start_date DESC';
        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row;
        }
        return $result;
    }

	 
    function GetEmployeesInformation($filterColumData){

        global $app_list_strings;
        $result = array();
        $active = $filterColumData[0]['filter_active'];
        $project = $filterColumData[0]['filter_project'];

        $employee = BeanFactory::getBean('CC_Employee_Information');
        $projects = BeanFactory::getBean('Project');

        $whereActive = ($active == 1) ? "cc_employee_information.active = 1" : '';
        $whereProject = ($project == 1) ? "cc_employee_information.is_assigned = 1" : '';
        $where = ($active == 1 && $project == 1) ? ($whereActive . ' and ' .  $whereProject) : ($active == 1 ? $whereActive : ($project == 1 ? $whereProject : ""));
        
        $project_list = array();

        if($projects){ 
            $projectsFields = $projects->get_full_list();
            foreach($projectsFields as $key => $item){
                $parray = array();
                $parray[$projectsFields[$key]->id]   = $projectsFields[$key]->name;
                $project_list[] = $parray; 
            }
        }

        if($employee){ 
            $employeeFields = $employee->get_full_list("",$where, false, 0);

            foreach($employeeFields as $key => $item){
               
                $array = array();
                $array['id']            = $employeeFields[$key]->id;
                $array['object_name']   = $employeeFields[$key]->object_name;
                $array['name']          = $employeeFields[$key]->name;
                $array['country_law']   = $employeeFields[$key]->country_law;
                $array['status']        = $app_list_strings['status_list'][$employeeFields[$key]->status] ?? '';
                $array['position']      = $employeeFields[$key]->position;
                $array['project_id_c']  = $employeeFields[$key]->project_id_c;
                $array['english_level'] = $employeeFields[$key]->english_level;
                $array['role']          = $app_list_strings['assigned_role_list'][$employeeFields[$key]->assigned_role] ?? '';
                $array['project']       = $employeeFields[$key]->project;
                $array['has_passport']  = $employeeFields[$key]->has_passport;
                $array['active']        = $employeeFields[$key]->active  ;
                $array['has_visa']      = $employeeFields[$key]->has_visa;
                $array['is_assigned']   = $employeeFields[$key]->is_assigned;
                $array['project_list']  = $project_list;
                $result[] = $array; 
              }
        
        }

        $json_data = array(
            "data" => $result
        );

        return $json_data;
    }


    public function quickEditEmployee($params){

        $employeeInformation = BeanFactory::getBean('CC_Employee_Information', $params['id_employee']);

        switch ($params['change']) {
            case 1:
              $employeeInformation->project_id_c = $params['info'];
              $this->addProjectoToEmployee($employeeInformation, $params['info']);
              break;
            case 2:
                $employeeInformation->english_level = $params['info'];
              break;
            case 3:
                $employeeInformation->has_passport = $params['info'];
              break;
              case 4:
                $employeeInformation->active = $params['info'];
              break;
              case 5:
                $employeeInformation->has_visa = $params['info'];
              break;
            default:
            $employeeInformation->is_assigned = $params['info'];
          }

        $employeeInformation->save();

    }


    public function inactivateEmployee($params){

        $employeeInformation = BeanFactory::getBean('CC_Employee_Information', $params['id_employee']);
            $employeeInformation->active = 0;
        $employeeInformation->save();

    }

    public function changeAnniversary($params){

        $employeeInformation = BeanFactory::getBean('CC_Employee_Information', $params['anniversary']);
        $employeeInformation->remind_anniversary = $params['id_employee'];
        $employeeInformation->save();

    }

    public function getThoseWhoHavePMRoleByProjectId($project_id)
    {
        $sql = "SELECT cc_employee_information_projectcc_employee_information_ida, `role` 
        FROM cc_employee_information_project_c AS eip
        WHERE (eip.`role`='tech_lead' OR eip.role='project_manager') AND eip.cc_employee_information_projectproject_idb='".$project_id."' AND eip.deleted=0;";
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $result = [];
        // Fetch the row
        $module_name = 'CC_Employee_Information';
        while ($row = $db->fetchRow($rows)) {
            $employee_id = $row["cc_employee_information_projectcc_employee_information_ida"];
            $result[] = BeanFactory::getBean($module_name, $employee_id);
        }

        return $result;
    }

    public function addProjectoToEmployee($employee, $selectedProjectId)
    {
        $today = date("Y-m-d H:i:s");
        $sql = "SELECT * FROM cc_employee_information_project_c ceipc  
        WHERE ceipc.deleted = 0 AND ceipc.cc_employee_information_projectcc_employee_information_ida = '".$employee->id."'
        ORDER BY start_date DESC
        LIMIT 1";
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);    
        // Fetch the row
        $oldProjectInfo = false;
        while ($row = $db->fetchRow($rows)) {
            $oldProjectInfo = $row;
        }

        if($oldProjectInfo){
            $sql2 = "UPDATE cc_employee_information_project_c ceipc
            SET end_date = '".$today."'
            WHERE ceipc.id = '".$oldProjectInfo['id']."'";
            $db->query($sql2);
        }

        $relationEP = "cc_employee_information_project";
        $beanOldProject = BeanFactory::getBean('Project', $oldProjectInfo["cc_employee_information_projectproject_idb"]);
        $project = BeanFactory::getBean('Project', $selectedProjectId);
        $employee->load_relationship($relationEP);
        if(!is_null($beanOldProject->id)){
            $employee->$relationEP->delete($employee->id, $beanOldProject);
        }
        $result = $employee->$relationEP->add($project);

        if($result){
            $sql3 = "SELECT * FROM cc_employee_information_project_c ceipc  
                WHERE ceipc.deleted = 0 AND ceipc.cc_employee_information_projectcc_employee_information_ida = '".$employee->id."'
                AND ceipc.cc_employee_information_projectproject_idb = '".$project->id."'";
            $rows2 = $db->query($sql3);    
            // Fetch the row
            while ($row = $db->fetchRow($rows2)) {
                $newProjectInfo = $row;
            }
            
            $sql4 = "UPDATE cc_employee_information_project_c ceipc
            SET end_date='".$employee->end_date."', start_date='".$employee->start_date."', `role`='".$employee->assigned_role."'
            WHERE ceipc.id = '".$newProjectInfo['id']."'";
            $db->query($sql4);
        }

        return $result;
    }

    function cmpDates(SugarBean $a, SugarBean $b) {
        return ($a->getFieldValue('start_date') > $b->getFieldValue('end_date'));
    }

    /**
     * @param $bean Base Bean
     * @param $relation relation name
     * @param $template template file
     * @param $title section title
     * @return string
     */
    private function loop_relation_template($bean, $relation, $template, $title){

        $relatedBeans = $bean->get_linked_beans($relation, $bean, array(), 0, -1, 0,  "1=1");
        usort($relatedBeans,
            function (SugarBean $a, SugarBean $b) {
                return ($a->getFieldValue('start_date') > $b->getFieldValue('end_date'));
            });
        $result = '';
        if(count($relatedBeans)){
            $result = "<h3><span style=\"font-family: 'times new roman', times; font-size: x-large; color: #8b8b8b\">".$title."</span></h3>";
            foreach ($relatedBeans as $relatedBean){
                $tplBuilder = new careers_bean2template($relatedBean);
                $result .= $tplBuilder->fetch($template);
            }
        }
        return $result;
    }

    /**
     * @param $bean base bean for locate template
     * @param $filter requiered filter
     * @param $data current data
     * @param $fields String field list
     * @param $template template file
     * @param $title title for section
     * @return string
     */
    private function get_data_template($bean, $filter, $data, $fields, $template, $title, $list=0){
        $result = '';
        if($list != 0){

            $result = "<h3><span style=\"font-family: 'times new roman', times; font-size: x-large; color: #8b8b8b\">".$title."</span></h3><ul>";

            foreach($data as $info){
                $anarray = (array) $info['data'];
                $fullinfo = '';
                if (array_intersect_assoc($filter, $anarray)) {
                    
                    foreach ($fields as $fieldName) {
                        if (array_key_exists($fieldName, $anarray)) {
                            if($list == 2){
                                $fullinfo .= ($fieldName == 'start_year' ? $anarray[$fieldName] . '-' : ($fieldName == 'end_year' ? ($anarray[$fieldName] == '0000' ? 'In Progress.' : $anarray[$fieldName] . '.') : $anarray[$fieldName] . ", " ) );
                            }else{
                                $fullinfo .= $anarray[$fieldName];
                            }
                        }
                    }
                }
                $result .= "<li>" . $fullinfo ."</li>";
                }
                $result .= "</ul>";
        }else{           

        $filtered_data = $this->filter_data($filter,$data);
        usort($filtered_data,
            function ($a,  $b) { return ($a['start_date'] > $b['end_date'] ); });
        if(count($filtered_data)){
            $result = "<h3><span style=\"font-family: 'times new roman', times; font-size: x-large; color: #8b8b8b\">".$title."</span></h3>";
            
            foreach ($filtered_data as $row) {
                $tplBuilder = new careers_bean2template($bean);
                $result .= $tplBuilder->fetch($template,$row,$fields);
            }
        }
    }

        return $result;
    }

    public function getAllInformation(){
        $sql = "SELECT e.name, e.identity_document,e.gender, e.date_of_birth,
                    e.current_email,e.phone_number,e.bank_account, e.is_married,
                    e.children,e.has_passport,e.passport_expiration,e.has_visa,
                    e.visa_expiration,e.active,e.is_assigned,e.car_plate,
                    p.name AS project,e.english_level,e.description,d.name AS POSITION, d.relate_role,  e.`status`, 
                    e.start_date, e.end_date, e.country_law, e.assigned_role, e.home_address, 
                    e.territory AS 'District/Zone/Town',e.city, 
                    CONCAT_WS(' ', c.first_name,  c.last_name) AS 'Emergency Contact',
                    CONCAT_WS(' ', co.first_name, co.last_name) AS 'Related Contact',
                    e.tshirt_size, e.blood_type
                FROM cc_employee_information e
                LEFT JOIN project p ON p.id=e.project_id_c
                LEFT JOIN cc_job_description d ON d.id=e.cc_job_description_id_c
                LEFT JOIN contacts c ON c.id=e.contact_id_c
                LEFT JOIN contacts co ON co.id=e.contact_id1_c";

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $name = $db->query($sql);
        $names = $db->fetchRow($name);

        $html = '<table id="tblData" style="display:none"><tr>';
        
        foreach(array_keys($names) as $name){
            $html .= '<td>' . $name . '</td>';
        }
        $html .= '</tr>';

        while ($row = $db->fetchRow($rows)) {
            $html .= '<tr>';
            foreach($row as $data){
                $html .= '<td>' . str_replace("#","Num",$data) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }

    /**
     * @param SugarBean $parentBean
     * @param $employeeQuasData
     * @return array
     */
    public function syncEmployeeQua(\SugarBean $parentBean, $employeeQuasData) {

        $eisRelation = 'cc_employee_information_cc_qualification';
        global $current_user;

        $user = BeanFactory::getBean('Users', $current_user->id);

        $parentBean->load_relationship($eisRelation);
        $result = [];
        $dataQua = $employeeQuasData->DataQua;
        $quaBean = BeanFactory::getBean('CC_Qualification', $dataQua['IdQua']);

        if(!$quaBean){
            $employeeQua = new stdClass();
            $employeeQua->name = $dataQua['QualificationName'];
            $employeeQua->description =  $dataQua['Description'];             
            $employeeQua->digital_support_required =  $dataQua['DigitalSupportRequired'];             
            //$employeeQua->mininum_requiered =  $dataQua['XXX'];             
            $quaBean = (new CC_QualificationController)->saveQualificationRecord($employeeQua, $dataQua['IdQua']);
        }

        $parentBean->$eisRelation->add($quaBean);
        $rQuaBean = new CC_QualificationCC_Employee_InformationRelationship();
        $relatedRow = $rQuaBean->get_relation_row($parentBean->id, $quaBean->id);

        if ($relatedRow) {
            //$relatedRow->actual_qualification = $dataQua['XXX'];
            $relatedRow->has_digital_support = $dataQua['HasDigitalSupport'];
            $relatedRow->modified_user_id = $user->id;
            $rSave = $relatedRow->save();
            if($rSave){
                $result[] = $rSave;
            }
        }

        return $result;

    }

    /**
     * @param SugarBean $parentBean
     * @param $employeeTraiCertData
     * @return false
     */
    public function syncEmployeeTrainingCertification(\SugarBean $parentBean, $employeeTraiCertData) {

        $eisRelation = 'cc_training_certifications_cc_employee_information';
        global $current_user;

        $user = BeanFactory::getBean('Users', $current_user->id);

        $parentBean->load_relationship($eisRelation);

        $dataTraiCert = $employeeTraiCertData->DataTraiCert;
        $employeetraicert_id = $dataTraiCert['IdTraiCert'];
        $employeeTraiCert = BeanFactory::getBean('CC_Training_Certifications', $employeetraicert_id);

        $result = false;
        if(!$employeeTraiCert) {
            $employeeTraiCert = BeanFactory::newBean('CC_Training_Certifications');
            $employeeTraiCert->id = $employeetraicert_id;
            $employeeTraiCert->new_with_id = $employeetraicert_id;
        }
        $employeeTraiCert->name = $dataTraiCert['Name'];
        $employeeTraiCert->description =  $dataTraiCert['Description'];
        $employeeTraiCert->issuing_organization =  $dataTraiCert['IssuingOrganization'];
        $employeeTraiCert->training_type =  $dataTraiCert['Type'];
        $employeeTraiCert->level_reached =  $dataTraiCert['LevelReached'];
        $employeeTraiCert->start_month =  $dataTraiCert['StartMonth'];
        $employeeTraiCert->start_year =  $dataTraiCert['StartYear'];
        $employeeTraiCert->end_month =  $dataTraiCert['EndMonth'];
        $employeeTraiCert->end_year =  $dataTraiCert['EndYear'];
        $employeeTraiCert->never_expire =  $dataTraiCert['NeverExpire'];
        $employeeTraiCert->expires_on_month =  $dataTraiCert['MonthExpiresOn'];
        $employeeTraiCert->expires_on_year =  $dataTraiCert['YearExpiresOn'];
        $employeeTraiCert->certification_id =  $dataTraiCert['CertId'];
        $employeeTraiCert->cert_url =  $dataTraiCert['CertURL'];
        $saveResult = $employeeTraiCert->save();
        if($saveResult){
            $result = $parentBean->$eisRelation->add($employeeTraiCert);
        }

        return $result;
    }


    public function getEmployeeByName(string $name)
    {

        $bean = BeanFactory::getBean('CC_Employee_Information');
        $where = "cc_employee_information.name like '%".$name."%'";
        $list =  $bean->get_full_list('name', $where ,false,0);

        $result = [];
        foreach ($list as $item){
            $result[] = (object) [
                "id" => $item->id,
                "name" => $item->name,
            ];
        }
        return $result;

    }

    public function update_rating($id, $rating)
    {
        $today = date("Y-m-d H:i:s");

        $db = DBManagerFactory::getInstance();
          
            $sql = "UPDATE cc_employee_information_cc_skill_c eis
            SET eis.date_modified='".$today."', eis.amount='".$rating."'
            WHERE eis.id = '".$id."'";
            $db->query($sql);
        

        return $result;
    }

    /**
     * @param $employee_id
     * @param $skill_id
     * @return true
     */
    public function deleteEmployeeSkillRecord($employee_id, $skill_id) {
        $eisRelation = 'cc_employee_information_cc_skill';
        $employeeBean = BeanFactory::getBean('CC_Employee_Information', $employee_id);
        $employeeBean->load_relationship($eisRelation);

        $actualSkills = $employeeBean->get_linked_beans($eisRelation,'CC_Skill');
        $beanKeyMap = $this->mapSkillIdFromBean($actualSkills);

        if(key_exists($skill_id,$beanKeyMap)){
            $rSkillBean = new CC_SkillCC_Employee_InformationRelationship();
            $relationBean = $rSkillBean->get_relation_row($employeeBean->id, $skill_id);
            if ($relationBean) {
                $relationBean->mark_deleted($relationBean->id);
                $relationBean->save();
                $notifyUtil = new \inside_skill_notification();
                $notifyUtil->sendNotification(
                    $beanKeyMap[$skill_id],
                    $employeeBean
                );
            }
        } else {
            throw new Exception("Skill Relation not found");
        }
        return true;
    }


    public function get_info($id)
    {
        $db = DBManagerFactory::getInstance();
          
            $sql = "SELECT COUNT(*) as count FROM(
                SELECT id FROM cc_candidate_cc_skill_c cs
                WHERE cs.cc_candidate_cc_skillcc_skill_idb='".$id."' AND cs.deleted=0
                UNION ALL
                SELECT id FROM cc_employee_information_cc_skill_c es
                WHERE es.cc_employee_information_cc_skillcc_skill_idb='".$id."' AND es.deleted=0) AS L";
        $results = $db->query($sql);
        
        $result = $db->fetchRow($results);

        return $result[count];
    }
    
}
