<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';

use Api\V8\Config\Common as Common;
use Api\V8\Utilities;

class CC_QualificationController extends SugarController{
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_QualificationController()
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
   *
   * @param array $arrIds
   * @return array
   */
  public static function getRecordsByIds(array $arrIds) {

    $sql = "SELECT q.id 'Id', q.name 'Name', q.mininum_requiered 'MinimumRequired', q.digital_support_required 'DigitalSupportRequired', q.description 'Description', q1.name 'Parent_Name'
      FROM ".Common::$quaTable." q
        LEFT JOIN ".Common::$quaRelationTable." qq ON qq.".Common::$quaRelationFieldB." = q.id AND qq.deleted = 0
        LEFT JOIN ".Common::$quaTable." q1 ON q1.id = qq.".Common::$quaRelationFieldA." AND q1.deleted = 0
      WHERE q.deleted = 0";
    
    if (!empty($arrIds))  {
      $sql .= " AND q.id IN ('".implode("', '", $arrIds)."')";
    }

    $sql .= " ORDER BY q.name";

    // Get an instance of the dabatabase manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $rows = $db->query($sql);
    
    // Initialize an array with the results
    $result = [];

    // Fetch the row
    while ($row = $db->fetchRow($rows)) {
      $row['DigitalSupportRequired'] = $row['DigitalSupportRequired'] == '1';
      $result[] = $row;
    }
    return $result;
  }

    /**
     *
     * @param string $profile
     * @param boolean $onlyFavourites
     * @return array
     */
    public static function getRecordsByProfileId(string $profileId,$onlyFavourites = true) {

        // in some modules more than one profile is sent
        $varientOfNumberProfile = strpos($profileId, ',');
        if($varientOfNumberProfile === false){
            $idProfiles = " AND pqq.id = '".$profileId."' ";
        }else{
            $profileArray = explode(',', $profileId);
            $dataProfile = "";
            for ($i=0; $i < count($profileArray); $i++) {
                $dataProfile.= "'".$profileArray[$i]."',";
            }
            $dataProfile = substr($dataProfile, 0, -1);
            $idProfiles = " AND pqq.id IN ($dataProfile) ";
        }
        $strFavourites = "";
        if($onlyFavourites){
            $strFavourites  = " q.favourite = 1 and ";
        }

        $sql = "SELECT q.id 'Id', q.name 'Name', q.mininum_requiered 'MinimumRequired', q.digital_support_required 'DigitalSupportRequired', q.description 'Description'
                FROM ".Common::$pqRelationTable." pq
                  INNER JOIN ".Common::$quaTable." q ON q.id = pq.".Common::$pqFieldB." AND q.deleted = 0
                  INNER JOIN ".Common::$profileTable." pqq ON pqq.id = pq.".Common::$pqFieldA." AND pqq.deleted = 0
                WHERE ".$strFavourites." pq.deleted = 0 $idProfiles ORDER BY q.name";
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);

        // Initialize an array with the results
        $result = [];

        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
            $row['DigitalSupportRequired'] = $row['DigitalSupportRequired'] == '1';
            $result[] = $row;
        }

        return $result;
  }


  public function saveQualificationRecord(object $qualification, $qualification_id=null){
    if($qualification_id){
      //This case works when the endpoint is called from the JobApplicationEvent
      $qualificationBean = Utilities::getCustomBeanById('CC_Qualification', $qualification_id);
      if($qualificationBean){
        return $qualificationBean;
      }      
      $qualificationBean = BeanFactory::newBean('CC_Qualification');
      $qualificationBean->id= $qualification_id;
      $qualificationBean->new_with_id = $qualification_id;
    }else {
      $qualificationBean = (Utilities::getCustomBeanByName('CC_Qualification', $qualification->Name)) ? Utilities::getCustomBeanByName('CC_Qualification', $qualification->Name) : BeanFactory::newBean('CC_Qualification');
    }
      
    $qualificationBean->name = $qualification->Name;
    if(key_exists("Description",$qualification)){
        $qualificationBean->description = $qualification->Description;
    }
    $qualificationBean->mininum_requiered = $qualification->MinimumRequired;
    $qualificationBean->digital_support_required = $qualification->DigitalSupportRequired;
    $qualificationBean->save();
    return $qualificationBean;
  }

  /**
   *
   * @param string $employeeId
   * @return array
   */
  public function getRecordsByEmployeeId(string $employeeId) {

    $sql = "SELECT eiq.has_digital_support 'HasDigitalSupport', eiq.actual_qualification 'ActualQualification', eiq.".Common::$eiqRelationFieldB." 'quaId'
      FROM ".Common::$eiqRelationName." eiq
      WHERE eiq.deleted = 0 AND eiq.".Common::$eiqRelationFieldA." = '".$employeeId."'";

    // Get an instance of the dabatabase manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $rows = $db->query($sql);
    
    // Initialize an array with the results
    $result = [];

    // Fetch the row
    while ($row = $db->fetchRow($rows)) {
      $qua = $this->getRecordsByIds(explode(",", $row['quaId']));
      unset($qua[0]['Description']);
      unset($qua[0]['Parent_Name']);
      $qua[0]['Minimum_Required'] = $qua[0]['MinimumRequired'];
      $qua[0]['Digital_Support'] = $qua[0]['DigitalSupportRequired'];
      unset($qua[0]['MinimumRequired']);
      unset($qua[0]['DigitalSupportRequired']);

      $row['HasDigitalSupport'] = ($row['HasDigitalSupport'] == '1');
      $row['Qualification'] = $qua[0];
      unset($row["quaId"]);
      $result[] = $row;
    }
    return $result;
  }

  /**
   *
   * @param string $candidateId
   * @return array
   */
  public function getRecordsByCandidateId(string $candidateId) {

    $sql = "SELECT cq.has_digital_support 'HasDigitalSupport', cq.actual_qualification 'ActualQualification', cq.".Common::$cqRelationFieldB." 'quaId'
      FROM ".Common::$cqRelationName." cq
      WHERE cq.deleted = 0 AND cq.".Common::$cqRelationFieldA." = '".$candidateId."'";

    // Get an instance of the dabatabase manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $rows = $db->query($sql);
    
    // Initialize an array with the results
    $result = [];

    // Fetch the row
    while ($row = $db->fetchRow($rows)) {
      $qua = $this->getRecordsByIds(explode(",", $row['quaId']));
      unset($qua[0]['Description']);
      unset($qua[0]['Parent_Name']);
      $qua[0]['Minimum_Required'] = $qua[0]['MinimumRequired'];
      $qua[0]['Digital_Support'] = $qua[0]['DigitalSupportRequired'];
      unset($qua[0]['MinimumRequired']);
      unset($qua[0]['DigitalSupportRequired']);

      $row['HasDigitalSupport'] = ($row['HasDigitalSupport'] == '1');
      $row['Qualification'] = $qua[0];
      unset($row["quaId"]);
      $result[] = $row;
    }
    return $result;
  }

    public function searchBy($stringNeedle, $fieldsFilter, $sort, $order, $offset, $limit){

        $queryBuilder = new CareersQueryBuilder(Common::$quaTable, $fieldsFilter);
        $queryBuilder->withLimitOffset($limit,$offset);
        $queryBuilder->withSearch($stringNeedle);
        $queryBuilder->withSort($sort);
        $queryBuilder->withOrder($order);

        $sql = $queryBuilder->getSQL();

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            if(key_exists("digital_support_required",$row)){
                $row["digital_support_required"] = $row["digital_support_required"]=="1";
            }
            if(key_exists("published",$row)){
                $row["published"] = $row["published"]=="1";
            }

            $result[] = $row;
        }
        return $result;
    }

    public function getEmployeeRelatedRecords($skillId, $sort, $order, $offset, $limit){
        $sqlOffset = ($limit && $offset)?", $offset":"";
        $sqlLimit = ($limit)? "LIMIT $limit": "";

        $sql = "SELECT e.id, e.name, e.active, e.is_assigned, es.actual_qualification, es.has_digital_support  
            FROM ".Common::$employee_qualification." es 
            INNER JOIN ".Common::$quaTable." AS qualification ON (es.cc_employee_information_cc_qualificationcc_qualification_idb = qualification.id)
            INNER JOIN ".Common::$employee." e ON (e.id = es.cc_employef198rmation_ida)
            WHERE qualification.id = '$skillId' AND qualification.deleted = 0 AND es.deleted = 0 ORDER BY $sort $order $sqlLimit $sqlOffset ";

        $db = DBManagerFactory::getInstance();
        $rows = $db->query($sql);
        $result = [];
        while ($row = $db->fetchRow($rows)) {
            $row['active'] = ($row['active']=="1");
            $row['is_assigned'] = ($row['is_assigned']=="1");
            $row['has_digital_support'] = ($row['has_digital_support']=="1");
            $result[] = $row;
        }
        return $result;
    }

    public function searchByTerm($term, $includeAll=true)
    {
      $justFavourites = "";
      if(!$includeAll){
          $justFavourites = " and favourite = 1";
      }
      $sql = "SELECT * from ".Common::$quaTable." where deleted = 0 AND  name LIKE '%$term%'".$justFavourites;
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
}