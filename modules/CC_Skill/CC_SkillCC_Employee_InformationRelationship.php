<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


// Contact is used to store customer information.
class CC_SkillCC_Employee_InformationRelationship extends SugarBean
{
    // Stored fields
    var $id;
    var $cc_employee_information_cc_skillcc_skill_idb;
    var $amount;
    var $rating;
    var $years;
    var $type;
    var $deleted;
    var $date_modified;
    var $modified_user_id;
    var $cc_employee_information_cc_skillcc_employee_information_ida;

    // Related fields
    var $cc_skill_name;
    var $cc_employee_information_name;

    var $table_name = "cc_employee_information_cc_skill_c";
    var $object_name = "CC_SkillCC_Employee_InformationRelationship";
    var $column_fields = Array(
    "id",
    "cc_employee_information_cc_skillcc_skill_idb" ,
    "cc_employee_information_cc_skillcc_employee_information_ida" ,
    "amount",
    "rating",
    "years",
    'date_modified',
    'modified_user_id'
    );

    var $field_defs = array(
        'id' => array(
            'name' => 'id',
            'type' => 'char',
            'len' => '36',
            'default' => ''),
        'cc_employee_information_cc_skillcc_skill_idb' => array(
            'name' => 'cc_employee_information_cc_skillcc_skill_idb',
            'type' => 'char',
            'len' => '36',),
        'cc_employee_information_cc_skillcc_employee_information_ida' => array(
            'name' => 'cc_employee_information_cc_skillcc_employee_information_ida',
            'type' => 'char',
            'len' => '36',),
        'amount' => array(
            'name' => 'amount',
            'type' => 'float',
        ),
        'type' => array(
            'name' => 'type',
            'type' => 'char',
        ),
        'years' => array(
            'name' => 'years',
            'type' => 'int',
        ),
        'rating' => array(
            'name' => 'rating',
            'type' => 'float',
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'type' => 'char',
            'len' => '36',
        ),
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

        $this->disable_row_level_security =true;
    }

    public function get_relation_row($employee_information, $related_skill) {
        $query = "SELECT * from ".$this->table_name." where cc_employee_information_cc_skillcc_skill_idb ='".$related_skill."' AND cc_employee_information_cc_skillcc_employee_information_ida = '".$employee_information."' AND deleted=0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_employee_information_cc_skillcc_employee_information_ida = $row['cc_employee_information_cc_skillcc_employee_information_ida'];
            $this->cc_employee_information_cc_skillcc_skill_idb = $row['cc_employee_information_cc_skillcc_skill_idb'];
            $this->type = $row['type'];
            $this->years = $row['years'];
            $this->rating = $row['rating'];
            $this->deleted = $row['deleted'];
            $this->modified_user_id = $row['modified_user_id'];
            return $this;
        }
        return false;
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_SkillCC_Employee_InformationRelationship()
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
        if (isset($this->cc_employee_information_cc_skillcc_skill_idb) && $this->cc_employee_information_cc_skillcc_skill_idb != "") {
            $query = "SELECT name, skill_type from cc_skill where id='".$this->cc_employee_information_cc_skillcc_skill_idb."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            
            if ($row != null) {
            $this->cc_skill_name = $locale->getLocaleFormattedName($row['name'], $row['skill_type']);
            }
        }

        if (isset($this->cc_employee_information_cc_skillcc_employee_information_ida) && $this->cc_employee_information_cc_skillcc_employee_information_ida != "") {
            $query = "SELECT name from cc_employee_information where id='$this->cc_employee_information_cc_skillcc_employee_information_ida' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            if ($row != null) {
                $this->cc_employee_information_name = $row['name'];
            }
        }
    }
}