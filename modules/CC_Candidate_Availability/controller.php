<?php


if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';


class CC_Candidate_AvailabilityController extends SugarController{

    private static $customModuleName = 'CC_Candidate_Availability';

    public function __construct(){
        parent::__construct();
       
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_Candidate_AvailabilityController()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
            
        }
        self::__construct();
       
    }

    /**
    * Get the Candidate availabilities associated to a candidate
    * @param SugarBean $parentBean
    * @param string $candidateId
    * @return array
    */
    public function getRecordsByCandidateId(\SugarBean $parentBean){
        $candidateAvailabilityRelation = 'cc_candidate_availability_cc_candidate';

        $parentBean->load_relationship($candidateAvailabilityRelation);
        $candidateAvailabilityIds = $parentBean->$candidateAvailabilityRelation->get();
        $result = $this->getRecordsByIds($candidateAvailabilityIds);

        return $result;
    }

    /**
    * Get the Candidate availabilities records
    * @param array $arrIds
    * @return array
    */
    public function getRecordsByIds(array $arrIds){

        $result = [];
        
        foreach($arrIds as $candidateAvailabilityId){
            $bean = BeanFactory::getBean(self::$customModuleName, $candidateAvailabilityId);    
            if($bean) {
                $candidateAvailability['Time1'] = empty($bean->time_1) ? date('H:i:s.u', strtotime("12:00am")) : date('H:i:s.u', strtotime($bean->time_1));
                $candidateAvailability['Time2'] = empty($bean->time_2) ? date('H:i:s.u', strtotime("12:00am")) : date('H:i:s.u', strtotime($bean->time_2));
                $candidateAvailability['Day'] = $bean->daypick;
                $candidateAvailability['Id'] = $bean->id;
    
                $result[] = $candidateAvailability;
            }
        }

        return $result;
    }

    /**
    * Save the Candidate Availability record
    * @param object $candidateAvailability
    * @return SugarBean
    */

    public function saveCandidateAvailabilityRecord(object $candidateAvailability, \SugarBean $candidateBean, $availability_id=null) {
        
        $candidateAvailabilityBean = null;
        $validDays = $this->validateDays($candidateAvailability->Day);
        $validTime = $this->validateTime($candidateAvailability->Time1, $candidateAvailability->Time2);

        if($validDays && $validTime){
            $candidateAvailabilityBean = BeanFactory::newBean(self::$customModuleName);
            $candidateAvailabilityBean->time_1 = $candidateAvailability->Time1;
            $candidateAvailabilityBean->time_2 = $candidateAvailability->Time2;
            $candidateAvailabilityBean->daypick = $candidateAvailability->Day;
            $candidateAvailabilityBean->name = $candidateAvailability->Name;
            
            if($availability_id){
                $candidateAvailabilityBean->id = $availability_id;
                $candidateAvailabilityBean->new_with_id = $availability_id;
            }

            try {
                $this->check_duplicates($candidateAvailabilityBean, $candidateBean);
                $candidateAvailabilityBean->save();
            } catch (\Exception $exception) {
                $candidateAvailabilityBean = null;
            }
        }

        return $candidateAvailabilityBean;
    }

    /**
    * Validate that the day is valid (between 0 and 6)
    * @param string $day
    * @return boolean
    */
    public function validateDays(string $day){
        $isValid = false;
        if(is_numeric($day)) {
            $intday = (int)$day;
            if((0<=$intday) && ($intday<=6)){
                $isValid = true;
            }
        }
        return $isValid;
    }

    /**
    * Validate that the times are valid
    * and that the end time is grater than the start time
    * @param string $time1
    * @param string $time2
    * @return boolean
    */
    public function validateTime(string $time1, string $time2){
        $isValid = false;
        $startTime = strtotime($time1);
        $endTime = strtotime($time2);
        if($startTime && $endTime && ($startTime < $endTime)){
            $isValid = true;
        }
        return $isValid;
    }

    /**
    * Check if the time range has been used already
    * @param SugarBean $availabilityBean
    * @param SugarBean $candidateBean
    */
    function check_duplicates(\SugarBean $availabilityBean, \SugarBean $candidateBean = null){

        $TimeZoneNameTo=TimeDate::userTimezone($user);
        date_default_timezone_set($TimeZoneNameTo);
        $TimeZoneNameFrom="UTC";

        $time_1 = strtotime(date_create($availabilityBean->time_1, new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i:s"));
        $time_2 = strtotime(date_create($availabilityBean->time_2, new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i:s"));
        


        $candidateAvailabilityRelation = 'cc_candidate_availability_cc_candidate';

        $result = false;
        
        if(!isset($candidateBean)){
            if($_REQUEST["return_module"] == "CC_Candidate_Availability") {
                $availabilityBean->load_relationship($candidateAvailabilityRelation);
                $candidateId = $availabilityBean->$candidateAvailabilityRelation->get()[0];
            } else {
                $candidateId = $_REQUEST["relate_id"];
            }
    
            $candidateBean = BeanFactory::getBean('CC_Candidate', $candidateId);
        }
        $candidateAvailability = $this->getRecordsByCandidateId($candidateBean);      
        
        foreach($candidateAvailability as $availability) {
            $atime_1 = strtotime($availability["Time1"]);
            $atime_2 = strtotime($availability["Time2"]);


            // Verify if the availability record id is different from the bean to 
            // check if is an update on the same record. Also make the additional
            // validations.

            if(($availabilityBean->id != $availability["Id"])
                &&($availabilityBean->daypick==$availability["Day"])
                && (($time_1 <= $atime_1 && $time_2> $atime_1) 
                || ($time_1 >= $atime_1 && $time_1 < $atime_2 )
                )) {
                $result = true;
            }
        }
        if($result) {
            throw new \InvalidArgumentException('Time range duplicated');
        }
    }

    public function ceilMinutes($hour, $minute){
        if($minute > 52 && $minute < 59 ){
            $time = ($hour+1).":00:00";
        }
        if($minute < 8 ) {
            $time = $hour.':00:00';
        }else if( $minute < 22 && $minute > 7 ) {
            $time = $hour.':15:00';
        }else if( $minute < 38 && $minute > 21 ) {
            $time = $hour.':30:00';
        }else if( $minute < 51 && $minute > 39 ){
            $time = $hour.':45:00';
        }
        return $time;
    }

    public function saveCandidateAvailability() {

        
        $validDays = $this->validateDays($_REQUEST['daypick']);
        $firsttime= explode(":", $_REQUEST['time_1']);
        $secondtime= explode(":", $_REQUEST['time_2']);

        $time1 = $this->ceilMinutes($firsttime[0], filter_var($firsttime[1], FILTER_SANITIZE_NUMBER_INT));
        $time2 = $this->ceilMinutes($secondtime[0], filter_var($secondtime[1], FILTER_SANITIZE_NUMBER_INT));

        $validTime = $this->validateTime($time1 , $time2);
        if($validDays && $validTime){

            $candidateAvailabilityBean = BeanFactory::newBean(self::$customModuleName);
            $candidateAvailabilityBean->time_1 = $time1;
            $candidateAvailabilityBean->time_2 = $time2;
            $candidateAvailabilityBean->timezone = $_SESSION['timezone'];
            $candidateAvailabilityBean->daypick = $_REQUEST['daypick'];


            try {
                $this->check_duplicates($candidateAvailabilityBean);
                $candidateAvailabilityBean->save();
                SugarApplication::redirect("index.php?action=DetailView&module={$_REQUEST["return_module"]}&record={$_REQUEST["return_id"]}");
            } catch (\Exception $exception) {
                $candidateAvailabilityBean = null;
            }
        }

        return $candidateAvailabilityBean;
    }


}

if(isset($_REQUEST['button']) && $_REQUEST['button'] == "Save"){
    $obj = (new CC_Candidate_AvailabilityController)->saveCandidateAvailability();
    
}