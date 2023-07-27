<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'custom/include/careersQueryBuilder.php';
use Api\V8\Config\Common as Common;
use Api\V8\Utilities;


class CC_SkillController extends SugarController{

    public function __construct() {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_SkillController() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getRecordsByIds(array $arrIds) {

        $sql = "SELECT s.id 'Id', s.name 'Name', s.skill_type 'Type', s.description 'Description', s1.name 'Parent_Name', s1.id 'Parent_Id' 
      FROM ".Common::$skillTable." s 
        LEFT JOIN ".Common::$skillRelationTable." ss ON ss.".Common::$skillRelationFieldA." = s.id AND ss.deleted = 0
        LEFT JOIN ".Common::$skillTable." s1 ON s1.id = ss.".Common::$skillRelationFieldB." AND s1.deleted = 0
        WHERE s.deleted = 0";

        if (!empty($arrIds))  {
            $sql .= " AND s.id IN ('".implode("', '", $arrIds)."')";
        }

        $sql .= " ORDER BY s.name";

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

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getRecordsByProfileId(string $profileId, $map = true) {

        // in some modules more than one profile is sent
        $varientOfNumberProfile = strpos($profileId, ',');
        if($varientOfNumberProfile === false){
            $idProfiles = " AND psp.id = '".$profileId."' ";
        }else{
            $profileArray = explode(',', $profileId);
            $dataProfile = "";
              for ($i=0; $i < count($profileArray); $i++) { 
                $dataProfile.= "'".$profileArray[$i]."',";
              }
              $dataProfile = substr($dataProfile, 0, -1);
              $idProfiles = " AND psp.id IN ($dataProfile) ";
        }


        $sql = "SELECT ps.amount 'Amount', s.description 'Description', s.name 'SkillName', s.id 'Id', REPLACE(s.skill_type, '_', ' ') 'SkillType',
    ps.years 'years_of_experience', ps.rating 'rating' 
    FROM ".Common::$psRelationTable." ps
      INNER JOIN ".Common::$profileTable." psp ON (psp.id = ps.".Common::$psFieldA." AND psp.deleted = 0 )
      INNER JOIN ".Common::$skillTable." s ON (s.id = ps.".Common::$psFieldB." AND s.deleted = 0)
    WHERE ps.deleted = 0 $idProfiles ORDER BY s.name";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $result = [];


        // Fetch the row
        while ($row = $db->fetchRow($rows)) {

            $skill = $this->getRecordsByIds(explode(",", $row['Id']));
            if($map){
                if($row['years_of_experience']){
                    $yearsrow['Skill'] = $skill[0];
                    $yearsrow['Amount'] = (double) $row['years_of_experience'];
                    $yearsrow['Type'] = 'years_of_experience';
                    unset($yearsrow['rating'],$yearsrow['years_of_experience']);

                    $result[] = $yearsrow;
                };

                if($row['rating']){
                    $ratingrow['Skill'] = $skill[0];
                    $ratingrow['Amount'] = (double) $row['rating'];
                    $ratingrow['Type'] = 'rating';
                    unset($ratingrow['rating'],$ratingrow['years_of_experience']);

                    $result[] = $ratingrow;
                };
            } else {
                $unmapped = [];
                $unmapped['id'] = $skill[0]['Id'];
                $unmapped['name'] = $skill[0]['Name'];
                $unmapped['rating'] = $row['rating'];
                $unmapped['years_of_experience'] = $row['years_of_experience'];
                $result[] = $unmapped;
            }
        }

        return $result;
    }

    public function saveSkillRecord(object $skill, $skill_id=null) {
        $skillBean = null;
        if (!property_exists($skill, 'Id')){
            if($skill->SkillName != null){
                $skillBean = (Utilities::getCustomBeanByName('CC_Skill', trim($skill->SkillName))) ? Utilities::getCustomBeanByName('CC_Skill', trim($skill->SkillName)) : BeanFactory::newBean('CC_Skill');
                $skillBean->name = trim($skill->SkillName);
                $skillBean->skill_type = $skill->SkillType;
                $skillBean->save();
            }
        } else {

            $skillList = BeanFactory::getBean('CC_Skill');

            $list = $skillList->get_full_list("", "cc_skill.id = '$skill->Id'",false,1);

            if(is_array($list)){
                throw new \InvalidArgumentException(sprintf(
                    'Skill %s with Id %s was already deleted ', $skill->SkillName, $skill->Id
                ));
            }

            $skillBean = BeanFactory::getBean('CC_Skill', trim($skill->Id));
            if(!$skillBean && $skill_id){
                //This case works when the endpoint is called from the JobApplicationEvent
                $skillBean = BeanFactory::newBean('CC_Skill');
                $skillBean->id= $skill_id;
                $skillBean->new_with_id = $skill_id;
                $skillBean->name = trim($skill->SkillName);
                $skillBean->description = (key_exists("Description",$skill))?$skill->Description:null;
                $skillBean->skill_type = $skill->SkillType;
                $skillBean->save();
            } else if(!$skillBean && is_null($skill_id) && key_exists("Name",$skill)){
                if(key_exists("Id",$skill)){
                    $skillBean = (Utilities::getCustomBeanByName('CC_Skill', trim($skill->Name)));
                    if($skillBean){
                        if((strcasecmp($skill->Name,$skillBean->name)==0)){
                            return $skillBean;
                        }
                        throw new InvalidArgumentException("Skill with name ´{$skill->Name}´ exists with different Id");
                    }
                    $skillBean = BeanFactory::newBean('CC_Skill',$skill->Id);
                    if(is_null($skillBean->id)){
                        $skillBean->id= $skill->Id;
                        $skillBean->new_with_id = $skill->Id;
                    }
                    $skillBean->name = trim($skill->Name);
                    $skillBean->description = (key_exists("Description",$skill))?$skill->Description:null;
                    $skillBean->skill_type = (key_exists("Type",$skill))?$skill->Type:"hard_skill";
                    $skillBean->save();
                }
            }
        }
        if(key_exists("Parent_Id",$skill) && !is_null($skill->Parent_Id) && !empty($skill->Parent_Id) && $skillBean instanceof SugarBean){
            $relationLoad = $skillBean->load_relationship('cc_skill_cc_skill');
            if($relationLoad){
                $relatedBean = Utilities::getCustomBeanById('CC_Skill',$skill->Parent_Id);
                if($relatedBean->id){
                    $skillBean->cc_skill_cc_skill->add($relatedBean);
                }else{
                    throw new InvalidArgumentException("Unable to load Skill Parent for {$skill->Name}");
                }
            } else {
                throw new InvalidArgumentException("Unable to load Skill Parent Relation");
            }
        }
        return $skillBean;
    }

    /**
     *
     * @param string $employeeId
     * @return array
     */
    public function getRecordsByEmployeeId(string $employeeId) {

        $sql = "SELECT eis.years 'Years', eis.rating 'Rating',  eis.".Common::$eisRelationFieldB." 'skillId'
      FROM ".Common::$eisRelationName." eis
      WHERE eis.deleted = 0 AND eis.".Common::$eisRelationFieldA." = '".$employeeId."'";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $skill = $this->getRecordsByIds(explode(",", $row['skillId']));
            unset($skill[0]['Skill_Related']);
            $row['Skill'] = $skill[0];
            $rating = $row['Rating'];
            $years = $row['Years'];
            unset($row['Amount']);unset($row['Rating']);unset($row['Years']);
            unset($row["skillId"]);
            if($years>0){
                $row['Type'] = "years_of_experience";
                $row['Amount'] = $years;
                $result[] = $row;
            }
            if($rating>0){
                $row['Type'] = "rating";
                $row['Amount'] = $rating;
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     *
     * @param string $candidateId
     * @return array
     */
    public function getRecordsByCandidateId(string $candidateId) {

        $sql = "SELECT ck.years 'Years', ck.rating 'Rating',  ck.".Common::$ckRelationFieldB." 'skillId'
        FROM ".Common::$ckRelationName." ck
        WHERE ck.deleted = 0 AND ck.".Common::$ckRelationFieldA." = '".$candidateId."'";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $skill = $this->getRecordsByIds(explode(",", $row['skillId']));
            unset($skill[0]['Skill_Related']);
            $row['Skill'] = $skill[0];
            $rating = $row['Rating'];
            $years = $row['Years'];
            unset($row['Amount']);unset($row['Rating']);unset($row['Years']);
            unset($row["skillId"]);
            if($years>0){
                $row['Type'] = "years_of_experience";
                $row['Amount'] = $years;
                $result[] = $row;
            }
            if($rating>0){
                $row['Type'] = "rating";
                $row['Amount'] = $rating;
                $result[] = $row;
            }
        }
        return $result;
    }


    /**
     *
     * @param string $employeeId
     * @return array
     */
    public function getBusinessRecordsByEmployeeId(string $employeeId) {

        $sql = "SELECT eis.amount 'Amount', eis.years 'Years', eis.rating 'Rating', eis.".Common::$eisRelationFieldB." 'skillId'
        FROM ".Common::$eisRelationName." eis
        WHERE eis.deleted = 0 AND eis.".Common::$eisRelationFieldA." = '".$employeeId."'";

        // Get an instance of the database manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $skill = $this->getRecordsByIds(explode(",", $row['skillId']));
            unset($skill[0]['Skill_Related']);
            $row['Skill'] = $skill[0];
            $result[] = $row;
        }
        return $result;
    }

    /**
     * Get Employees by Skill
     * @param string $params
     * @return array
     */
    public function getEmployeesBySkill(string $params) {
        $employeeArray = $this->getEmployeeBySkillArray($params);
        $scoredRows = $this->getScoredEmployeeArray($params,$employeeArray);
        usort($scoredRows, function($a, $b) {
            return $a['score'] <=> $b['score'];
        });
        $employees = array_reverse($scoredRows);
        $result = [];
        foreach ($employees as $employee){
            $result[$employee['id']] = $employee;
        }
        return $result;
    }


    private function rateEmployee($requestedSearch, $employee){
        $value = 0;
        if($employee['skillcount']>0){
            $skillList = preg_split('/[\,]+/', $employee['skillids'],-1,PREG_SPLIT_NO_EMPTY);
            $employeeSkills = preg_split('/[\,]+/', $employee['skills'],-1,PREG_SPLIT_NO_EMPTY);
            $skillTypes = preg_split('/[\,]+/', $employee['skilltype'],-1,PREG_SPLIT_NO_EMPTY);
            $skillAmounts = preg_split('/[\,]+/', $employee['skillamount'],-1,PREG_SPLIT_NO_EMPTY);
            $discard = [];
            $value = 0;
            foreach ($requestedSearch as $search){
                $skillName = strtolower(trim($search));
                $existPosition = array_search($skillName, array_map('strtolower', $employeeSkills));
                if($existPosition!==false) {
                    if (!in_array($discard, $skillList[$existPosition])) {
                        $discard[] = $skillList[$existPosition];
                        $value += intval(trim($skillAmounts[$existPosition])) / 5;
                    }
                } else {
                    $found = false; $i = 0;
                    for($i=0;$i<count($employeeSkills);$i++){
                        if(strpos(strtolower($employeeSkills[$i]),strtolower($search))!==false){
                            $found = true;
                            break;
                        }
                    }
                    if($found){
                        if (!in_array($discard, $skillList[$i])) {
                            $discard[] = $skillList[$i];
                            $value += (intval(trim($skillAmounts[$i]))*.95) / 5;
                        }
                    }

                }
            }
        }
        if($value>0){
            return $value/count($requestedSearch);
        }
        return $value;
    }

    private function getScoredEmployeeArray(string $params, array $employees){
        $split_strings = preg_split('/[\n\,]+/', $params);
        $skCount = count($split_strings);
        $result = [];
        if($skCount > 0){
            $i=0;
            foreach ($employees as $employee){
                $score = $this->rateEmployee($split_strings,$employee);
                $employees[$i]['score'] = $score;
                $i++;
            }
        }
        return $employees;

    }

    private function getEmployeeBySkillArray(string $params){
        $query = $this->buildEmployeeSearch($params, 0);
        $query2 = $this->buildEmployeeSearch($params, 1);
        $sql = "SELECT e.id, e.name, GROUP_CONCAT(skill.name SEPARATOR ',') as skills, COUNT(skill.name) as skillcount,
            GROUP_CONCAT(skill.skill_type SEPARATOR ',') as skilltype, GROUP_CONCAT(es.amount SEPARATOR ', ') as skillamount,
            GROUP_CONCAT(es.cc_employee_information_cc_skillcc_skill_idb SEPARATOR ',') as skillids,
            e.date_entered, e.date_modified
            FROM ".Common::$skillTable." AS skill
            INNER JOIN ".Common::$employee_skill." es ON (es.cc_employee_information_cc_skillcc_skill_idb = skill.id)
            INNER JOIN ".Common::$employee." e ON (e.id = es.cc_employee_information_cc_skillcc_employee_information_ida)
            WHERE (( ".$query." )
            AND e.active = 1 AND skill.deleted = 0 AND es.deleted = 0) 
            OR (( ".$query2." ) AND e.active = 1 )
            GROUP BY e.id";
        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row;
        }
        return $result;
    }


    /**
     * Build LIKE STATEMENT to search employee by skills
     * @param string $params
     * @return string
     */
    public function buildEmployeeSearch(string $params, $flag) {
        $split_strings = preg_split('/[\ \n\,]+/', $params);
        $string = '';
        if(count($split_strings) > 0){
            for($i = 0; $i < count($split_strings); $i++){
                if($i == 0){
                    if($flag == 0){
                        $string = "skill.name LIKE '%".$split_strings[$i]."%'";
                    }else{
                        $string = "e.name LIKE '%".$split_strings[$i]."%'";
                    }
                }else{
                    if($flag == 0){
                        $string .= " OR skill.name LIKE '%".$split_strings[$i]."%' ";
                    }else{
                        $string .= " OR e.name LIKE '%".$split_strings[$i]."%'";
                    }
                }
            }
        }
        return $string;
    }

    public function getConsolidatedRecords($sort, $order, $offset, $limit){
        $sqlOffset = ($limit && $offset)?", $offset":"";
        $sqlLimit = ($limit)? "LIMIT $limit": "";

        $sql = "SELECT skill.id, skill.name, skill.skill_type, ROUND(AVG(es.amount),2) businessRating, 
                       ROUND(AVG(es.years),2) employeeExperience , ROUND(AVG(es.rating),2) employeeRating , COUNT(e.id) employeeCount 
            FROM ".Common::$skillTable." AS skill
            INNER JOIN ".Common::$employee_skill." es ON (es.cc_employee_information_cc_skillcc_skill_idb = skill.id)
            INNER JOIN ".Common::$employee." e ON (e.id = es.cc_employee_information_cc_skillcc_employee_information_ida)
            WHERE skill.deleted = 0 AND es.deleted = 0 GROUP BY (skill.id) ORDER BY $sort $order $sqlLimit $sqlOffset ";

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getEmployeeRelatedRecords($skillId, $sort, $order, $offset, $limit){
        $sqlOffset = ($limit && $offset)?", $offset":"";
        $sqlLimit = ($limit)? "LIMIT $limit": "";

        $sql = "SELECT e.id, e.name, e.active, e.is_assigned, ROUND(es.amount,2) businessRating, ROUND(es.years,2) employeeExperience , ROUND(es.rating,2) employeeRating 
            FROM ".Common::$employee_skill." es 
            INNER JOIN ".Common::$skillTable." AS skill ON (es.cc_employee_information_cc_skillcc_skill_idb = skill.id)
            INNER JOIN ".Common::$employee." e ON (e.id = es.cc_employee_information_cc_skillcc_employee_information_ida)
            WHERE skill.id = '$skillId' AND skill.deleted = 0 AND es.deleted = 0 ORDER BY $sort $order $sqlLimit $sqlOffset ";

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $row['active'] = ($row['active']=="1");
            $row['is_assigned'] = ($row['is_assigned']=="1");
            $result[] = $row;
        }
        return $result;
    }


    public function searchBy($stringNeedle, $fieldsFilter, $sort, $order, $offset, $limit){

        $queryBuilder = new CareersQueryBuilder(Common::$skillTable, $fieldsFilter);
        $queryBuilder->withLimitOffset($limit,$offset);
        $queryBuilder->withSearch($stringNeedle);
        $queryBuilder->withSort($sort);
        $queryBuilder->withOrder($order);

        $sql = $queryBuilder->getSQL();

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            if(key_exists("published",$row)){
                $row["published"] = $row["published"]=="1";
            }
            $result[] = $row;
        }
        return $result;
    }

    public function searchByTerm($term)
    {
        $sql = "SELECT * from ".Common::$skillTable." where deleted = 0 AND name LIKE '%$term%'";
        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $results = [];
        while ($row = $db->fetchRow($rows)) {
            $results[] = (object) [
                'id' => $row['id'],
                'text' => $row['name']
            ];
        }
        return $results;
    }


    public function getSkillQuestions($skillId){
        global $app_list_strings;
        $result = [];
        $cc_skill_cc_questions = 'cc_skill_cc_questions';

        $skillBean = BeanFactory::getBean('CC_Skill',$skillId);

        if($skillBean){
            $skillBean->load_relationship($cc_skill_cc_questions);
            $skillAvailabilityFiles = $skillBean->$cc_skill_cc_questions->getBeans();

            foreach($skillAvailabilityFiles as $key => $item){

                $beamCodeObject = null;
                if(!empty($skillAvailabilityFiles[$key]->cc_tq_code_type_id_c)){
                    $beamCodeType = BeanFactory::getBean('CC_TQ_Code_Type',$skillAvailabilityFiles[$key]->cc_tq_code_type_id_c);
                    $beamCodeObject = (object) [
                        'id' => $beamCodeType->external_id,
                        'name'=> $beamCodeType->name,
                        'file_extension' => $beamCodeType->extension
                    ];
                }

                $answer_values = [];
                $answer_options = [];
                if($skillAvailabilityFiles[$key]->type==='CustomOptions'){
                    if(key_exists($skillAvailabilityFiles[$key]->answer_options,$app_list_strings)){
                        $answer_values = $app_list_strings[$skillAvailabilityFiles[$key]->answer_options];
                        foreach ($answer_values as $ans => $label){
                            $answer_options[] = (object)[
                                'value' => $ans, 'label' => $label
                            ];
                        }
                    }
                }

                $results[] = (object) [
                    'id'            => $skillAvailabilityFiles[$key]->id,
                    'name'          => $skillAvailabilityFiles[$key]->name,
                    'date_entered'  => $skillAvailabilityFiles[$key]->date_entered,
                    'date_modified' => $skillAvailabilityFiles[$key]->date_modified,
                    'description'   => htmlspecialchars_decode($skillAvailabilityFiles[$key]->description),
                    'category'      => $skillAvailabilityFiles[$key]->category,
                    'type'          => $skillAvailabilityFiles[$key]->type,
                    'code_base'     => $beamCodeObject,
                    'options'       => $answer_options
                ];
            }
        }

        return $results;

    }

    public function linkingRatingAndYears($skills) {
        $provitionalSkill = [];
        $leakedSkills = [];
        $account = 0;

        foreach ($skills as $skill) {

            $account += 1;
            $sameIds = $provitionalSkill["Skill"]["Id"] == $skill["Skill"]["Id"];
            if (count($provitionalSkill) != 0) {
                if ($sameIds) {
                    if($skill["Type"] == 'rating') {
                        $skill["years"] = $provitionalSkill["Amount"];
                        $skill["rating"] = $skill["Amount"];
                    }else {
                        $skill["years"] = $skill["Amount"];
                        $skill["rating"] = $provitionalSkill["Amount"];
                    }
                    $leakedSkills[] = $skill;
                }
                elseif (!in_array($provitionalSkill, $leakedSkills)) {
                    self::putRatinYear($provitionalSkill);
                    $leakedSkills[] = $provitionalSkill;
                }
            }
            
            if ($account == count($skills) && !in_array($skill, $leakedSkills)) {
                self::putRatinYear($skill);
                $leakedSkills[] = $skill;
            }
            if(!$sameIds) {
                self::putRatinYear($skill);
            }
            $provitionalSkill = $skill;
        }
        return $leakedSkills;
    }

    private function putRatinYear(&$principal){
        if($principal["Type"] == 'rating') {
            $principal["rating"] = $principal["Amount"];
            $principal["years"] = 0;
        }else {
            $principal["years"] = $principal["Amount"];
            $principal["rating"] = 0;
        }
    }

}
