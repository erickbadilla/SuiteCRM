<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
use Api\V8\Config\Common as Common;
use Api\V8\Utilities;

class CC_Job_DescriptionController extends SugarController{

    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Job_DescriptionController()
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
  public function getRecordsByIds(array $arrIds) {

    $sql = "SELECT jd.relate_role 'Role', jd.objectives 'Objectives', jd.name 'Name', jd.id 'Id'
      FROM ".Common::$jdTable." jd
      WHERE jd.deleted = 0";
    
    if (!empty($arrIds))  {
      $sql .= " AND jd.id IN ('".implode("', '", $arrIds)."')";
    }

    $sql .= " ORDER BY jd.name";

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

}