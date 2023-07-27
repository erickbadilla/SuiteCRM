<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'custom/application/Ext/Api/V8/utilities.php';
use Api\V8\Config\Common as Common;
use Api\V8\Utilities as Utilities;


class CC_Personality_TestController extends SugarController{


    private static $customModuleName = 'CC_Personality_Test';

    private static $keys = [
        "pattern",
        "score_index",
        "date_entered",
        "date_modified",
        "modified_user_id",
        "modify_by_user",
        "name",
        "id"
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getRecordsByIds(string $arrIds) {

        $sql = "SELECT date_entered 'CreatedDate', id 'Id',  pattern 'Pattern',  score_index 'Score' FROM ".Common::$candidatepersonalitytestTable." WHERE deleted = 0";

        if (!empty($arrIds))  {
            $sql .= " AND id IN ('".$arrIds."')";
        }

        $sql .= " ORDER BY pattern";

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
     * @param $personalityTest
     * @param SugarBean $relatedBeanData
     * @return bool|SugarBean
     */
    private function saveJsonEntity($personalityTest, SugarBean $relatedBeanData){
        global $current_user;
        $personalityTest = Utilities::keysToLower($personalityTest);
        $actual_id = (key_exists("id", $personalityTest)) ? $personalityTest->id : false;
        if($actual_id){
            $personalityTestBean = BeanFactory::getBean(self::$customModuleName,$actual_id);
            if(!$personalityTestBean){
                $personalityTestBean = BeanFactory::getBean(self::$customModuleName);
                $personalityTestBean->new_with_id=true;
                $personalityTestBean->id = $actual_id;
            }
        } else {
            $personalityTestBean = BeanFactory::getBean(self::$customModuleName);
        }
        $pattern = ucwords(strtolower($personalityTest->pattern));
        $personalityTestBean->name = (key_exists("name", $personalityTest)) ? $personalityTest->name : $relatedBeanData->name ." - ". $pattern;
        $personalityTestBean->pattern = strtolower(str_replace(" ","-",$pattern));
        $personalityTestBean->score_index = $personalityTest->score;
        $personalityTestBean->modified_user_id=$current_user->id;
        $personalityTestBean->modified_by_name=$current_user->name;
        $personalityTestBean->save();
        return $personalityTestBean;
    }


    /**
     * @param string $relatedBean
     * @param string $related_id
     * @param $personalityTests
     * @return array
     * @throws Exception
     */
    public function saveRelatedPersonalityTest(string $relatedBean, string $related_id, $personalityTests): array
    {
        $result = [];
        $relatedRelation = strtolower($relatedBean)."_".strtolower(self::$customModuleName);

        $relatedBeanData = BeanFactory::getBean($relatedBean,$related_id);
        if(!$relatedBeanData){
            throw new Exception("Unable to load related bean");
        }

        if(is_array($personalityTests)){
            foreach ($personalityTests as $personalityTest) {
                $personalityTestBean = $this->saveJsonEntity($personalityTest,$relatedBeanData);
                if($personalityTestBean instanceof SugarBean){
                    $personalityTestBean->load_relationship($relatedRelation);
                    $personalityTestBean->$relatedRelation->add($related_id);
                    $result[] = $personalityTestBean->id;
                }
            }
        } else {
            $personalityTestBean = $this->saveJsonEntity($personalityTests,$relatedBeanData);
            if($personalityTestBean instanceof SugarBean){
                $personalityTestBean->load_relationship($relatedRelation);
                $personalityTestBean->$relatedRelation->add($related_id);
                $result[] = $personalityTestBean->id;
            }
        }
        return $result;
    }

    /**
     * @param string $relatedBeanName
     * @param string $related_id
     * @return array
     * @throws Exception
     */
    public function getRecordsByRelatedId(string $relatedBeanName, string $related_id): array
    {
        $relatedBean = BeanFactory::getBean($relatedBeanName,$related_id);
        $relatedRelationName = strtolower($relatedBeanName)."_".strtolower(self::$customModuleName);
        $relatedBean->load_relationship($relatedRelationName);
        return $relatedBean->get_linked_beans( $relatedRelationName, $relatedBean, array(), 0, -1, 0,  " 1=1 ");
    }

    /**
     *
     * @param string $candidateId
     * @return array
     */
    public function getRecordsByCandidateId(string $candidateId) {

        if(is_null($candidateId)){
            return [];
        }
        $rows = $this->getRecordsByRelatedId("CC_Candidate", $candidateId);
        $result = [];
        foreach ($rows as $row){
            $result[] = array_intersect_key($row->toArray(), array_flip(self::$keys));
        }

        return $result;
    }

    public function saveCandidatePersonalityRecord(object $personalityTest, $personalityTestId=null) {

        $personalityTestBean = null;
        $pattern = ucwords(strtolower($personalityTest->Pattern));
        
        if(in_array($pattern, $GLOBALS['app_list_strings']['Pattern_list']) && is_numeric($personalityTest->Score)){
            $personalityTestBean = BeanFactory::getBean(self::$customModuleName, trim ($personalityTest->Id));
            if($personalityTestBean) return $personalityTestBean;

            $personalityTestBean = BeanFactory::newBean(self::$customModuleName);
            if ($personalityTestId){
                $personalityTestBean->id= $personalityTestId;
                $personalityTestBean->new_with_id = $personalityTestId;
            }
            $personalityTestBean->pattern = strtolower(str_replace(" ","-",$pattern));
            $personalityTestBean->score_index = $personalityTest->Score;
            $personalityTestBean->save();

            return $personalityTestBean;
        }
        throw new \InvalidArgumentException('Personality Test invalid');
    }

    /**
     * @param string $personalityTestId
     * @param array $personalityTest
     * @return array
     * @throws InvalidArgumentException
     */
    public function updateRecord(string $personalityTestId, array $personalityTest): array
    {
        global $current_user;
        $result = [];
        if(is_array($personalityTest) && count($personalityTest)==1){
            $personalityTest = Utilities::keysToLower($personalityTest[0]);
        } else {
            throw new \InvalidArgumentException('Personality Test Update should have just an element count');
        }

        $personalitytestBean = BeanFactory::getBean(self::$customModuleName, $personalityTestId);
        $pattern = ucwords(strtolower($personalityTest->pattern));
        if (!$personalitytestBean) {
            throw new \InvalidArgumentException('Personality Test not found');
        }

        if(in_array($pattern, $GLOBALS['app_list_strings']['Pattern_list']) && is_numeric($personalityTest->score)){
            $personalitytestBean->pattern = strtolower(str_replace(" ","-",$pattern));
            $personalitytestBean->score_index = $personalityTest->score;
            $personalitytestBean->modified_user_id=$current_user->id;
            $personalitytestBean->save();

            $result = array_intersect_key($personalitytestBean->toArray(), array_flip(self::$keys));
        }


        return $result;
    }
}