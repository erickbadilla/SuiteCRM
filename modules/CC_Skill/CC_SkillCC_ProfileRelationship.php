<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


// Contact is used to store customer information.
class CC_SkillCC_ProfileRelationship extends SugarBean
{
    // Stored fields
    public $id;
    public $type;
    public $amount;
    public $rating;
    public $years;
    public $deleted;
    public $date_modified;
    public $cc_profile_cc_skillcc_profile_ida;
    public $cc_profile_cc_skillcc_skill_idb;

    // Related fields
    public $cc_skill_name;
    public $cc_profile_name;
    public $table_name = "cc_profile_cc_skill_c";
    public $object_name = "CC_SkillCC_ProfileRelationship";
    public $column_fields = Array(
        "id",
        "cc_profile_cc_skillcc_skill_idb" ,
        "cc_profile_cc_skillcc_profile_ida" ,
        "type" ,
        "amount",
        "rating",
        "years",
        'date_modified'
    );

    public $field_defs = array(
        'id' => array(
            'name' => 'id',
            'type' => 'char',
            'len' => '36',
            'default' => ''),
        'cc_profile_cc_skillcc_skill_idb' => array(
            'name' => 'cc_profile_cc_skillcc_skill_idb',
            'type' => 'char',
            'len' => '36',),
        'cc_profile_cc_skillcc_profile_ida' => array(
            'name' => 'cc_profile_cc_skillcc_profile_ida',
            'type' => 'char',
            'len' => '36',),
        'type' => array(
            'name' => 'type',
            'type' => 'char',
        'len' => '100',),
        'amount' => array(
            'name' => 'amount',
            'type' => 'float',
        ),
        'years' => array(
            'name' => 'years',
            'type' => 'int',
        ),
        'rating' => array(
            'name' => 'rating',
            'type' => 'float',
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

    public function get_relation_row($profile, $related_skill) {
        $query = "SELECT * from ".$this->table_name." where cc_profile_cc_skillcc_skill_idb ='".$related_skill."' AND cc_profile_cc_skillcc_profile_ida = '".$profile."' AND deleted=0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_profile_cc_skillcc_profile_ida = $row['cc_profile_cc_skillcc_profile_ida'];
            $this->cc_profile_cc_skillcc_skill_idb = $row['cc_profile_cc_skillcc_skill_idb'];
            $this->type = $row['type'];
            $this->amount = $row['amount'];
            $this->years = $row['years'];
            $this->rating = $row['rating'];
            $this->deleted = $row['deleted'];
            $this->date_modified = $row['date_modified'];
            return $this;
        }
        return false;
    }

    public function get_related_skills($profile) {
        $query = "SELECT * from ".$this->table_name." where cc_profile_cc_qualificationcc_profile_ida = '".$profile."' AND deleted=0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $data = [];
        while ($row = $this->db->fetchByAssoc($result)) {
            $item = new CC_SkillCC_ProfileRelationship();
            $item->id = $row['id'];
            $item->cc_profile_cc_skillcc_profile_ida = $row['cc_profile_cc_skillcc_profile_ida'];
            $item->cc_profile_cc_skillcc_skill_idb = $row['cc_profile_cc_skillcc_skill_idb'];
            $item->type = $row['type'];
            $item->amount = $row['amount'];
            $item->years = $row['years'];
            $item->rating = $row['rating'];
            $item->deleted = $row['deleted'];
            $item->date_modified = $row['date_modified'];
            $data[] = $item;
        }
        return $data;
    }

    public static function remove_related_skills($profile) {
        $db = DBManagerFactory::getInstance();
        $query = "UPDATE cc_profile_cc_skill_c SET deleted = 1 WHERE cc_profile_cc_skillcc_profile_ida = '".$profile."' AND deleted=0";
        return $db->query($query, true, " Error updating profile Skills. ");
    }

    public static function remove_related_skill($profileId, $skillId) {
        $db = DBManagerFactory::getInstance();
        $query = "UPDATE cc_profile_cc_skill_c SET deleted = 1 WHERE cc_profile_cc_skillcc_profile_ida = '".$profileId."' AND cc_profile_cc_skillcc_skill_idb = '".$skillId."' AND deleted=0";
        return $db->query($query, true, " Error deleting profile Skill. ");
    }

    public static function saveProfileSkillRecord(\SugarBean $parentBean, array $profileSkills){

        $actualRelation = 'cc_profile_cc_skill';

        $parentBean->load_relationship($actualRelation);
        $result = [];
        foreach($profileSkills as $profileSkill){
            if(key_exists("relation", $profileSkill)){
                $profileSkill->Type = ($profileSkill->relation=="Years of Experience") ? "years_of_experience" : strtolower($profileSkill->relation);
            }else if(key_exists("Type", $profileSkill)){
                $profileSkill->Type = ($profileSkill->Type=="Years of Experience") ? "years_of_experience" : strtolower($profileSkill->Type);
            }

            $resultItem=[];
            if(property_exists($profileSkill,"Skill")){
                $skillBean = (new CC_SkillController)->saveSkillRecord($profileSkill->Skill);
            } else {
                $skillBean = (new CC_SkillController)->saveSkillRecord($profileSkill);
            }

            $parentBean->$actualRelation->add($skillBean);

            $skill = (new CC_SkillController)->getRecordsByIds(explode(",", $skillBean->id));

            $resultItem['Skill']=$skill[0];

            $rSkillBean = new CC_SkillCC_ProfileRelationship();

            $relatedRow = $rSkillBean->get_relation_row($parentBean->id, $skillBean->id);
            if ($relatedRow) {
                if(
                    strtolower($profileSkill->Type)=='years_of_experience' ||
                    strtolower($profileSkill->relation)=='years of experience' //Case in which the request is made from the EndPoint
                ){
                    $resultItem['Type']='years_of_experience';
                    $relatedRow->years = $profileSkill->Amount;
                }
                if(
                    strtolower($profileSkill->Type)=='rating' ||
                    strtolower($profileSkill->relation)=='rating' //Case in which the request is made from the EndPoint
                ){
                    $relatedRow->rating = $profileSkill->Amount;
                    if($profileSkill->Amount>5){
                        $relatedRow->rating = 5;
                    }
                    if($profileSkill->Amount<0){
                        $relatedRow->rating = 0;
                    }
                }
                $rSave = $relatedRow->save();
                if($rSave){
                    $resultItem['Type']=$profileSkill->Type;
                    $resultItem['Amount']=$profileSkill->Amount;
                    $result[] = $resultItem;
                }
            }
        }

        return $result;
    }

    public function fill_in_additional_detail_fields()
    {
        global $locale;
        if (isset($this->cc_profile_cc_skillcc_skill_idb) && $this->cc_profile_cc_skillcc_skill_idb != "") {
            $query = "SELECT name, skill_type from cc_skill where id='".$this->cc_profile_cc_skillcc_skill_idb."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            
            if ($row != null) {
            $this->cc_skill_name = $locale->getLocaleFormattedName($row['name'], $row['skill_type']);
            }
        }

        if (isset($this->cc_profile_cc_skillcc_profile_ida) && $this->cc_profile_cc_skillcc_profile_ida != "") {
            $query = "SELECT name from cc_profile where id='$this->cc_profile_cc_skillcc_profile_ida' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            if ($row != null) {
                $this->cc_profile_name = $row['name'];
            }
        }
    }
}
