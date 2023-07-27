<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point'); 
}

class CC_Job_OfferCC_ProfileRelationship extends SugarBean
{
    // Stored fields
    var $id;
    var $cc_profile_cc_job_offercc_job_offer_idb;
    var $dependency;
    var $cc_profile_cc_job_offercc_profile_ida;

    // Related fields
    var $cc_profile_name;
    var $cc_job_offer_name;

    var $table_name = "cc_profile_cc_job_offer_c";
    var $object_name = "CC_Job_OfferCC_ProfileRelationship";
    var $column_fields = Array(
    "id",
    "cc_profile_cc_job_offercc_job_offer_idb" ,
    "cc_profile_cc_job_offercc_profile_ida" ,
    "dependency" ,
    'date_modified'
    );
    
    var $field_defs = array (
        'id'=>array(
        'name'=>'id',
        'type'=>'char',
        'len'=>'36',
        'default'=>''),
        'cc_profile_cc_job_offercc_job_offer_idb' => array(
        'name'=>'cc_profile_cc_job_offercc_job_offer_idb',
        'type'=>'char',
        'len'=>'36',),
        'cc_profile_cc_job_offercc_profile_ida' => array(
        'name'=>'cc_profile_cc_job_offercc_profile_ida',
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
    public function CC_Job_OfferCC_ProfileRelationship()
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
        if (isset($this->cc_profile_cc_job_offercc_profile_ida) && $this->cc_profile_cc_job_offercc_profile_ida != "") {
            $query = "SELECT id, name from cc_profile where id='".$this->cc_profile_cc_job_offercc_profile_ida."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            
            if ($row != null) {
                $this->cc_profile_name = $row['name'];
            }
        }

        if (isset($this->cc_profile_cc_job_offercc_job_offer_idb) && $this->cc_profile_cc_job_offercc_job_offer_idb != "") {
            $query = "SELECT name from cc_job_offer where id='$this->cc_profile_cc_job_offercc_job_offer_idb' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if ($row != null) {
                $this->cc_job_offer_name = $row['name'];
            }
        }
    }

    public function get_relation_row($jobOfferId, $profileId) {

        $query = "SELECT * from ".$this->table_name." 
        WHERE ".$this->table_name.".cc_profile_cc_job_offercc_profile_ida ='".$profileId."' 
        AND ".$this->table_name.".cc_profile_cc_job_offercc_job_offer_idb ='".$jobOfferId."' 
        AND deleted=0";
        
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_profile_cc_job_offercc_profile_ida = $row['cc_profile_cc_job_offercc_profile_ida'];
            $this->cc_profile_cc_job_offercc_job_offer_idb = $row['cc_profile_cc_job_offercc_job_offer_idb'];
            $this->dependency = $row['dependency'];
            return $this;
        }
        return false;
    }

    public static function remove_related_profile_job_application($idProfile, $JobApplicationId) {
        $db = DBManagerFactory::getInstance();
        $query = "UPDATE cc_profile_cc_job_offer_c SET deleted = 1 WHERE cc_profile_cc_job_offercc_job_offer_idb = '".$JobApplicationId."' AND cc_profile_cc_job_offercc_profile_ida = '".$idProfile."' AND deleted=0";
        return $db->query($query, true, " Error updating profile job offer. ");
    }
}
