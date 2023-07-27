<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

global $current_user;
require_once "modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php";
require_once "modules/CC_application_stage/StageHandler.php";
require_once 'modules/CC_Profile/RatingCalculationUtility.php';

class CC_Job_Applications extends Basic
{
    public $new_schema = true;
    public $module_dir = 'CC_Job_Applications';
    public $object_name = 'CC_Job_Applications';
    public $table_name = 'cc_job_applications';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $relation;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function getCandidateRelatedAvailabilityInfo($candidateId = null)
    {
        $errorMessage = "Unable to relate a candidate";

        $candidateMap = function ($value): object {
            $stringDay = (jddayofweek($value->daypick, CAL_DOW_SHORT)) ? jddayofweek($value->daypick, CAL_DOW_SHORT) : $value->daypick;
            return (object)[
                'day' => $stringDay,
                'daypick' => $value->daypick,
                'time_1' => $value->time_1,
                'time_2' => $value->time_2
            ];
        };

        if (is_null($candidateId)) {
            $jobOfferCandidateRelation = new CC_Job_OfferCC_CandidateRelationship();
            $relationRow = $jobOfferCandidateRelation->getRecordsByIds($this->id);
            if (count($relationRow)) {
                $candidateId = $relationRow[0]['Candidate_Id'];
            } else {
                throw new Exception($errorMessage);
            }
        }
        $candidateBean = BeanFactory::getBean('CC_Candidate', $candidateId);
        if (!$candidateBean) {
            throw new Exception($errorMessage);
        }
        $candidateBean->load_relationship('cc_candidate_availability_cc_candidate');
        $candidateAvailability = $candidateBean->cc_candidate_availability_cc_candidate->getBeans();
        $map = array_map($candidateMap, $candidateAvailability);
        return $map;
    }

    /**
     * @throws Exception
     */
    public function getJobOfferRelatedInterviewersInfo($jobOfferId = null)
    {
        $errorMessage = "Unable to relate a Job Offer Interviewer";

        $interviewerMap = function ($value): object {

            $current_email = "";
            $country = "";
            $employeeBean = BeanFactory::getBean('CC_Employee_Information', $value->cc_intervi9533rmation_ida);
            if($employeeBean){
                $current_email = $employeeBean->current_email;
                $country = $employeeBean->country_law;
            }
            return (object)[
                'job_offer_name' => $value->cc_interviewer_cc_job_offer_name,
                'employee_information_name' => $value->cc_interviewer_cc_employee_information_name,
                'employee_information_id' => $value->cc_intervi9533rmation_ida,
                'employee_information_current_email' => $current_email,
                'employee_information_country_law' => $country,
                'interviewer_name' => $value->name,
                'interviewer_id' => $value->id,
                'interviewer_role' => $value->interviewer_role,
            ];
        };

        if (is_null($jobOfferId)) {
            $jobOfferCandidateRelation = new CC_Job_OfferCC_CandidateRelationship();
            $relationRow = $jobOfferCandidateRelation->getRecordsByIds($this->id);
            if (count($relationRow)) {
                $jobOfferId = $relationRow[0]['Job_Offer_Id'];
            } else {
                throw new Exception($errorMessage);
            }
        }
        $jobOfferBean = BeanFactory::getBean('CC_Job_Offer', $jobOfferId);
        if (!$jobOfferBean) {
            throw new Exception($errorMessage);
        }
        $jobOfferBean->load_relationship('cc_interviewer_cc_job_offer');
        $interviewersRelated = $jobOfferBean->cc_interviewer_cc_job_offer->getBeans();
        $map = array_map($interviewerMap, $interviewersRelated);
        return $map;
    }


    public function getInfoJobApplicationWorkFlowInterviewerStep($applicationId){

        $result = array();
	
        $sql = "SELECT
                        aps.data 
                FROM 
                        cc_job_applications_cc_application_stage_c aps
                WHERE 
                       aps.completed = 0 AND aps.deleted = 0 AND aps.cc_job_applications_cc_application_stagecc_job_applications_ida = '".$applicationId."' 
                       AND aps.cc_application_stage_note = (SELECT n.id FROM notes n WHERE n.parent_type = 'CC_Job_Applications' AND n.parent_id = '".$applicationId."' ORDER BY n.date_modified DESC LIMIT 1)";


    $db = DBManagerFactory::getInstance();
    $result_total = $db->fetchOne($sql);

    $array = array();
    $array['data']   = $result_total['data'];
    $result = $array;
    
    return $result;


    }


    public function getInfoJobApplicationWorkFlow($applicationId,$getInfoJobApplicationInterviewerEmail){

		$result = array();
        $InterviewerEmail = explode(",",$getInfoJobApplicationInterviewerEmail);
        $stringInterviewerEmail = "";

        for ($i=0; $i < count($InterviewerEmail); $i++) { 
            $stringInterviewerEmail.= "'".$InterviewerEmail[$i]."',";
        }
        $stringInterviewerEmail = substr($stringInterviewerEmail, 0, -1);

	
		    $sql = "SELECT 
                            ca.name name_candidate,of.name name_job_offer,st.name name_state,
                            aps.`data`,ca.id id_candidate,ca.email email_candidate,st.settings,ja_inter.id id_interview,
                            GROUP_CONCAT(distinct ap_inter.name) name_interviewers,GROUP_CONCAT(distinct emplo.current_email) email_interviewers
                    FROM 
                            cc_job_applications_cc_application_stage_c aps
                    INNER JOIN 
                        cc_application_stage st
                            ON aps.cc_job_applications_cc_application_stagecc_application_stage_idb = st.id
                    INNER JOIN 
                        cc_job_applications_list ja
                            ON aps.cc_job_applications_cc_application_stagecc_job_applications_ida = ja.applications_id
                    INNER JOIN 
                        cc_candidate ca
                            ON ja.candidate_id = ca.id
                    INNER JOIN 
                        cc_job_offer of
                            ON ja.job_offer_id = of.id
                    LEFT JOIN   
                        cc_job_applications_cc_job_application_interviewer_c  int_p
                            ON aps.cc_job_applications_cc_application_stagecc_job_applications_ida = int_p.cc_job_app1ac4cations_ida AND int_p.cc_interviewer_idb IN (".$stringInterviewerEmail.") AND  int_p.deleted = 0
                    LEFT JOIN 
                        cc_job_application_interviewer ap_inter
                        ON int_p.cc_job_app4369rviewer_idb = ap_inter.id AND st.settings = ap_inter.interview_type
                    LEFT JOIN 
                        cc_interviewer inte
                            ON int_p.cc_interviewer_idb = inte.id  AND inte.id IN (".$stringInterviewerEmail.") AND inte.deleted = 0
                    LEFT JOIN 
                        cc_interviewer_cc_employee_information_c inter_emplo_p
                        ON inte.id = inter_emplo_p.cc_interviewer_cc_employee_informationcc_interviewer_idb AND inter_emplo_p.deleted = 0
                    LEFT JOIN 
                        cc_job_applications_cc_interviews_c ja_inter_pu
                         ON ja.applications_id = ja_inter_pu.cc_job_applications_ida AND ja_inter_pu.deleted = 0
					LEFT JOIN 
                        cc_interviews ja_inter
						 ON ja_inter_pu.cc_job_interview_idb = ja_inter.id AND ja_inter.deleted = 0 
                    LEFT JOIN 
                        cc_employee_information emplo
                        ON inter_emplo_p.cc_intervi9533rmation_ida = emplo.id
                    WHERE 
                        aps.deleted = 0 AND aps.completed = 0 AND aps.cc_job_applications_cc_application_stagecc_job_applications_ida = '".$applicationId."' 
                        AND aps.cc_application_stage_note = (SELECT n.id FROM notes n WHERE n.parent_type = 'CC_Job_Applications' AND n.parent_id = '".$applicationId."' ORDER BY n.date_modified DESC LIMIT 1)
                    GROUP BY 
                        ca.name,of.name,st.name,aps.`data`,ca.id,ca.email,st.settings";

        $db = DBManagerFactory::getInstance();
        $result_total = $db->fetchOne($sql);
        $array = array();

        $array['id']         = $applicationId;
        $array['id_candidate']   = $result_total['id_candidate'];
        $array['name_candidate']   = $result_total['name_candidate'];
        $array['id_interview']   = $result_total['id_interview'];
        $array['name_job_offer']   = $result_total['name_job_offer'];
        $array['name_interviewers']   = $result_total['name_interviewers'];
        $array['name_state']   = $result_total['name_state'];
        $array['email_candidate']   = $result_total['email_candidate'];
        $array['settings_step']   = $result_total['settings'];
        $array['email_interviewers']   = $result_total['email_interviewers'];
        $array['data']   = $result_total['data'];
        
        $result = $array;
        
        return $result;
	}


    public function getIEmailsInterviewersJobOfferWorkFlow($applicationId){

		$result = array();
	
		    $sql = "SELECT 
                            GROUP_CONCAT(distinct emplo.current_email) email_interviewers_all
                    FROM
                        cc_job_applications_list a
                    INNER JOIN 
                        cc_job_offer of
                        ON a.job_offer_id = of.id 
                    INNER JOIN 
                        cc_interviewer_cc_job_offer_c inte_offer
                        ON of.id = inte_offer.cc_interviewer_cc_job_offercc_job_offer_ida AND inte_offer.deleted = 0
                    INNER JOIN 
                        cc_interviewer inter
                        ON inte_offer.cc_interviewer_cc_job_offercc_interviewer_idb = inter.id AND inter.deleted  = 0
                    INNER JOIN 
                        cc_interviewer_cc_employee_information_c inter_emplo
                        ON inter.id = inter_emplo.cc_interviewer_cc_employee_informationcc_interviewer_idb AND inter_emplo.deleted = 0
                    INNER JOIN 
                        cc_employee_information emplo
                        ON inter_emplo.cc_intervi9533rmation_ida = emplo.id 
                    WHERE 
                        a.applications_id = '".$applicationId."' ";

	
        $db = DBManagerFactory::getInstance();
        $result_data = $db->fetchOne($sql);

        $array = array();
        $array['email_interviewers_all']   = $result_data['email_interviewers_all'];
        $result = $array;
        
        return $result;
	}


    public function insertInterviewrsApplication($interviewers_applications,$interviewers_id,$applicationId){

		$sql = "INSERT INTO 
                           cc_job_applications_cc_job_application_interviewer_c (id,date_modified,cc_job_app1ac4cations_ida,cc_job_app4369rviewer_idb,cc_interviewer_idb)
                VALUES
                           (UUID(),NOW(),'".$applicationId."','".$interviewers_applications."','".$interviewers_id."')";
	
        $db = DBManagerFactory::getInstance();
        $db->query($sql);

	}


    public function getHVCandidate($applicationId){

		$sql = "SELECT 
                        n.id id_note
                FROM
                        cc_job_applications_list a_l
                INNER JOIN 
                        notes n
                        ON a_l.candidate_id = n.parent_id AND n.deleted = 0  AND n.parent_type = 'CC_Candidate'
                WHERE 
                        n.file_mime_type = 'application/pdf' AND
                        a_l.applications_id = '".$applicationId."'";
	
            $db = DBManagerFactory::getInstance();
            $result_data = $db->fetchOne($sql);

            $array = array();
            $array['id_note']   = $result_data['id_note'];
            $result = $array;
            
            return $result;

	}


    public function getEmployeeRelated($applicationId,$type){

		$sql = "SELECT 
                        GROUP_CONCAT(em.current_email) current_email
                FROM 
                        cc_job_applications_list a
                INNER JOIN   
                        cc_job_offer o 
                        ON a.job_offer_id = o.id AND o.deleted = 0
                LEFT JOIN 
                        cc_job_offer_cc_employee_information_c of_em
                        ON o.id = of_em.cc_job_offer_cc_employee_informationcc_job_offer_ida AND of_em.deleted = 0
                LEFT JOIN 
                        cc_employee_information em
                        ON of_em.cc_job_offer_cc_employee_informationcc_employee_information_idb = em.id AND em.deleted = 0
                WHERE 
                        a.applications_id = '".$applicationId."' AND of_em.type = '".$type."' ";
	
            $db = DBManagerFactory::getInstance();
            $result_data = $db->fetchOne($sql);

            $array = array();
            $array['current_email']   = $result_data['current_email'];
            $result = $array;
            
            return $result;

	}


    public function getDataOfAdmission($applicationId){

		$sql = "SELECT
                        data 
                FROM 
                        cc_job_applications_cc_application_stage_c apl_sta
                WHERE 
                        apl_sta.deleted = 0 AND apl_sta.completed = 0 AND 
                        apl_sta.cc_job_applications_cc_application_stagecc_job_applications_ida = '".$applicationId."'
                ORDER BY 
                        apl_sta.date_modified DESC 
                LIMIT 1";
	
            $db = DBManagerFactory::getInstance();
            $result_data = $db->fetchOne($sql);

            $array = array();
            $array['data']   = $result_data['data'];
            $result = $array;
            
            return $result;

	}
    
    

}