<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


// Contact is used to store customer information.
class CC_Job_Applications_CC_application_stage extends SugarBean
{
    // Stored fields
    public $id;
    public $date_modified;
    public $deleted;
    public $cc_job_applications_cc_application_stagecc_job_applications_ida;
    public $cc_job_applications_cc_application_stagecc_application_stage_idb;
    public $completed;
    public $cc_application_stagecc_employee_information_last_user;
    public $data;
    public $cc_application_stage_note;

    // Related fields
    public $table_name = "cc_job_applications_cc_application_stage_c";
    public $object_name = "CC_Job_Applications_CC_application_stage";
    public $column_fields = Array(
        "id",
        "date_modified",
        "deleted",
        "cc_job_applications_cc_application_stagecc_job_applications_ida",
        "cc_job_applications_cc_application_stagecc_application_stage_idb",
        "completed",
        "cc_application_stagecc_employee_information_last_user",
        "data",
        "cc_application_stage_note",
    );

    public $field_defs = array(
        'id' => array(
            'name' => 'id',
            'type' => 'char',
            'len' => '36',
            'default' => ''),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true),
        'cc_job_applications_cc_application_stagecc_job_applications_ida' => array(
            'name' => 'cc_job_applications_cc_application_stagecc_job_applications_ida',
            'type' => 'char',
            'len' => '36',),
        'cc_job_applications_cc_application_stagecc_application_stage_idb' => array(
            'name' => 'cc_job_applications_cc_application_stagecc_application_stage_idb',
            'type' => 'char',
            'len' => '36',),
        'completed' => array(
            'name' => 'completed',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => false),
        'cc_application_stagecc_employee_information_last_user' => array(
            'name' => 'cc_application_stagecc_employee_information_last_user',
            'type' => 'char',
            'len' => '36',),
        'data' => array(
            'name' => 'data',
            'type' => 'text',
            'required' => false),
        'rating' => array(
            'name' => 'rating',
            'type' => 'float',
        ),
        'cc_application_stage_note' => array(
            'name' => 'cc_application_stage_note',
            'type' => 'char',
            'len' => '36',
        ),
        'closed_state' => array(
            'name' => 'closed_state',
            'type' => 'int',
            'len' => '2',
            'default' => '0',
            'required' => false
        ),
    );

    public function __construct() {
        parent::__construct();
        $this->db = DBManagerFactory::getInstance();
        $this->dbManager = DBManagerFactory::getInstance();
        $this->disable_row_level_security =true;
    }


    public function get_relation_row($job_application, $related_state_id) {
        $query = "SELECT * from ".$this->table_name." where cc_job_applications_cc_application_stagecc_application_stage_idb ='".$related_state_id."' AND cc_job_applications_cc_application_stagecc_job_applications_ida = '".$job_application."' AND deleted=0 AND completed = 0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_job_applications_cc_application_stagecc_job_applications_ida = $row['cc_job_applications_cc_application_stagecc_job_applications_ida'];
            $this->cc_job_applications_cc_application_stagecc_application_stage_idb = $row['cc_job_applications_cc_application_stagecc_application_stage_idb'];
            $this->completed = $row['completed'];
            $this->cc_application_stagecc_employee_information_last_user = $row['cc_application_stagecc_employee_information_last_user'];
            $this->data = $row['data'];
            $this->cc_application_stage_note = $row['cc_application_stage_note'];
            return $this;
        }
        return false;
    }

    public function getUltimateStep($type){

        $result = array();
	
        $sql = "SELECT 
                      id,name 
                FROM 
                      cc_application_stage s 
                WHERE 
                       s.deleted = 0 AND s.settings = 'CLOSED' AND s.type = '". $type ."'";


            $db = DBManagerFactory::getInstance();
            $result_total = $db->fetchOne($sql);

           $array = array();
           $array['id']   = $result_total['id'];
           $array['name']   = $result_total['name'];
           $result = $array;
    
           return $result;


    }



}
