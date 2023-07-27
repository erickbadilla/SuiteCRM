<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
use Api\V8\Config\Common as Common;
use Api\V8\Utilities;


class CC_Candidate_Track_LogController extends SugarController{


    private static $customModuleName = 'CC_Candidate_Track_Log';

    public function __construct() {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Candidate_Track_LogController() {
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
  * @param string $candidateId
  * @return array
  */
  public function getRecordsByCandidateId(string $candidateId) {

    $sql = "SELECT ctl.name 'Name', ctl.log_type 'LogType',  ctl.log_date 'LogDate'
      FROM ".Common::$candidateTrackLogTable." ctl
      JOIN ".Common::$ctlRelationName." rel
      ON ctl.id = rel.".Common::$ctlRelationFieldB."
      WHERE ctl.deleted = 0 
      and rel.".Common::$ctlRelationFieldA." ='".$candidateId."'
      ORDER BY Name";

    // Get an instance of the database manager
    $db = DBManagerFactory::getInstance();
    // Perform the query
    $rows = $db->query($sql);
    // Initialize an array with the results
    $result = [];
    // Fetch the row
    while ($row = $db->fetchRow($rows)) {
        $row['LogDate'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($row['LogDate']));
        $result[] = $row;
    }
    return $result;
  }

  public function saveTrackLogRecord(object $trackLog) {
    
    $trackLogBean = null;
    $logType = ucwords(strtolower($trackLog->LogType));

    if(in_array($logType, $GLOBALS['app_list_strings']['log_type_list']) && strtotime($trackLog->LogDate)){
      $trackLogBean = BeanFactory::newBean(self::$customModuleName);
      $trackLogBean->name = $trackLog->Name;
      $trackLogBean->log_type = $logType;
      $trackLogBean->log_date = gmdate('Y-m-d H:i:s', strtotime($trackLog->LogDate));

      $trackLogBean->save();
    }

    return $trackLogBean;
  }


}