<?php
require_once 'StageHandler.php';
require_once "modules/CC_Job_Offer/CC_Job_OfferCC_CandidateRelationship.php";
require_once "modules/CC_Job_Applications/CC_Job_Applications_CC_application_stage.php";
require_once 'custom/Extension/application/Include/cc_recruitment_activity_handler.php';

class StageActionHandler
{

    private ?string $stageId = null;
    private ?string $applicationId = null;
    private ?SugarBean $applicationBean = null;
    private ?array $relationRow = null;
    private ?SugarBean $stageBean = null;
    private ?SugarBean $current_user;

    public function __construct($stageId,$applicationId)
    {
        $this->applicationId = $applicationId;
        $this->stageId = $stageId;
        $this->applicationBean = BeanFactory::getBean('CC_Job_Applications', $this->applicationId);
        $jobOfferCandidateRelation = new CC_Job_OfferCC_CandidateRelationship();
        $this->relationRow = $jobOfferCandidateRelation->getRecordsByIds($this->applicationId);
        $this->stageBean = BeanFactory::getBean('CC_application_stage', $this->stageId);
        $this->current_user = BeanFactory::newBean('Users');
        $this->current_user->getSystemUser();

    }

    /**
     * return actual stage settings
     * @return string|null
     */
    public function getStageSettings(): ?string
    {
        if($this->stageBean && $this->stageBean->settings){
            return strtoupper($this->stageBean->settings);
        }
        return null;
    }

    /**
     * @param array $data
     * @param string $relatedNote
     * @return string
     */
    public function markStageAsCompleted(array $data,string $relatedNote, string $targetStageId = "", int $closed_state = 0): string
    {
        $data_output = self::registerApplicationStage(
            $this->applicationId,
            $this->stageId,
            0,
            $data,
            $relatedNote,
            $closed_state
        );

       

        $stageHandler = new StageHandler($this->stageId);
        $stageHandler->setApplication($this->applicationBean);
        $nextStage = $stageHandler->getNextStage($targetStageId);
        $nextStageData = $nextStage;
        //enter if it is the last step and there is no next
        if($nextStageData->name == ""){
             $this->applicationBean->actual_stage    = $this->stageBean->name;
             $this->applicationBean->actual_stage_id = $this->stageBean->id;
             $this->applicationBean->last_modification_data  = date('Y-m-d h:i:s');
        }else{
            if(isset($data['approved_interview']) && $data['approved_interview'] == "Not Approved"){
                $JobApplicationJobApplicationStage = new CC_Job_Applications_CC_application_stage();
                $dataUltimateStep = $JobApplicationJobApplicationStage->getUltimateStep($this->stageBean->type);
                $this->applicationBean->actual_stage    = $dataUltimateStep['name'];
                $this->applicationBean->actual_stage_id = $dataUltimateStep['id'];
                $this->applicationBean->last_modification_data  = date('Y-m-d h:i:s');
            }else{ 
                $this->applicationBean->actual_stage    = $nextStageData->name;
                $this->applicationBean->actual_stage_id = $nextStageData->id;
                $this->applicationBean->last_modification_data  = date('Y-m-d h:i:s');
            }
        }
        
        $result = $this->applicationBean->save();

        return $data_output;
    }

    /**
     * @param $applicationId
     * @param $stageId
     * @param $completed
     * @param $data
     * @param null $relatedNote
     * @return string
     */
    public static function registerApplicationStage(
        $applicationId,
        $stageId,
        $completed,
        $data,
        $relatedNote = null,
        $closed_state
    ): string
    {

        $stageCompletedRecord = new CC_Job_Applications_CC_application_stage();
        $stageCompletedRecord->cc_job_applications_cc_application_stagecc_job_applications_ida = $applicationId;
        $stageCompletedRecord->cc_job_applications_cc_application_stagecc_application_stage_idb = $stageId;
        $stageCompletedRecord->deleted = 0;
        $stageCompletedRecord->completed = $completed;
        $stageCompletedRecord->data = json_encode($data);
        $stageCompletedRecord->cc_application_stagecc_employee_information_last_user = $_SESSION['authenticated_user_id'];
        $stageCompletedRecord->cc_application_stage_note = $relatedNote;
        $stageCompletedRecord->closed_state = $closed_state;
        $result = $stageCompletedRecord->save();

        if($result){
            $registry = (new CC_Recruitment_Activity_Handler($stageCompletedRecord,"Step change in job application",null,$stageCompletedRecord->data,$applicationId))->saveRecruitmentActivityStage();
        }
       
        return $result;
    }

    /**
     * @param string $text
     * @return Note
     */
    public function createRelatedNote(string $text, $notesFile=""): Note
    {
        global  $sugar_config;

        $note = new Note();
        $note->modified_user_id = $this->current_user->id;
        $note->created_by = $this->current_user->id;
        $note->assigned_user_id = $this->current_user->id;
        $note->name = $this->applicationBean->name.' / '.$this->stageBean->name;
        $note->parent_type = "CC_Job_Applications";
        $note->parent_id = $this->applicationId;
        $note->description = $text;

        if(!empty($notesFile['file']['tmp_name'])){ 
              
            $name_file_attached = $notesFile['file']['name'];
            $type_file_attached = $notesFile['file']['type'];
            $ext_file_attached  = explode('/',$notesFile['file']['type']);
            $size_file_attached = $notesFile['file']['size'];
            $tmp_file_attached  = $notesFile['file']['tmp_name'];
            $new_name_attached  = $this->applicationId ."_".date('Y-m-d_H:m:s').".".end($ext_file_attached);

            //$path_invoice = $sugar_config['upload_dir']."invoice_attachments/";
            $path_invoice = $sugar_config['upload_dir'];


            $attached_file = move_uploaded_file($tmp_file_attached,$path_invoice.$new_name_attached);
            if($attached_file){
                chmod($path_invoice.$new_name_attached, 0777);
                $note->file_mime_type = $type_file_attached;
                $note->filename = $notesFile['file']['name'];   
                $flag = 1;                
            }else{
                echo "attached error";
                $flag = 0;
            }   
            
        }

        $noteID = $note->save();

        if($noteID !== false && intval($flag) == 1){
            rename($path_invoice.$new_name_attached, $sugar_config['upload_dir'] . $noteID);
        }
        return $note;
    }

     /**
     * @param string $name,$current_step
     * @return Interview
     */
    public function createApplicationInterview(string $name, string $current_step): CC_Interviews
    {
        $jobApplicationInterview = new CC_Interviews();
        $jobApplicationInterview->modified_user_id = $this->current_user->id;
        $jobApplicationInterview->created_by = $this->current_user->id;
        $jobApplicationInterview->assigned_user_id = $this->current_user->id;
        $jobApplicationInterview->name = $name;
        $jobApplicationInterview->type = $current_step;
        $result = $jobApplicationInterview->save();

        if($result){
            $registry = (new CC_Recruitment_Activity_Handler($jobApplicationInterview,"".$current_step." interview is created",null,null,$this->applicationBean->id))->saveRecruitmentActivity();
        }

        return $jobApplicationInterview;
    }

     /**
     * @param string interviewes_name,current_step
     * @return String with the interviewers' id
     */
    public function createApplicationInterviewes(string $interviewes_name,$current_step): string
    {
        $interviewes_name_all = explode(',', $interviewes_name);
        $respu = "";
        for($i=0; $i < count($interviewes_name_all); $i++) { 
            $jobApplicationInterviewes = new CC_Job_Application_Interviewer();
            $jobApplicationInterviewes->modified_user_id = $this->current_user->id;
            $jobApplicationInterviewes->created_by = $this->current_user->id;
            $jobApplicationInterviewes->assigned_user_id = $this->current_user->id;
            $jobApplicationInterviewes->name = $interviewes_name_all[$i];
            $jobApplicationInterviewes->interview_type = $current_step;
            $respu.= $jobApplicationInterviewes->save();
            $respu.=",";
        }
        $respu = substr($respu, 0, -1);
        return $respu;
    }


    
    /**
     * @param string $text
     */
    public function saveInformativeAction(string $text,string $targetStageId = ""){
        try {
            $note = $this->createRelatedNote($text);
            $this->applicationBean->load_relationship('cc_job_applications_notes');
            $this->applicationBean->cc_job_applications_notes->add($note);
            self::markStageAsCompleted(['note'=>$text],$note->id, $targetStageId);
            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }


    /**
      * @param string $text
    */
    public function saveTestsAction(string $text,string $targetStageId = ""){
        try {
            $note = $this->createRelatedNote($text);
            $this->applicationBean->load_relationship('cc_job_applications_notes');
            $this->applicationBean->cc_job_applications_notes->add($note);
            self::markStageAsCompleted(['approved'=>$text],$note->id, $targetStageId);
            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }


    /**
      * @param string $note,$approved 
    */
    public function saveMonetaryAction(string $text, string $approved,string $targetStageId = ""){
        try {
            $note = $this->createRelatedNote($text ."|". $approved);
            $this->applicationBean->load_relationship('cc_job_applications_notes');
            $this->applicationBean->cc_job_applications_notes->add($note);
            self::markStageAsCompleted(['note'=>$text,'approved'=>$approved],$note->id, $targetStageId);
            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }


     /**
      * @param string $note,$approved,$date_of_admission
    */
    public function saveClosedAction(string $text, string $approved, string $date_of_admission, string $targetStageId = "", string $send_email){
        try {
            $note = $this->createRelatedNote($text ."|". $approved);
            $this->applicationBean->load_relationship('cc_job_applications_notes');
            $this->applicationBean->cc_job_applications_notes->add($note);
            $closed_state = ($approved == "Close Won") ? 1 : 2;
          
            self::markStageAsCompleted(['note'=>$text,'approved'=>$approved, 'date_of_admission' => $date_of_admission, 'send_email' => $send_email],$note->id, $targetStageId ,$closed_state);
            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }


     /**
      * @param string  $schedule_date, $start_hours, $end_hours, $start_minutes, $end_minutes, $interviewers
    */
    public function saveScheduleAction(string $currentStep,string $jobOfferName,string $schedule_date, string $start_hours, string $end_hours, string $start_minutes, string $end_minutes, string $interviewers_id, string $interviewers_name,string $targetStageId = ""){
        try {
           
            $note = $this->createRelatedNote($schedule_date." ".$start_hours.":".$start_minutes.":".$end_hours.":".$end_minutes);
            $this->applicationBean->load_relationship('cc_job_applications_notes');
            $this->applicationBean->cc_job_applications_notes->add($note);

            $interview  = $this->createApplicationInterview($jobOfferName,$currentStep);
            $this->applicationBean->load_relationship('cc_job_applications_cc_interviews');
            $this->applicationBean->cc_job_applications_cc_interviews->add($interview);

            $interviewes = $this->createApplicationInterviewes($interviewers_name,$currentStep); 
            $this->applicationBean->load_relationship('cc_job_applications_cc_job_application_interviewer');
            $all_interviewes_applications = explode(",",$interviewes);
            $all_interviewes_ids = explode(",",$interviewers_id);

            for ($i=0; $i < count($all_interviewes_applications); $i++) { 
                $interviewersRelations = new CC_Job_Applications();
                $profileFieldsRelation = $interviewersRelations->insertInterviewrsApplication($all_interviewes_applications[$i], $all_interviewes_ids[$i],$this->applicationId);
                //$this->applicationBean->cc_job_applications_cc_job_application_interviewer->add($all_interviewes[$i]);
            }

            self::markStageAsCompleted(['schedule_date'=>$schedule_date,'start_hours'=>$start_hours,'end_hours'=> $end_hours,'start_minutes'=>$start_minutes,'end_minutes'=>$end_minutes,'interviewers_id'=> $interviewers_id],$note->id, $targetStageId);
            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }


     /**
      * @param string $interview_date,$english_level,$approved_interview,$recomended_position,$positove_aspects,$what_to_improve,$participants_data,$general_comments,$current_step
    */
    public function saveInterviewAction(string $interview_date,string $english_level,string $approved_interview,string $recomended_position,string $positove_aspects,string $what_to_improve,string $participants_data,string $general_comments,string $current_step, string $targetStageId = "", string $edit = "0", string $id_application_stage = "", $noteFile){
       

        try {
            $participants_data_convert = json_decode($participants_data);

            if($edit == "0"){  
                $note = $this->createRelatedNote($interview_date." ".$english_level." ".$approved_interview." ".$recomended_position." ".$positove_aspects ." ". $what_to_improve ." ". $general_comments, $noteFile);
                $this->applicationBean->load_relationship('cc_job_applications_notes');
                $this->applicationBean->cc_job_applications_notes->add($note);
                self::markStageAsCompleted(['interview_date'=>$interview_date,'english_level'=>$english_level,'approved_interview'=> $approved_interview,'recomended_position'=>$recomended_position,'positove_aspects'=>$positove_aspects,'what_to_improve'=> $what_to_improve,'participants_data'=> $participants_data_convert,'general_comments'=> $general_comments],$note->id, $targetStageId);
            }else{
                $stageCompletedRecordEdit = new CC_Job_Applications_CC_application_stage();
                $stageCompletedRecordEdit->retrieve($id_application_stage);
                $stageCompletedRecordEdit->data = json_encode(['interview_date'=>$interview_date,'english_level'=>$english_level,'approved_interview'=> $approved_interview,'recomended_position'=>$recomended_position,'positove_aspects'=>$positove_aspects,'what_to_improve'=> $what_to_improve,'participants_data'=> $participants_data_convert,'general_comments'=> $general_comments]);
                $stageCompletedRecordEdit->cc_application_stagecc_employee_information_last_user = $_SESSION['authenticated_user_id'];
                $stageCompletedRecordEdit->save();
            }

            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }



     /**
      * @param string $testToSend,$idTemplate,$templatesVariables
    */
    public function saveSendAction(string $testToSend, string $idTemplate, string $nameTemplate, string $templatesVariables,string $targetStageId = ""){
        try {
           
            $temp = str_replace("$", "" , $templatesVariables);
            $dataVariables  = json_decode($temp);
            $note = $this->createRelatedNote($testToSend);
            $this->applicationBean->load_relationship('cc_job_applications_notes');
            $this->applicationBean->cc_job_applications_notes->add($note);
            self::markStageAsCompleted(['testToSend'=>$testToSend,'idTemplate'=>$idTemplate,'nameTemplate' => $nameTemplate,'templatesVariables'=> $dataVariables],$note->id, $targetStageId);
            return self::result(200, $this->stageBean->name.' Stage Updated / Note '.$note->name.' Created');
        } catch (Exception $e){
            return self::result(500, $e->getMessage());
        }
    }


    /**
     * @param $date
     * @param $start
     * @param $end
     * @param $emailList
     */
    public function sendScheduleAction($date,$start,$end, $emailList){

    }

    /**
     * @param $url
     * @param $dueDate
     */
    public function sendApplicationTestAction($url, $dueDate){

    }

    /**
     * @param $textNote
     * @param $emailList
     * @param null $dueDate
     */
    public function sendRequestForApproval($textNote, $emailList, $dueDate=null){

    }

    private static function result($status, $message){
        $response = (object)[
            'status' => $status,
            'message' => $message
        ];
        return json_encode($response);
    }

}