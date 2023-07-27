<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Contact is used to store customer information.
class CC_QualificationCC_ProfileRelationship extends SugarBean
{
    // Stored fields
    public $id;
    public $deleted;
    public $date_modified;
    public $cc_profile_cc_qualificationcc_profile_ida;
    public $cc_profile_cc_qualificationcc_qualification_idb;

    // Related fields
    public $cc_qualification_name;
    public $cc_profile_name;

    public $table_name = "cc_profile_cc_qualification_c";
    public $object_name = "CC_QualificationCC_ProfileRelationship";
    public $column_fields = Array(
        "id",
        "cc_profile_cc_qualificationcc_profile_ida" ,
        "cc_profile_cc_qualificationcc_qualification_idb" ,
        "deleted",
        'date_modified'
    );

    public $field_defs = array(
        'id' => array(
            'name' => 'id',
            'type' => 'char',
            'len' => '36',
            'default' => ''),
        'cc_profile_cc_qualificationcc_qualification_idb' => array(
            'name' => 'cc_profile_cc_qualificationcc_qualification_idb',
            'type' => 'char',
            'len' => '36',),
        'cc_profile_cc_qualificationcc_profile_ida' => array(
            'name' => 'cc_profile_cc_qualificationcc_profile_ida',
            'type' => 'char',
            'len' => '36',),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime'),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true)
    );

    public function __construct() {
        parent::__construct();
        $this->db = DBManagerFactory::getInstance();
        $this->dbManager = DBManagerFactory::getInstance();

        $this->disable_row_level_security = true;
    }

    public function get_relation_row($profile, $related) {
        $query = "SELECT * from ".$this->table_name." where cc_profile_cc_qualificationcc_qualification_idb ='".$related."' AND cc_profile_cc_qualificationcc_profile_ida = '".$profile."' AND deleted=0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_profile_cc_qualificationcc_profile_ida = $row['cc_profile_cc_qualificationcc_profile_ida'];
            $this->cc_profile_cc_qualificationcc_qualification_idb = $row['cc_profile_cc_qualificationcc_qualification_idb'];
            $this->deleted = $row['deleted'];
            $this->date_modified = $row['date_modified'];
            return $this;
        }
        return false;
    }

    public function get_related_qualifications($profile) {
        $query = "SELECT * from ".$this->table_name." where cc_profile_cc_qualificationcc_profile_ida = '".$profile."' AND deleted=0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $data = [];
        while ($row = $this->db->fetchByAssoc($result)) {
            $item = new CC_QualificationCC_ProfileRelationship();
            $item->id = $row['id'];
            $item->cc_profile_cc_qualificationcc_profile_ida = $row['cc_profile_cc_qualificationcc_profile_ida'];
            $item->cc_profile_cc_qualificationcc_qualification_idb = $row['cc_profile_cc_qualificationcc_qualification_idb'];
            $item->deleted = $row['deleted'];
            $item->date_modified = $row['date_modified'];
            $data[] = $item;
        }
        return $data;
    }

    public static function remove_related_qualifications($profile) {
        $db = DBManagerFactory::getInstance();
        $query = "UPDATE cc_profile_cc_qualification_c SET deleted = 1 WHERE cc_profile_cc_qualificationcc_profile_ida = '".$profile."' AND deleted=0";
        return $db->query($query, true, " Error updating profile Qualifications. ");
    }

    public static function remove_related_qualification($idProfile, $idQualification) {
        $db = DBManagerFactory::getInstance();
        $query = "UPDATE cc_profile_cc_qualification_c SET deleted = 1 WHERE cc_profile_cc_qualificationcc_profile_ida = '".$idProfile."' AND cc_profile_cc_qualificationcc_qualification_idb = '".$idQualification."' AND deleted=0";
        return $db->query($query, true, " Error deleting profile qualification.");
    }

    public static function saveProfileQualificationRecord(\SugarBean $parentBean, array $profileQualifications, $mapToArray=null ){

        $actualRelation = 'cc_profile_cc_qualification';
        $result = [];
        foreach($profileQualifications as $profileQualification){
            $parentBean->load_relationship($actualRelation);

            $qualificationBean = (new CC_QualificationController)->saveQualificationRecord($profileQualification);

            $parentBean->$actualRelation->add($qualificationBean);

            if(!is_null($mapToArray)){
                $values = array_intersect_key($qualificationBean->toArray(),$mapToArray);
                foreach ($mapToArray as $key => $newKey){
                    $values[$newKey] = $values[$key];
                    unset($values[$key]);
                }
                $result[] = $values;
            } else {
                $result[] = $qualificationBean;
            }
        }

        return $result;

    }


    public function fill_in_additional_detail_fields()
    {
        global $locale;
        if (isset($this->cc_profile_cc_qualificationcc_qualification_idb) && $this->cc_profile_cc_qualificationcc_qualification_idb != "") {
            $query = "SELECT name from cc_qualification where id='".$this->cc_profile_cc_qualificationcc_qualification_idb."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            
            if ($row != null) {
                $this->cc_qualification_name = $locale->getLocaleFormattedName($row['name']);
            }
        }

        if (isset($this->cc_profile_cc_qualificationcc_profile_ida) && $this->cc_profile_cc_qualificationcc_profile_ida != "") {
            $query = "SELECT name from cc_profile where id='$this->cc_profile_cc_qualificationcc_profile_ida' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            if ($row != null) {
                $this->cc_profile_name = $row['name'];
            }
        }
    }
}
