<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point'); 
}

class CC_Job_OfferCC_Employee_InformationRelationship extends SugarBean
{
    // Stored fields
    var $id;
    var $cc_job_offer_cc_employee_informationcc_job_offer_ida;
    var $type;
    var $cc_job_offer_cc_employee_informationcc_employee_information_idb;

    // Related fields
    var $cc_profile_name;
    var $cc_job_offer_name;

    var $table_name = "cc_job_offer_cc_employee_information_c";
    var $object_name = "CC_Job_OfferCC_Employee_InformationRelationship";
    var $column_fields = Array(
    "id",
    "cc_job_offer_cc_employee_informationcc_job_offer_ida" ,
    "cc_job_offer_cc_employee_informationcc_employee_information_idb" ,
    "type" ,
    'date_modified'
    );
    
    var $field_defs = array (
        'id'=>array(
        'name'=>'id',
        'type'=>'char',
        'len'=>'36',
        'default'=>''),
        'cc_job_offer_cc_employee_informationcc_job_offer_ida' => array(
        'name'=>'cc_job_offer_cc_employee_informationcc_job_offer_ida',
        'type'=>'char',
        'len'=>'36',),
        'cc_job_offer_cc_employee_informationcc_employee_information_idb' => array(
        'name'=>'cc_job_offer_cc_employee_informationcc_employee_information_idb',
        'type'=>'char',
        'len'=>'36',),
        'type'=>array(
        'name'=>'type',
        'type'=>'char',
        'len'=>'100',),
        'date_modified'=>array(
        'name'=>'date_modified',
        'type'=>'datetime'),
        'deleted'=>array(
        'name'=>'deleted',
        'type'=>'bool',
        'len'=>'1',
        'default'=>'0',
        'required'=>true)
    );    
    public function __construct()
    {
        parent::__construct();
        $this->db = DBManagerFactory::getInstance();
        $this->dbManager = DBManagerFactory::getInstance();

        $this->disable_row_level_security =true;
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Job_OfferCC_Employee_InformationRelationship()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    public function get_relation_row($jobOfferId, $employeeId) {

        $query = "SELECT * from ".$this->table_name." 
        WHERE ".$this->table_name.".cc_job_offer_cc_employee_informationcc_employee_information_idb ='".$employeeId."' 
        AND ".$this->table_name.".cc_job_offer_cc_employee_informationcc_job_offer_ida ='".$jobOfferId."' 
        AND deleted=0";
        
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_job_offer_cc_employee_informationcc_employee_information_idb = $row['cc_job_offer_cc_employee_informationcc_employee_information_idb'];
            $this->cc_job_offer_cc_employee_informationcc_job_offer_ida = $row['cc_job_offer_cc_employee_informationcc_job_offer_ida'];
            $this->type = $row['type'];
            return $this;
        }
        return false;
    }

    public static function remove_related_profile_job_application($employeeId, $JobApplicationId) {
        $db = DBManagerFactory::getInstance();
        $query = "UPDATE cc_job_offer_cc_employee_information_c SET deleted = 1 WHERE cc_job_offer_cc_employee_informationcc_job_offer_ida = '".$JobApplicationId."' AND cc_job_offer_cc_employee_informationcc_employee_information_idb = '".$employeeId."' AND deleted=0";
        return $db->query($query, true, " Error updating profile job offer. ");
    }
}
