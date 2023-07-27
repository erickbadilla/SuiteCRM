<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/CC_Profile/controller.php';

use Api\V8\Config\Common as Common;

class CT_Project_ModulesController extends SugarController{

    private $careers_default_user = 'ca4ee4s0-cccc-4000-cccc-b352d6b25516';

    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CT_Project_ModulesController()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    public function getMatchingModules(string $projectId){
        if($projectId == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT pm.id 'Id', pm.name 'Name'
        FROM ".Common::$pmprRelationName." pmpr
          INNER JOIN ".Common::$prTable." pr ON pr.id = pmpr.".Common::$pmprRelationFieldA." AND pr.deleted = 0
          INNER JOIN ".Common::$pmTable." pm ON pm.id = pmpr.".Common::$pmprRelationFieldB." AND pm.deleted = 0
        WHERE pmpr.deleted = 0 AND pr.id = '".$projectId."'
        ORDER BY pm.name";

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

    public function getProjectModule(string $moduleId){
        if($moduleId == null) throw new \InvalidArgumentException('Id can not be null');

        $sql = "SELECT pm.id 'Id', pm.name 'Name'
        FROM ".Common::$pmTable." pm
        WHERE pm.deleted = 0 AND pm.id = '".$moduleId."'";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $row = $db->query($sql);
        
        $result = $db->fetchRow($row);

        return $result;
    }
}