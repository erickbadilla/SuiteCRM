<?php
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';

use Api\V8\Config\Common as Common;
use Api\V8\Utilities;
use Mockery\Undefined;

class CT_daily_status_reportController extends SugarController
{

    private $object_name = "CT_daily_status_report";

    public function __construct(){
        parent::__construct();
    }

}
