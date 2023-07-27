<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
use Api\V8\Config\Common as Common;

require_once 'modules/CC_Profile/RatingCalculationUtility.php';

// Contact is used to store customer information.
class CC_Job_OfferCC_CandidateRelationship extends SugarBean
{
    // Stored fields
    var $id;
    var $cc_candidate_cc_job_offercc_job_offer_idb;
    var $type;
    var $stage;
    var $general_rating;
    var $skill_rating;
    var $qualification_rating;
    var $deleted;
    var $cc_candidate_cc_job_offercc_candidate_ida;

    // Related fields
    var $cc_job_offer_name;
    var $cc_candidate_name;

    var $table_name = "cc_candidate_cc_job_offer_c";
    var $object_name = "CC_Job_OfferCC_CandidateRelationship";
    var $column_fields = Array(
        "id",
        "cc_candidate_cc_job_offercc_job_offer_idb" ,
        "cc_candidate_cc_job_offercc_candidate_ida" ,
        "type",
        "stage",
        "general_rating",
        "skill_rating",
        "qualification_rating",
        'date_modified'
    );

    var $field_defs = array(
        'id' => array(
            'name' => 'id',
            'type' => 'char',
            'len' => '36',
            'default' => ''),
        'cc_candidate_cc_job_offercc_job_offer_idb' => array(
            'name' => 'cc_candidate_cc_job_offercc_job_offer_idb',
            'type' => 'char',
            'len' => '36',),
        'cc_candidate_cc_job_offercc_candidate_ida' => array(
            'name' => 'cc_candidate_cc_job_offercc_candidate_ida',
            'type' => 'char',
            'len' => '36',),
        'type' => array(
            'name' => 'type',
            'type' => 'enum',
            'options' => 'applicant_type_list',
        ),
        'stage' => array(
            'name' => 'stage',
            'type' => 'ApplicationStage',
            'options' => 'applicant_stage_list',
        ),
        'general_rating' => array(
            'name' => 'general_rating',
            'type' => 'char',
        ),
        'skill_rating' => array(
            'name' => 'skill_rating',
            'type' => 'char',
        ),
        'qualification_rating' => array(
            'name' => 'qualification_rating',
            'type' => 'char',
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

    public function get_relation_row($candidate, $related_job_offer) {
        $query = "SELECT * from ".$this->table_name." where cc_candidate_cc_job_offercc_job_offer_idb ='".$related_job_offer."' AND cc_candidate_cc_job_offercc_candidate_ida = '".$candidate."' AND deleted=0";
        $result = $this->db->query($query, true, " Error filling in additional detail fields: ");
        // Get the id and the name.
        $row = $this->db->fetchByAssoc($result);
        if ($row != null) {
            $this->id = $row['id'];
            $this->cc_candidate_cc_job_offercc_candidate_ida = $row['cc_candidate_cc_job_offercc_candidate_ida'];
            $this->cc_candidate_cc_job_offercc_job_offer_idb = $row['cc_candidate_cc_job_offercc_job_offer_idb'];
            $this->type = $row['type'];
            $this->stage = $row['stage'];
            $this->skill_rating = $row['skill_rating'];
            $this->general_rating = $row['general_rating'];
            $this->qualification_rating = $row['qualification_rating'];
            $this->deleted = $row['deleted'];
            return $this;
        }
        return false;
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Job_OfferCC_CandidateRelationship()
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
        if (isset($this->cc_candidate_cc_job_offercc_job_offer_idb) && $this->cc_candidate_cc_job_offercc_job_offer_idb != "") {
            $query = "SELECT name from cc_job_offer where id='".$this->cc_candidate_cc_job_offercc_job_offer_idb."' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);

            if ($row != null) {
                $this->cc_job_offer_name = $row['name'];
            }
        }

        if (isset($this->cc_candidate_cc_job_offercc_candidate_ida) && $this->cc_candidate_cc_job_offercc_candidate_ida != "") {
            $query = "SELECT name from cc_candidate where id='$this->cc_candidate_cc_job_offercc_candidate_ida' AND deleted=0";
            $result =$this->db->query($query, true, " Error filling in additional detail fields: ");
            // Get the id and the name.
            $row = $this->db->fetchByAssoc($result);
            if ($row != null) {
                $this->cc_candidate_name = $row['name'];
            }
        }
    }

    /**
     *
     * @param array $arrIds
     * @return array
     */
    public function getRecordsByIds(string $jobApplication_id) {

        $sql = "SELECT cjo.id 'Id', jo.name 'Name', cjo.stage 'Opportunity_Stage', 
        jo.id 'Job_Offer_Id', ca.id 'Candidate_Id', ca.name 'Candidate_Name'
        FROM ".Common::$cjRelationName." cjo 
        INNER JOIN ".Common::$joTable." jo ON jo.id = cjo.".Common::$cjRelationFieldB." AND jo.deleted = 0 
        INNER JOIN ".Common::$candidateTable." ca ON ca.id = cjo.".Common::$cjRelationFieldA." AND ca.deleted = 0 
        WHERE cjo.deleted = 0";

        if (!empty($jobApplication_id)) {
            $sql .= " AND cjo.id IN ('".$jobApplication_id."')";
        }

        $sql .= " ORDER BY jo.name";

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

    public function getCandidatesIdByJobOfferId(string $jobOfferId) {
        $sql = "SELECT cjo.cc_candidate_cc_job_offercc_candidate_ida 'id'
        FROM cc_candidate_cc_job_offer_c cjo
        WHERE cjo.deleted = 0 AND cjo.cc_candidate_cc_job_offercc_job_offer_idb = '$jobOfferId'";

        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $idRows = $db->query($sql);
        // Fetch the row
        while ($idRow = $db->fetchRow($idRows)) {
            $result[] = $idRow;
        }
        return $result;
    }

    public function updateJobOfferProfilesRating(string $jobOfferId, string $candidateId = null){

        $authenticated_user = $_SESSION['authenticated_user_id'];

        $funcCandidateMap = function($value) {
            return $value['id'] ;
        };

        $db = DBManagerFactory::getInstance();
        $candidateList = [];
        if(is_null($candidateId)){
            $candidateRowList = self::getCandidatesIdByJobOfferId($jobOfferId);
            $candidateList = array_map( $funcCandidateMap, $candidateRowList );
        } else {
            $candidateList[] = $candidateId;
        }

        $db->query('START TRANSACTION');
        foreach ( $candidateList as $currentCandidate){

            $candidateOfferRelation = new CC_Job_OfferCC_CandidateRelationship();
            $rObject = new RatingCalculationUtility();
            $ratings = $rObject->calculateJobOfferCandidateRating($currentCandidate, $jobOfferId);

            if(!$candidateOfferRelation->get_relation_row($currentCandidate, $jobOfferId)){
                $db->query('ROLLBACK');
                return false;
            }
            $bean = BeanFactory::getBean('CC_Job_Applications',$candidateOfferRelation->id);

            if(!$bean){
                $db->query('ROLLBACK');
                return false;
            }

            $bean->modified_user_id = $authenticated_user;
            $bean->skill_rating = $ratings['skills'];
            $bean->qualification_rating = $ratings['qualifications'];
            $bean->general_rating = $ratings['general'];
            $bean->best_rating = 0;
            $result = $bean->save();

            if($result){
                $candidateOfferRelation->skill_rating = $ratings['skills'];
                $candidateOfferRelation->qualification_rating = $ratings['qualifications'];
                $candidateOfferRelation->general_rating = $ratings['general'];
                $candidateOfferRelation->modified_user_id = $authenticated_user;
                $relationResult = $candidateOfferRelation->save();
                if(!$relationResult){
                    $db->query('ROLLBACK');
                    return false;
                }
            }
        }

        $db->query('COMMIT');
        return true;
    }

}
