<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once 'modules/CC_application_stage/StageActionHandler.php';
require_once 'modules/CC_Job_Applications/CC_Job_Applications_CC_application_stage.php';
require_once 'modules/CC_Job_Applications/controller.php';
require_once 'modules/CC_Profile/controller.php';
require_once 'modules/CC_Profile/RatingCalculationUtility.php';

$res = [];
$default = array("options" => array(
    "default" => null
));

$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT, $default);
$candidateId = filter_input(INPUT_POST, 'candidateId', FILTER_DEFAULT, $default);
$jobOfferId = filter_input(INPUT_POST, 'jobOfferId', FILTER_DEFAULT, $default);
$applicationId = filter_input(INPUT_POST, 'applicationId', FILTER_DEFAULT, $default);
$stageId = filter_input(INPUT_POST, 'stageId', FILTER_DEFAULT, $default);
$stageAction = filter_input(INPUT_GET, 'stageAction', FILTER_DEFAULT, $default);


if (!is_null($action) && !is_null($candidateId) && !is_null($jobOfferId)) {
    if ($action == 'getRatings'){
        $res = ['skillRating' => 0, 'qualificationRating' =>  0, 'generalRating' => 0 ];
        $rObject = new RatingCalculationUtility();
        $res = $rObject->calculateJobOfferCandidateRating($candidateId,$jobOfferId);
    }
}
if (!is_null($stageAction)) {

    if($stageAction == "getStatusJA"){

        $cObject = new CC_Job_ApplicationsController();
        $results = $cObject->getStatusJA($applicationId);
        $res = (object)['results' => $results];
    }

    if($stageAction == "getResumeCandidate"){
        $cObject = new CC_Job_ApplicationsController();
        $results = $cObject->getResumeCandidate($candidateId);
        $res = (object)['results' => $results];
    }
        
    if($stageAction == 'getStepStatus'){
        $dataStepStatus = new CC_Job_Applications_CC_application_stage();
        $stageId = filter_input(INPUT_POST, 'stageId', FILTER_DEFAULT, $default);
        $applicationId = filter_input(INPUT_POST, 'applicationId', FILTER_DEFAULT, $default);
        $respu = $dataStepStatus->get_relation_row($applicationId,$stageId);
                
        $Applications = BeanFactory::getBean('CC_Job_Applications',$applicationId);
        $Applications->load_relationship('cc_job_applications_cc_job_application_interviewer');
        $ApplicationsInterviewers = $Applications->cc_job_applications_cc_job_application_interviewer->getBeans();

        $result = array();
        $result_interviewers = array();
        $resultInterviewers = array();
        $array = array();

        $array['id']         = $respu->id;
        $array['data']       = html_entity_decode($respu->data);
        $array['completed']  = $respu->completed;
        //enter the steps of interviewers
        if($ApplicationsInterviewers){
            foreach($ApplicationsInterviewers as $key => $item){
                $result_interviewers['id_interviewers']   = $ApplicationsInterviewers[$key]->id;
                $result_interviewers['name_interviewers'] = $ApplicationsInterviewers[$key]->name;
                $result_interviewers['interview_type']    = $ApplicationsInterviewers[$key]->interview_type;
                $resultInterviewers[] = $result_interviewers; 
            }
            $array['data_interviewers']  = $resultInterviewers;
            $result = $array;
        }else{
           $result = $array;
        } 
        
        $res  = (object)['results' => $result];
    }


    if ($stageAction == 'updateStage'){
        $actionHandler = new StageActionHandler($stageId,$applicationId);
        $settings = $actionHandler->getStageSettings();
        $targetStageId = filter_input(INPUT_POST, 'targetStageId', FILTER_DEFAULT, $default);

      
        switch ($settings){
            case "INFORMATIVE":
                $note = filter_input(INPUT_POST, 'note', FILTER_DEFAULT, $default);
                $res = $actionHandler->saveInformativeAction($note,$targetStageId);
            break;
            case "TESTS":
                $approved = filter_input(INPUT_POST, 'approved', FILTER_DEFAULT, $default);
                $res = $actionHandler->saveTestsAction($approved,$targetStageId);
            break;
            case "MONETARYA":
                $note = filter_input(INPUT_POST, 'note', FILTER_DEFAULT, $default);
                $approved = filter_input(INPUT_POST, 'approved', FILTER_DEFAULT, $default);
                $res = $actionHandler->saveMonetaryAction($note,$approved,$targetStageId);
            break;
            case "CLOSED":
                $note = filter_input(INPUT_POST, 'note', FILTER_DEFAULT, $default);
                $approved = filter_input(INPUT_POST, 'approved', FILTER_DEFAULT, $default);
                $date_of_admission = filter_input(INPUT_POST, 'date_of_admission', FILTER_DEFAULT, $default);
                $send_email = filter_input(INPUT_POST, 'send_email', FILTER_DEFAULT, $default);
                $res = $actionHandler->saveClosedAction($note,$approved,$date_of_admission,$targetStageId,$send_email);
            break;
            case "SCHEDULE":
            case "SCHEDULEHT":
            case "SCHEDULETEC":
                $schedule_date = filter_input(INPUT_POST, 'schedule_date', FILTER_DEFAULT, $default);
                $start_hours   = filter_input(INPUT_POST, 'start_hours', FILTER_DEFAULT, $default);
                $end_hours     = filter_input(INPUT_POST, 'end_hours', FILTER_DEFAULT, $default);
                $start_minutes = filter_input(INPUT_POST, 'start_minutes', FILTER_DEFAULT, $default);
                $end_minutes   = filter_input(INPUT_POST, 'end_minutes', FILTER_DEFAULT, $default);
                $interviewers_id    = filter_input(INPUT_POST, 'interviewers_id', FILTER_DEFAULT, $default);
                $interviewers_name  = filter_input(INPUT_POST, 'interviewers_name', FILTER_DEFAULT, $default);
                $job_offer_name  = filter_input(INPUT_POST, 'jobOfferName', FILTER_DEFAULT, $default);
                $current_step    = $settings;
                $res = $actionHandler->saveScheduleAction($current_step,$job_offer_name = '',$schedule_date,$start_hours,$end_hours,$start_minutes,$end_minutes,$interviewers_id,$interviewers_name,$targetStageId);
            break;
            case "INTERVIEW":
                $interview_date      = filter_input(INPUT_POST, 'interview_date', FILTER_DEFAULT, $default);
                $english_level       = filter_input(INPUT_POST, 'english_level', FILTER_DEFAULT, $default);
                $approved_interview  = filter_input(INPUT_POST, 'approved_interview', FILTER_DEFAULT, $default);
                $recomended_position = filter_input(INPUT_POST, 'recomended_position', FILTER_DEFAULT, $default);
                $positove_aspects    = filter_input(INPUT_POST, 'positove_aspects', FILTER_DEFAULT, $default);
                $what_to_improve     = filter_input(INPUT_POST, 'what_to_improve', FILTER_DEFAULT, $default);
                $participants_data   = filter_input(INPUT_POST, 'participants_data', FILTER_DEFAULT, $default);
                $general_comments    = filter_input(INPUT_POST, 'general_comments', FILTER_DEFAULT, $default);
                $current_step        = $settings;
                $edit                 = filter_input(INPUT_POST, 'edit', FILTER_DEFAULT, $default);
                $id_application_stage = filter_input(INPUT_POST, 'id_application_stage', FILTER_DEFAULT, $default);

                $res = $actionHandler->saveInterviewAction($interview_date,$english_level,$approved_interview,$recomended_position,$positove_aspects,$what_to_improve,$participants_data,$general_comments,$current_step,$targetStageId,$edit,$id_application_stage,$_FILES);
            break;
            case "SEND":
                $testToSend = filter_input(INPUT_POST, 'testToSend', FILTER_DEFAULT, $default);
                $idTemplate = filter_input(INPUT_POST, 'idTemplate', FILTER_DEFAULT, $default);
                $nameTemplate = filter_input(INPUT_POST, 'nameTemplate', FILTER_DEFAULT, $default);
                $templatesVariables = filter_input(INPUT_POST, 'templatesVariables', FILTER_DEFAULT, $default);
                $res = $actionHandler->saveSendAction($testToSend,$idTemplate,$nameTemplate,$templatesVariables,$targetStageId);
            break;

        }

    }


    if($stageAction == 'getTemplateVariables'){
       $template_words       = explode(' ',preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $_POST['template']));
       $remove_spelling_sign = array(",",".",";",":");
       $template_variables   = array();

       for($i=0; $i < count($template_words) ; $i++) { 
         $remove_one =  str_replace("$", "", $template_words[$i]);
         $variable   =  str_replace("_", "",  $remove_one);
         $validate_variable = strpos($template_words[$i], "$");

        if($validate_variable !== false) {
           //this in case there is a spelling sign attached to the variable
           if(in_array(substr($variable, -1), $remove_spelling_sign)) {
              $variable   = substr($variable, 0, -1);
              $template_words[$i] = substr($template_words[$i], 0, -1);
           }
           //this because there are some variables that are not of the user as url etc.
           if(ctype_alpha($variable)){
              array_push($template_variables, $template_words[$i]);
           }
        }  
       }// end for
    
       $res = (object)['results' => $template_variables];

    }


    if ($stageAction == 'getTemplate'){

        $GetTemplates = BeanFactory::getBean('EmailTemplates');
        $where        = !empty($_POST['searchTerm']) ? "email_templates.name like '%".$_POST['searchTerm']."%'" : '';
        $beanList     = $GetTemplates->get_full_list('name',$where,false,0);

        if($beanList){
            foreach($beanList as $key => $item){
                $results[] = (object) [
                    'id'    => $beanList[$key]->id,
                    'name'  => $beanList[$key]->name,
                    'body'  => $beanList[$key]->body
                ];        
            }
          }
    
          $res = (object)['results' => $results];

    }
    /*
     * This is an HTML end point
     */
    if ($stageAction == 'getStageTemplate'){

        $smarty = new Sugar_Smarty();
        $templateDir = 'modules/CC_Job_Applications/tpls/';
        $stage_id = filter_input(INPUT_POST, 'stage_id', FILTER_DEFAULT, $default);
        $target_stage_id = filter_input(INPUT_POST, 'target_stage_id', FILTER_DEFAULT, $default);
        $applications_id = filter_input(INPUT_POST, 'applications_id', FILTER_DEFAULT, $default);
        $candidate_id = filter_input(INPUT_POST, 'candidate_id', FILTER_DEFAULT, $default);
        $job_offer_id = filter_input(INPUT_POST, 'job_offer_id', FILTER_DEFAULT, $default);
        $job_offer_name = filter_input(INPUT_POST, 'job_offer_name', FILTER_DEFAULT, $default);

        if(!is_null($applications_id) && !is_null($stage_id) && !is_null($candidate_id) && !is_null($job_offer_id)){

            $application_bean = BeanFactory::getBean('CC_Job_Applications',$applications_id);
            $stage_bean = BeanFactory::getBean('CC_application_stage',$stage_id);
            $target_stage = BeanFactory::getBean('CC_application_stage',$target_stage_id);

            $stageHandler = new StageHandler();
            $stageHandler->setApplication($application_bean);

            if($stageHandler->isStageCompleted($target_stage_id)){
                echo "Error, Target stage is already completed";
                exit;
            }

            if($target_stage->stageorder < $stage_bean->stageorder){
                echo "Error, Target stage should not be a previous stage";
                exit;
            }

            $actionStage = (object) [
                "name" => $stage_bean->name,
                "id" => $stage_bean->id,
                "order" => $stage_bean->stageorder
            ];

            $candidateAvailabilityData = $application_bean->getCandidateRelatedAvailabilityInfo($candidate_id);
            $jobOfferInterviewersData = $application_bean->getJobOfferRelatedInterviewersInfo($job_offer_id);

            $JobDescription = BeanFactory::getBean('CC_Job_Description');

            $JobDescriptionFields = $JobDescription->get_full_list();
            $result_job_description = array();

            foreach($JobDescriptionFields as $key => $item){
                $array = array();
                $array['id_job_description']   = $JobDescriptionFields[$key]->id;
                $array['name_job_description'] = $JobDescriptionFields[$key]->name;
                $result_job_description[] = $array;
            }

            $smarty->assign('CANDIDATEID', $candidate_id);
            $smarty->assign('JOBOFFERID',$job_offer_id);
            $smarty->assign('JOBOFFERNAME',$job_offer_name);

            $smarty->assign('BEANID', $applications_id ?? 'null' );
            $smarty->assign('MODULE', "CC_Job_Applications");
            $smarty->assign('CANDIDATEAVAILABILITY', $candidateAvailabilityData);
            $smarty->assign('INTERVIEWERS', $jobOfferInterviewersData);
            $smarty->assign('JOBDESCRIPTION', $result_job_description);

            $smarty->assign('actionStage', $actionStage);
            $smarty->assign('targetStageId', $target_stage->id);
            $smarty->assign('stageId', $stage_bean->id);
            $smarty->assign('actionType', strtolower($stage_bean->settings));

            $tpl = $templateDir.strtolower($stage_bean->settings).'_action.tpl';
            $view = $smarty->fetch($tpl);
            /*
            if($stageHandler->isStageCompleted($target_stage_id)){
                echo "<div class='actionCardHeader step-error'><h2>Target stage is already completed</h2></div>";
            }
            */
            echo $view;
        }
        exit;
    }


     if ($stageAction == 'getPermissions'){

        $listSecurityGroup= SecurityGroup::getAllSecurityGroups();

        $result = array();
        foreach ($listSecurityGroup as $key => $value) {
            $array = array();
            $array['id']   = $value['id'];
            $array['name'] = $value['name'];
            $result[] = $array;
        }
    
        $res = (object)['results' => $result];

    }

}

echo json_encode($res);