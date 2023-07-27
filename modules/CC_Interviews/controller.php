<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class CC_InterviewsController extends SugarController {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_InterviewsController() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    public function getInterviewResultsByApplicationsId($applicationsId)
    {
        $sql = "SELECT jai.type AS 'type', jai.approved AS 'approved', jai.result AS 'result', jai.observation AS 'observation',
                jajai.cc_job_applications_ida AS 'applications_id', jai.id AS 'job_application_interview_id', jai.recommended AS 'recommended',
                jai.english_level AS 'english_level', jai.positive_aspects AS 'positive_aspects', jai.what_to_improve AS'what_to_improve',
                jai.description AS 'description', jai.other_position AS 'other_position', jai.interview_date AS 'interview_date'
            FROM cc_interviews AS jai
            INNER JOIN cc_job_applications_cc_interviews_c AS jajai
                ON jajai.cc_job_interview_idb=jai.id
            WHERE jajai.cc_job_applications_ida='".$applicationsId."';";
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

   ///// interview results //////////////

	function getInterviewResult($applicationId){
		$result = array();
		$cc_job_applications_cc_interviews = 'cc_job_applications_cc_interviews';
		$jobApplication = BeanFactory::getBean('CC_Job_Applications',$applicationId);
	
		if($jobApplication){   
		   $jobApplication->load_relationship($cc_job_applications_cc_interviews);
	
		   $intresultFields = $jobApplication->cc_job_applications_cc_interviews->getBeans();
		   
		   if($intresultFields){
			  foreach($intresultFields as $key => $item){
				
				$array = array();
				$array['id']              = $intresultFields[$key]->id;
				$array['name']            = $intresultFields[$key]->name;
				$array['description']     = $intresultFields[$key]->description;
				$array['approved']        = $intresultFields[$key]->approved;
				$array['interview_date']  = $intresultFields[$key]->interview_date;
				$array['observation']     = $intresultFields[$key]->observation;
				$array['result']          = $intresultFields[$key]->result;
				$array['type']            = $intresultFields[$key]->type;
				$array['english_level']   = $intresultFields[$key]->english_level;
				$array['positive_aspects']= $intresultFields[$key]->positive_aspects;
				$array['what_to_improve'] = $intresultFields[$key]->what_to_improve;
				$array['recommended']     = $intresultFields[$key]->recommended;
				$array['other_position']  = $intresultFields[$key]->other_position;
				$array['interview_results']  = $intresultFields[$key]->interview_results;
				$result[] = $array; 
			  }
		   }
		}
	
		 $json_data = array(
			"data" => $result
		 );
	
		  return $json_data;
	  }

	  public function createInterviewResult($intresult, $point){
        
        global  $sugar_config;
		$time = new \DateTime();

		if(strlen($intresult['id_intresult']) == 0){

			$intresultBean = BeanFactory::newBean('CC_Interviews');
			$intresultBean->date_entered     = $time->format('Y-m-d H:i:s');
			$intresultBean->date_modified    = $time->format('Y-m-d H:i:s');
			$intresultBean->assigned_user_id = $_SESSION['authenticated_user_id'];
			$intresultBean->created_by 		 = $_SESSION['authenticated_user_id'];

		}else{

			$intresultBean = BeanFactory::getBean('CC_Interviews', $intresult['id_intresult']);
			$intresultBean->date_modified = $time->format('Y-m-d H:i:s');
			$intresultBean->modified_user_id = $_SESSION['authenticated_user_id'];

		}

		$intresultBean->name             = $intresult['name'];
		$intresultBean->description      = $intresult['description']; 
		$intresultBean->approved         = $intresult['approved']; 
		$intresultBean->interview_date   = $intresult['interview_date']; 
		$intresultBean->observation      = $intresult['observation']; 
		$intresultBean->result           = $intresult['result']; 
		$intresultBean->type             = $intresult['type']; 
		$intresultBean->english_level    = $intresult['english_level']; 
		$intresultBean->positive_aspects = $intresult['positive_aspects']; 
		$intresultBean->what_to_improve  = $intresult['what_to_improve']; 
		$intresultBean->recommended      = $intresult['recommended']; 
		$intresultBean->other_position   = $intresult['other_position']; 
		$intresultBean->interview_results= $intresult['interview_results'];

		if (key_exists('recommended_position_id', $intresult ) && !is_null($intresult['recommended_position_id']) ){
            $intresultBean->cc_job_description_id_c = $intresult['recommended_position_id'];
        }

		$intresultBean->save();

		if($point == 1){
				$relation1 = 'cc_job_applications_cc_interviews';
				$jobApplicationRecord = new CC_Interviews();
				$jobApplicationRecord->retrieve($intresultBean->id);
				$jobApplicationRecord->load_relationship($relation1);
				$jobApplicationRecord->$relation1->add($intresult['applicationId']);
				$jobApplicationRecord->save();
			}


            $json_respu = array("module" => $intresultBean->object_name, "id" => $intresultBean->id);
        

        return $json_respu;
    }

	public function getIntResultSingle($idResult){

        $getInterview  = BeanFactory::getBean('CC_Interviews', $idResult);

         if($getInterview){

            $recommended_position = property_exists($getInterview, 'recommended_position')?$getInterview->recommended_position:null;
            $results[] = (object) [
                'id'              => $getInterview->id,
                'name'            => $getInterview->name,
                'description'     => $getInterview->description,
                'approved'        => $getInterview->approved,
                'interview_date'  => $getInterview->interview_date,
                'observation'     => $getInterview->observation,
                'result'          => (is_null($getInterview->result) || ($getInterview->result == "")) ? "0.00" : $getInterview->result,
				'type'        	  => $getInterview->type,
                'english_level'   => $getInterview->english_level,
                'positive_aspects'=> $getInterview->positive_aspects,
                'what_to_improve' => $getInterview->what_to_improve,
                'recommended'     => $getInterview->recommended,
                'other_position'  => $getInterview->other_position,
                'interview_results'  => $getInterview->interview_results,
                'recommended_position_id' => $getInterview->cc_job_description_id_c,
                'recommended_position' => $recommended_position
            ];
          }
   
          return $results;
    }

	public function getAccessTokenWihtUserId($userId)
    {
        $sql = "SELECT up.contents
            FROM user_preferences AS up
            WHERE up.deleted=0 AND up.category='GoogleSync' AND up.assigned_user_id='".$userId."';";
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();
        // Perform the query
        $rows = $db->query($sql);
        // Initialize an array with the results
        $GoogleApiToken = null;
        // Fetch the row
        while ($row = $db->fetchRow($rows)) {
			$contents = base64_decode($row["contents"]);
			$arrayContents = explode(':"', $contents);
			if(!preg_match("[GoogleApiToken]", $arrayContents[2])) return $GoogleApiToken;
			$base64GoogleApiToken = explode('";', $arrayContents[3])[0];
			$GoogleApiToken = base64_decode($base64GoogleApiToken);
        }
        return $GoogleApiToken;
    }

}