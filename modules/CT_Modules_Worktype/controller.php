<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';

use Api\V8\Config\Common as Common;

class CT_Modules_WorktypeController extends SugarController{

    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CT_Modules_WorktypeController()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    public function getMatchingWorktypes(string $moduleId){
        if($moduleId == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT mw.id 'Id', mw.name 'Name'
        FROM ".Common::$mwpmRelationName." mwpm
          INNER JOIN ".Common::$pmTable." pm ON pm.id = mwpm.".Common::$mwpmRelationFieldA." AND pm.deleted = 0
          INNER JOIN ".Common::$mwTable." mw ON mw.id = mwpm.".Common::$mwpmRelationFieldB." AND mw.deleted = 0
        WHERE mwpm.deleted = 0 AND pm.id = '".$moduleId."'
        ORDER BY mw.name";

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

    public function getModuleWorktype(string $worktypeId){
        if($worktypeId == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT mw.id 'Id', mw.name 'Name'
        FROM ".Common::$mwTable." mw
        WHERE mw.deleted = 0 AND mw.id = '".$worktypeId."'";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $row = $db->query($sql);
        
        $result = $db->fetchRow($row);

        return $result;
    }
}