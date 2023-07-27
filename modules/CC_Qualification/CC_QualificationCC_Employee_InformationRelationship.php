<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

//
class CC_QualificationCC_Employee_InformationRelationship extends SugarBean {
    // Stored fields
    var $id;
    var $cc_employee_information_cc_qualificationcc_qualification_idb;
    var $actual_qualification;
    var $has_digital_support;
    var $deleted;
    var $cc_employef198rmation_ida;

    // Related fields
    var $CC_Qualification_name;
    var $cc_employee_information_name;

    var $table_name = "cc_employee_information_cc_qualification_c";
    var $object_name = "CC_QualificationCC_Employee_InformationRelationship";
    var $column_fields = Array(
        "id",
        "cc_employee_information_cc_qualificationcc_qualification_idb" ,
        "cc_employef198rmation_ida" ,
        "actual_qualification" ,
        "has_digital_support" ,
        'date_modified'
    );

    var $field_defs = array (
        'id'=>array(
            'name' =>'id',
            'type' =>'char',
            'len'=>'36',
            'default'=>''),
        'cc_employee_information_cc_qualificationcc_qualification_idb'=>array(
            'name' =>'cc_employee_information_cc_qualificationcc_qualification_idb',
            'type' =>'char',
            'len'=>'36', ),
        'cc_employef198rmation_ida'=>array(
            'name' =>'cc_employef198rmation_ida',
            'type' =>'char',
            'len'=>'36',),
        'actual_qualification'=>array(
            'name' =>'actual_qualification',
            'type' =>'char',
            'len'=>'100',),
        'has_digital_support'=>array(
            'name' =>'has_digital_support',
            'type' =>'bool'
        ),
        'date_modified'=>array (
            'name' => 'date_modified',
            'type' => 'datetime'),
        'deleted'=>array(
            'name' =>'deleted',
            'type' =>'bool',
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
    public function CC_QualificationCC_Employee_InformationRelationship()
    {
        self::__construct();
    }

    public function get_relation_row($employee_information, $related_qua){
        $query = "SELECT * FROM ".$this->table_name." WHERE cc_employee_information_cc_qualificationcc_qualification_idb ='".$related_qua."' AND cc_employef198rmation_ida = '".$employee_information."' AND deleted = 0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_employef198rmation_ida = $row['cc_employef198rmation_ida'];
            $this->cc_employee_information_cc_qualificationcc_qualification_idb = $row['cc_employee_information_cc_qualificationcc_qualification_idb'];
            $this->actual_qualification = $row['actual_qualification'];
            $this->has_digital_support = $row['has_digital_support'];
            $this->deleted = $row['deleted'];
            return $this;
        }
        return false;
    }


    public function fill_in_additional_detail_fields()
    {
        global $locale;
        if (isset($this->cc_employee_information_cc_qualificationcc_qualification_idb) && $this->cc_employee_information_cc_qualificationcc_qualification_idb != "") {
            $query = "SELECT * from cc_qualification where id='".$this->cc_employee_information_cc_qualificationcc_qualification_idb."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if ($row != null) {
                $this->CC_Qualification_name = $row['name'];
            }
        }

        if (isset($this->cc_employef198rmation_ida) && $this->cc_employef198rmation_ida != "") {
            $query = "SELECT name from cc_employee_information where id='{$this->cc_employef198rmation_ida}' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            if ($row != null) {
                $this->CC_Employee_Information_name = $row['name'];
            }
        }
    }
}