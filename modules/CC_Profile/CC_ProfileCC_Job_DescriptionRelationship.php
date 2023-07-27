<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point'); 
}

class CC_ProfileCC_Job_DescriptionRelationship extends SugarBean
{
    // Stored fields
    var $id;
    var $cc_job_description_cc_profilecc_profile_idb;
    var $dependency;
    var $cc_job_description_cc_profilecc_job_description_ida;

    // Related fields
    var $cc_profile_name;
    var $cc_job_description_name;

    var $table_name = "cc_job_description_cc_profile_c";
    var $object_name = "CC_ProfileCC_Job_DescriptionRelationship";
    var $column_fields = Array(
    "id",
    "cc_job_description_cc_profilecc_profile_idb" ,
    "cc_job_description_cc_profilecc_job_description_ida" ,
    "dependency" ,
    'date_modified'
    );
    
    var $field_defs = array (
        'id'=>array(
        'name'=>'id',
        'type'=>'char',
        'len'=>'36',
        'default'=>''),
        'cc_job_description_cc_profilecc_profile_idb' => array(
        'name'=>'cc_job_description_cc_profilecc_profile_idb',
        'type'=>'char',
        'len'=>'36',),
        'cc_job_description_cc_profilecc_job_description_ida' => array(
        'name'=>'cc_job_description_cc_profilecc_job_description_ida',
        'type'=>'char',
        'len'=>'36',),
        'dependency'=>array(
        'name'=>'dependency',
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
    public function CC_ProfileCC_Job_DescriptionRelationship()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    public function fill_in_additional_detail_fields()
    {
        global $locale;
        if (isset($this->cc_job_description_cc_profilecc_profile_idb) && $this->cc_job_description_cc_profilecc_profile_idb != "") {
            $query = "SELECT id, name from cc_profile where id='".$this->cc_job_description_cc_profilecc_profile_idb."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if ($row != null) {
                $this->cc_profile_name = $row["name"];
            }
        }

        if (isset($this->cc_job_description_cc_profilecc_job_description_ida) && $this->cc_job_description_cc_profilecc_job_description_ida != "") {
            $query = "SELECT name from cc_job_description where id='$this->cc_job_description_cc_profilecc_job_description_ida' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if ($row != null) {
                $this->cc_job_description_name = $row['name'];
            }
        }
    }
}
