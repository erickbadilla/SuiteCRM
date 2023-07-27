<?php
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';

use Api\V8\Config\Common as Common;
use Api\V8\Utilities;
use Mockery\Undefined;

class AR_Activity_ReportController extends SugarController
{

    private $queryActivityReport =
    "SELECT m.id, m.name, m.description, m.date_start,DATE_FORMAT(m.date_start, '%m/%d/%Y') dateStr, 
    DATE_FORMAT(m.date_start,'%H')  hourStr, DATE_FORMAT(m.date_start,'%i')  minuteStr,
     m.parent_type, m.parent_id, SUM((m.duration_hours*60)+ m.duration_minutes) as duration,
     ar.last_start_time, ar.accumulated_time, ar.status as status, ar.is_billable,
     DATEDIFF(NOW(), ar.last_start_time) AS running_days,
     TIME_FORMAT(TIMEDIFF(NOW(), ar.last_start_time), '%H') AS running_hours,
     TIME_FORMAT(TIMEDIFF(NOW(), ar.last_start_time), '%i') AS running_minutes,
     ar.parent_type AS typeAr, ar.id as idAr  
     FROM  ar_activity_report ar 
     JOIN meetings m ON ar.parent_id =m.id";

    private $queryActivityReportParticipants =
    "SELECT arp.id 'idParRel', p.id, p.name, p.parent_id as participant, p.is_owner 
    FROM ar_activity_report_ar_participant_c arp
    LEFT JOIN ar_participant p ON p.id = arp.ar_activity_report_ar_participantar_participant_idb
    WHERE arp.deleted = 0 ";

    private $queryDutiesParticipants =
    "SELECT ar.id as id_duty,ar.description,ar.due_date, ar.original_estimate, ar.cc_employee_information_id_c as cc_employee
    FROM ar_activity_duty ar
    WHERE ar.deleted = 0 ";

    private $queryStopAllUserRunnningActivities =
        "UPDATE ar_activity_report SET ".
        "status='paused',accumulated_time= (TIME_FORMAT(TIMEDIFF(NOW(), last_start_time), '%H')*60)+".
        "TIME_FORMAT(TIMEDIFF(NOW(), last_start_time), '%i'), last_start_time=null";

    private $object_name = "AR_Activity_Report";

    public function __construct(){
        parent::__construct();
    }

    function bringRelatedTo($module){

        $bean = BeanFactory::getBean($module);
        $list =  $bean->get_full_list('', '' ,false,0);

        $result = [];
        foreach ($list as $item){
            $result[] = (object) [
                "id" => $item->id,
                "name" => $item->name,
            ];
        }
        return $result;
    }

	

	  public function CreateActivityReport($notesData,$notesFile){
        global  $sugar_config;
        global $current_user;
		$time = new \DateTime();
        $meetingID=$this->create_meeting_call($notesData, $notesData['idMain'],$current_user->id);
        
        if($meetingID !== false){
            if($notesData['id'] ==""){
                $activityRID = $this->CreateActivity($notesData,null, $current_user->id,$meetingID);

            }else {

                $activitiRBean = BeanFactory::getBean('AR_Activity_Report',$notesData['id']); 
                $activityRID = $this->updateActivity($activitiRBean,$notesData,null, $current_user->id,$meetingID);
                
            }  

            for ($x = 0; $x < $notesData['numberFiles']; $x++) {
                if($notesData['description'.$x] != 'undefined'){
                    $name_note = (!empty($notesData['subject'])) ? " / ".$notesData['subject'] .$x : "";
                    $description = $notesData['description'.$x];
                    $this->create_note($name_note,$description,$notesFile['file'.$x], $meetingID, $current_user->id, $notesData['typeAr']);
            }
            }

            for ($x = 1; $x <= $notesData['numParticipant']; $x++) {
                if($notesData['participant_'.$x] != 'undefined'){
                    $this->create_participant($notesData['participant_'.$x],$notesData['participantName_'.$x],$notesData['participantOwner_'.$x], $activityRID, $current_user->id);
                if($notesData['participant_duties_'.$x] && strlen($notesData['participant_duties_'.$x])>0){
                $json = json_decode(stripslashes(utf8_encode(str_replace('&quot;', '"', $notesData['participant_duties_'.$x]))), true);
                foreach($json as $num => $val) {
                    $name_duty =($notesData['subject'] .'_'. $notesData['participantName_'.$x]) .'_duty_'. ($num+1);
                    $this->create_duty($val,$name_duty,$notesData['participant_'.$x], $activityRID, $current_user->id);
                  }
                }
                }

            }
			
            $json_respu = array("module" => "AR_Activity_Report", "id" => $activitiRBean->id);

        return $json_respu;

      }else return "Error";
    }

    public function create_meeting_call($notesData, $id=null, $created_user){
        $time = new \DateTime();
        
        if($notesData['end_date']){
            $start_date=date_format(date_create($notesData['start_date']),"Y-m-d H:i:s");
            $end_date=date_format(date_create($notesData['end_date']),"Y-m-d H:i:s");
            $hours= $notesData['hours']; 
            $minutes = $notesData['minutos'];
        }else{
            $start=date_create($notesData['date_start']);
            date_add($start,date_interval_create_from_date_string($notesData['duration']));
            $end_date=date_format($start,"Y-m-d H:i:s");
            $start_date=date_format(date_create($notesData['date_start']),"Y-m-d H:i:s");
            $pieces = explode(" ", $notesData['duration']);
            $hours= $pieces[0]; 
            $minutes = $pieces[2];
        }
        if($id =="" || $id == null){    
            $mainBean = BeanFactory::newBean($notesData['typeAr']);
            $mainBean->date_entered     = $time->format('Y-m-d H:i:s');
            $mainBean->created_by = $created_user;
        }else {
            $mainBean = BeanFactory::getBean($notesData['typeAr'],$id); 
        } 
        
        $mainBean->date_modified    = $time->format('Y-m-d H:i:s');
        $mainBean->name    = $notesData['subject'];
        $mainBean->description    = $notesData['agenda'];
        $mainBean->duration_hours    = $hours;
        $mainBean->duration_minutes    = $minutes;
        $mainBean->date_start    =  $start_date;
        $mainBean->date_end    =  $end_date;
        $mainBean->parent_id        = $notesData['parent_id'];
        $mainBean->parent_type      = $notesData['parent_type'];
        $mainBean->assigned_user_id = $created_user;
        $mainBean->status = $notesData['status'];

        $mainID = $mainBean->save();

        return $mainID;
    }

    public function CreateActivity($notesData, $id=null, $created_user,$typeId){
        $time = new \DateTime();
        $activitiRBean = BeanFactory::newBean('AR_Activity_Report');
        if($id !== null){
            $activitiRBean->id= $candidate_id;
            $activitiRBean->new_with_id = $candidate_id;
        }
        
        $activitiRBean->date_entered     = $time->format('Y-m-d H:i:s');
        $activitiRBean->created_by       = $created_user; 
        $activitiRBean->date_modified    = $time->format('Y-m-d H:i:s');
        $activitiRBean->assigned_user_id = $created_user;
        $activitiRBean->name    = $notesData['subject'];
        $activitiRBean->description    = $notesData['agenda'];
        $activitiRBean->parent_type    =  $notesData['typeAr'];
        $activitiRBean->parent_id    = $typeId;

        return $activitiRBean->save();
    }

    public function updateActivity(SugarBean $activitiRBean,$notesData, $id=null, $created_user,$typeId){
        $time = new \DateTime();
        $activitiRBean->date_entered     = $time->format('Y-m-d H:i:s');
        $activitiRBean->created_by       = $created_user; 
        $activitiRBean->date_modified    = $time->format('Y-m-d H:i:s');
        $activitiRBean->assigned_user_id = $created_user;
        $activitiRBean->name    = $notesData['subject'];
        $activitiRBean->description    = $notesData['agenda'];
        $activitiRBean->parent_type    =  $notesData['typeAr'];
        $activitiRBean->parent_id    = $typeId;

        return $activitiRBean->save();
    }

    public function create_note($name_note,$description,$notesFile,$meetingID, $created_user, $main){
        $time = new \DateTime();
        $notesBean = BeanFactory::newBean('Notes');
                $notesBean->date_entered     = $time->format('Y-m-d H:i:s');
                $notesBean->date_modified    = $time->format('Y-m-d H:i:s');
                $notesBean->parent_id        = $meetingID;
                $notesBean->parent_type      = $main;
                $notesBean->assigned_user_id = $created_user;

                $notesBean->name        = $notesFile['name'].$name_note;
                $notesBean->description = $description; 
                $flag = 0; 


                if(!empty($notesFile['tmp_name'])){ 
                
                    $name_file_attached = $notesFile['name'];
                    $type_file_attached = $notesFile['type'];
                    $ext_file_attached  = explode('/',$notesFile['type']);
                    $size_file_attached = $notesFile['size'];
                    $tmp_file_attached  = $notesFile['tmp_name'];
                    $new_name_attached  = $meetingID."_".date('Y-m-d_H:m:s').".".end($ext_file_attached);

                    //$path_invoice = $sugar_config['upload_dir']."invoice_attachments/";
                    $path_invoice = $sugar_config['upload_dir'];


                    $attached_file = move_uploaded_file($tmp_file_attached,$path_invoice.$new_name_attached);
                    if($attached_file){
                        chmod($path_invoice.$new_name_attached, 0777);
                        $notesBean->file_mime_type = $type_file_attached;
                        $notesBean->filename = $notesFile['name'];   
                        $flag = 1;                
                    }else{
                        echo "attached error";
                    }   
                    
                }

                $noteID = $notesBean->save();

                if($noteID !== false && intval($flag) == 1){
                    // rename file of folder,to use the suitecrm methods
                    rename($path_invoice.$new_name_attached, $sugar_config['upload_dir'] . $notesBean->id);
                }
    }

    public function create_participant($id, $name, $owner,$activityRID, $created_user){
        $time = new \DateTime();
        $participantsBean = BeanFactory::newBean('AR_Participant');
        $participantsBean->date_entered     = $time->format('Y-m-d H:i:s');
        $participantsBean->date_modified    = $time->format('Y-m-d H:i:s');
        $participantsBean->assigned_user_id = $created_user;
        $participantsBean->created_by = $created_user;
        $participantsBean->parent_type    = 'CC_Employee_Information';
        $participantsBean->parent_id    = $id;
        $participantsBean->name    = $name;
        $participantsBean->is_owner    = $owner;
        

        $participantsID = $participantsBean->save();

        $relation1 = 'ar_activity_report_ar_participant';
        $activityReportRecord = BeanFactory::getBean('AR_Activity_Report',$activityRID);     

        $activityReportRecord->load_relationship($relation1);
        $activityReportRecord->$relation1->add($participantsBean->id);
        $activityReportRecord->save();
    }

    public function edit_participant($id, $name, $owner,$participantID, $created_user){
        $time = new \DateTime();
        $participantsBean = BeanFactory::getBean('AR_Participant',$participantID); 
        $participantsBean->date_entered     = $time->format('Y-m-d H:i:s');
        $participantsBean->date_modified    = $time->format('Y-m-d H:i:s');
        $participantsBean->assigned_user_id = $created_user;
        $participantsBean->created_by = $created_user;
        $participantsBean->parent_type    = 'CC_Employee_Information';
        $participantsBean->parent_id    = $id;
        $participantsBean->name    = $name;
        $participantsBean->is_owner    = $owner;
        $participantsID = $participantsBean->save();
        
    }

    public function create_duty($dutyInfo, $name, $employeeId,$activityRID, $created_user){
        $dueDate=date_create($dutyInfo['dueDate']);
        $time = new \DateTime();

        $dutyBeans = BeanFactory::newBean('AR_Activity_Duty');
        $dutyBeans->date_entered     = $time->format('Y-m-d H:i:s');
        $dutyBeans->date_modified    = $time->format('Y-m-d H:i:s');
        $dutyBeans->assigned_user_id = $created_user;
        $dutyBeans->created_by = $created_user;
        $dutyBeans->parent_type    = 'AR_Activity_Report';
        $dutyBeans->parent_id    = $activityRID;
        $dutyBeans->cc_employee_information_id_c =$employeeId;
        $dutyBeans->name    =$name;
        $dutyBeans->due_date    = (date_format($dueDate,"Y-m-d H:i:s"));
        $dutyBeans->original_estimate    = ($dutyInfo['time']);
        $dutyBeans->description    = ($dutyInfo['Description']);    


        $dutyBeanId = $dutyBeans->save();
    }
   

    public function validateDate($date, $default, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date){
            return $date;
        } else {
            return $default;
        }
    }

    public function GetActivityReport($id_AR){
        $where = " WHERE ar.id ='" . $id_AR . "'";
        $sql =  $this->queryActivityReport . $where ;
    
        $db = DBManagerFactory::getInstance();
        $GetActivityReport = $db->fetchRow($db->query($sql));


         if($GetActivityReport){
            $related2 = $this->bringRelatedTo($GetActivityReport['parent_type']);
            $resultPart = $this->bringParticipantsTo($id_AR);
            $resultNote = $this->bringNotesTo($GetActivityReport['id']);
               
            $results[] = (object) [
                'id'               => $id_AR,
                'idMeet'           => $GetActivityReport['id'],
                'name'             => $GetActivityReport['name'],
                'description'      => $GetActivityReport['description'],
                'dateStr'          => $GetActivityReport['dateStr'],
                'hourStr'          => $GetActivityReport['hourStr'],
                'minuteStr'        => $GetActivityReport['minuteStr'],
                'parent_type'      => $GetActivityReport['parent_type'],
                'duration'         => $GetActivityReport['duration'],
                'status'           => $GetActivityReport['status'],
                'last_start_time'  => $GetActivityReport['last_start_time'],
                'accumulated_time' => $GetActivityReport['accumulated_time'],
                'running_days'     => $GetActivityReport['running_days'],
                'running_hours'    => $GetActivityReport['running_hours'],
                'running_minutes'  => $GetActivityReport['running_minutes'],
                'is_billable'      => $GetActivityReport['is_billable'],
                'parent_id'        => $GetActivityReport['parent_id'],
                'participants'     => $resultPart,
                'partRela'         => $GetActivityReport['partRela'],
                'notes'            => $resultNote,
                'related2'         => $related2,
            ];        
               
          }
   
          return $results;
    }

    function bringParticipantsTo($id){

        $where = " AND arp.ar_activity_report_ar_participantar_activity_report_ida= '".$id."' ";
        $sql =  $this->queryActivityReportParticipants . $where ;
        // Get an instance of the dabatabase manager
        $db = DBManagerFactory::getInstance();

        // Perform the query
        $rows = $db->query($sql);
        $resultAll = [];

        $whereEmp = " AND ar.parent_id ='".$id."'";
            $sqlEmp =  $this->queryDutiesParticipants . $whereEmp ;
            $dbEmp = DBManagerFactory::getInstance();
            $rowsEmp = $dbEmp->query($sqlEmp);
            
            while ($rowEmp = $db->fetchByAssoc($rowsEmp)) {
                if(!$duties[$rowEmp['cc_employee']]){
                $duties[$rowEmp['cc_employee']][]= (object) $rowEmp;    
                }else{
                    array_push($duties[$rowEmp['cc_employee']],(object) $rowEmp);
                }
            }

        while ($row = $db->fetchRow($rows)) {
            $result = [];
            $result['id'] = $row['id'];
            $result['participant'] = $row['participant'];
            $result['idParRel'] = $row['idParRel'];
            $result['name'] = $row['name'];
            $result['is_owner'] = $row['is_owner'];
             
            $result['duties'] =$duties[$row['participant']];
            $resultAll[] = $result;
        }

        return $resultAll;
    }

    function bringNotesTo($id){
        $beanNote = BeanFactory::getBean('Notes');
        $where = "notes.parent_id = '".$id."'";
        $list =  $beanNote->get_full_list('', $where ,false, 0);
        $result = [];
        foreach ($list as $item){
            $result[] = (object) [
                "id" => $item->id,
                "name" => $item->filename,
                "description" => $item->description,
            ];
        }
        return $result;
    }

    public function userTZDiff(){
        global $current_user;
        global $timedate;

        $user_timezone = new DateTimeZone($current_user->getPreference('timezone'));
        $gmt_timezone = new DateTimeZone('GMT');

        $CurrentBDDateTime = $timedate->getInstance()->nowDb();

        $user_datetime = new DateTime('now', $user_timezone);
        $gmt_datetime = new DateTime($CurrentBDDateTime, $gmt_timezone);

        $offset_in_seconds = $user_timezone->getOffset($user_datetime) - $gmt_timezone->getOffset($gmt_datetime);
        $offset_in_hours = $offset_in_seconds / 3600;
        return $offset_in_hours;

    }

    private function pauseUserRunningActivities($activity_report_id){
        global $current_user;
        $result = false;
        if(!empty($current_user->id)){
            $db = DBManagerFactory::getInstance();
            $sql = $this->queryStopAllUserRunnningActivities.sprintf(
                " where status='running' and created_by = '%s' and id!='%s'",
                $current_user->id,
                $activity_report_id
            );
            // Perform the query
            $result = $db->query($sql);
        }
        return $result;
    }

    public function setActivityRunning(SugarBean $activity_report){
        global $timedate;

        if($activity_report->status !== 'running'){
            $TimeZoneNameFrom="UTC";
            $nowdate = date('Y-m-d H:i:s', strtotime($timedate->now()));
            if(!empty($activity_report->accumulated_time) && intval($activity_report->accumulated_time)>0){
                $nowdate = date('Y-m-d H:i:s',
                    strtotime($timedate->now()."- ".intval($activity_report->accumulated_time)." minute"));
                $activity_report->accumulated_time = 0;
            }
            $this->pauseUserRunningActivities($activity_report->id);
            $activity_report->status = 'running';
            $activity_report->last_start_time = $nowdate;
            return $activity_report->save();
        }

        return false;
    }

    public function setActivityPaused(SugarBean $activity_report){
        global $current_user;
        $result = false;
        if($activity_report->status !=='running'){
            return [
                "result"=>false,
                "msg"=> "Only running activities can be paused"
            ];
        }
        if(!empty($current_user->id)){
            $db = DBManagerFactory::getInstance();
            $sql = $this->queryStopAllUserRunnningActivities.sprintf(
                    " where status='running' and created_by = '%s' and id='%s'",
                    $current_user->id,
                    $activity_report->id
                );
            // Perform the query
            $result = $db->query($sql);
        }
        return $result;
    }

    public function setActivityCompleted(SugarBean $activity_report){

        $total_extra_time = $activity_report->accumulated_time;

        if($activity_report->status ==='completed'){
            return [
                "result"=>false,
                "msg"=> "This activity has already been marked as complete."
            ];
        }

        if($total_extra_time>480){
            $total_extra_time = 480;
        }

        $duration_hour = intdiv($total_extra_time,60);
        $duration_minutes =  $total_extra_time % 60;

        if($duration_minutes>15){
            $duration_minutes = round($duration_minutes/15,0)*15;
            if($duration_minutes==60){
                $duration_minutes =0;
                $duration_hour++;
            }
        }

        $meetingBean = BeanFactory::getBean($activity_report->parent_type,$activity_report->parent_id);
        if($meetingBean instanceof SugarBean) {
            if(($meetingBean->duration_minutes + $duration_minutes)>=60){
                $duration_hour++;
                $duration_minutes = $duration_minutes-60;
            }
            $meetingBean->duration_minutes = $meetingBean->duration_minutes + $duration_minutes;
            $meetingBean->duration_hours = $meetingBean->duration_hours + $duration_hour;

            $meetingBean->status = 'Held';
            if($meetingBean->save()){
                $activity_report->status = 'completed';
                $activity_report->accumulated_time = 0;
                $activity_report->last_start_time = null;
                return $activity_report->save();
            }
        }
        return false;
    }

    public function UpdateActivityStatus($elementId,$activity_action)
    {
        global $current_user;
        $result_array = ["result"=>false,"msg"=>"Operation not allowed"];
        $actionFunctions = [
            'play' => function ($element) {
                return self::setActivityRunning($element);
            },
            'pause' => function ($element) {
                return self::setActivityPaused($element);
            },
            'stop' => function ($element) {
                global $current_user;
                if($element->status === 'running'){
                    $db = DBManagerFactory::getInstance();
                    $sql = $this->queryStopAllUserRunnningActivities.sprintf(
                            " where status='running' and created_by = '%s' and id='%s'",
                            $current_user->id,
                            $element->id
                        );
                    $result = $db->query($sql);
                        $activity_report_bean = BeanFactory::getBean($this->object_name);
                        $list = $activity_report_bean->get_full_list("", "ar_activity_report.id = '".$element->id."'");
                        if(count($list)) {
                            $element = $list[0];
                        }
                }
                return self::setActivityCompleted($element);
            },
        ];

        if(ACLController::checkAccess($this->object_name, 'edit', true,'module',  true)){
            $activity_report = BeanFactory::getBean($this->object_name,$elementId);
            if( ($activity_report && ($activity_report->created_by == $current_user->id) ||
                ($activity_report->assigned_user_id == $current_user->id))){
                $result = $actionFunctions[$activity_action]($activity_report);
                if(is_array($result)){
                    $result_array = $result;
                } else {
                    $result_array = [
                        "result"=>boolval($result),
                        "msg"=> ($result)?"Record Updated":"There was an error processing your request"
                    ];
                }
            }
        }
        return $result_array;
    }

    public function deleteParticipant($idParRel,  $id = null)
    {
        $relationName = "ar_activity_report_ar_participant";
        $parentBean = BeanFactory::getBean('AR_Participant', $idParRel);
        $parentBean->load_relationship($relationName);
        $parentBean->$relationName->delete($id);
        $parentBean->mark_deleted($idParRel);
        $parentBean->save();

        return $id;
    }


    public function deleteFile($id)
    {
        global  $sugar_config;
        $parentBean = BeanFactory::getBean('Notes', $id);
        $path_invoice = $sugar_config['upload_dir'];
        unlink($path_invoice . "/" .$id);
        $parentBean->mark_deleted($id);
        $parentBean->save();

        return $id;
    }

    public function deleteDuty($id)
    {
        global  $sugar_config;
        $parentBean = BeanFactory::getBean('AR_Activity_Duty', $id);
        $parentBean->mark_deleted($id);
        $parentBean->save();
        return $id;
    }

    public function UpdateDuty($id, $notesData)
    {
        global  $sugar_config;
        $time = new \DateTime();
        $parentBean = BeanFactory::getBean('AR_Activity_Duty', $id);
        $parentBean->description = $notesData['description'];
        $parentBean->due_date = $notesData['due_date'];
        $parentBean->original_estimate = $notesData['time'];
        $parentBean->date_modified    = $time->format('Y-m-d H:i:s');
        $parentBean->save();
        return $id;
    }

    public function AddNewDuty($id, $notesData)
    {
        $time = new \DateTime();
        global $current_user;
        $dueDate=date_create($notesData['due_date']);
        srand (time());
        $numero_aleatorio = rand(1,10);
        $dutyBeans = BeanFactory::newBean('AR_Activity_Duty');
      

        $dutyBeans->date_entered     = $time->format('Y-m-d H:i:s');
        $dutyBeans->date_modified    = $time->format('Y-m-d H:i:s');
        $dutyBeans->assigned_user_id = $current_user->id;
        $dutyBeans->created_by = $current_user->id;
        $dutyBeans->parent_type    = 'AR_Activity_Report';
        $dutyBeans->parent_id    = $notesData['id'];
        $dutyBeans->cc_employee_information_id_c    =$notesData['participant'];
        $dutyBeans->name    = ($notesData['subject'] .'_'. $notesData['participant']) .'_duty_'. ($numero_aleatorio);
        $dutyBeans->due_date    = (date_format($dueDate,"Y-m-d H:i:s"));
        $dutyBeans->original_estimate    = ($notesData['time']);
        $dutyBeans->description    = ($notesData['description']);

        $dutyBeanId = $dutyBeans->save();
        return $dutyBeanId;
    }

    public function getOwner()
    {
        global $current_user;  
        $beanEmployee     = BeanFactory::getBean('CC_Employee_Information');
        $getEmployee   = $beanEmployee->retrieve_by_string_fields(array('current_email' => $current_user->email1));
        if($getEmployee){
            $result[] = (object) [
                "id" => $getEmployee->id,
                "name" => $getEmployee->name
            ];
        }else{
            $result[] = (object) [
                "id" => 0,
                "name" => ''
            ];
        }
        return $result;
    }


    public function saveActivityRecord($notesData, $employee_id=null, $activity_id=null){
        global  $sugar_config;
       $arrayData=get_object_vars($notesData);

        $activitiRBean = BeanFactory::getBean('AR_Activity_Report',$activity_id);

        if ($activity_id != '' && $activitiRBean){

        $meetingID=$this->create_meeting_call($arrayData, $activitiRBean->parent_id ,$employee_id);
     
        $activityRID = $this->updateActivity($activitiRBean,$arrayData,null, $employee_id,$meetingID);

        $relation1 = 'ar_activity_report_ar_participant';

        $activitiRBean->load_relationship($relation1);
        $participants = $activitiRBean->$relation1->getBeans();
        $existsId= array();
        foreach($participants as $participant){
            $existsId[$participant->id]=$participant->parent_id;
        }

        foreach ($notesData->participants as $participant) {
            $created_user = $employee_id;
            $id    = $participant->id;
            $name    = $participant->name;
            $owner    = $participant->owner == true ? 1 : 0;

            if(($key = array_search($participant->id, $existsId)) !== false){
                unset($existsId[$key]);
                $this->edit_participant($id, $name, $owner,$key, $created_user);
            }else{
                $this->create_participant($id, $name, $owner,$activityRID, $created_user);

            }
        }
        foreach ($existsId as $key => $value){
            $this->deleteParticipant($key);
            $whereEmp = " AND ar.parent_id ='".$activity_id."' AND ar.cc_employee_information_id_c ='".$value."'";
            $sqlEmp =  $this->queryDutiesParticipants . $whereEmp ;
            $dbEmp = DBManagerFactory::getInstance();
            $rowsEmp = $dbEmp->query($sqlEmp);
                while ($rowEmp = $dbEmp->fetchByAssoc($rowsEmp)) {
                    $this->deleteDuty($rowEmp['ar_id']);
                }
            
        }

        }else{

        $meetingID=$this->create_meeting_call($arrayData, null ,$employee_id);

        if($meetingID !== false){
    
            $activityId = is_null($activity_id) ? (is_null($notesData->id)? null: $notesData->id): $activity_id;
            $activityRID = $this->CreateActivity($arrayData,$activityId, $employee_id,$meetingID);
            

            foreach ($notesData->participants as $participant) {
                $created_user = $employee_id;
                $id    = $participant->id;
                $name    = $participant->name;
                $owner    = $participant->owner == true ? 1 : 0;
                $this->create_participant($id, $name, $owner,$activityRID, $created_user);

            }

        }
    }
    return $activityRID;

    }

   


}
