<?php
class JobDescriptionProfileDifferenceHandler
{

    private ?string $idJodDescription = null;
    private ?string $idRR = null;
    
    
    public function __construct($idJodDescription,$idRR){
        $this->idJodDescription = $idJodDescription;
        $this->idRR = $idRR;
    }

    public function computesDifferenceProfile(){

        $RRBean = BeanFactory::getBean('CC_Recruitment_Request',$this->idRR);
        $RRBean->load_relationship("cc_recruitment_request_cc_profile");
        $RRBean->load_relationship("cc_recruitment_request_cc_job_description"); 
        $JobDescriptionRR = current($RRBean->cc_recruitment_request_cc_job_description->getBeans());
    
        if($JobDescriptionRR->id == $this->idJodDescription){
            return;
        }
    
        $idsProfilesJobDesOld = $this->GetProfileOfJobDescription($JobDescriptionRR->id);

        if($idsProfilesJobDesOld){ 
            foreach ($idsProfilesJobDesOld as $key => $value) {
                //$RRBean->cc_recruitment_request_cc_profile->delete($value,$RRBean);
                $deleted = "UPDATE cc_recruitment_request_cc_profile_c SET deleted = 1 WHERE cc_recruitment_request_cc_profilecc_recruitment_request_ida = '".$this->idRR."' AND cc_recruitment_request_cc_profilecc_profile_idb = '".$value."';";
                $db = DBManagerFactory::getInstance();
                $db->query($deleted);
            }
        }
        $idsProfilesJobDesNew = $this->GetProfileOfJobDescription($this->idJodDescription);
        $idsProfilesRR = $RRBean->cc_recruitment_request_cc_profile->get();
        $newProfileInsert = array_diff($idsProfilesJobDesNew,$idsProfilesRR);
        if($newProfileInsert){ 
            foreach ($newProfileInsert as $key => $value) {
                $RRBean->cc_recruitment_request_cc_profile->add($value);
            }
        }


    }


    public function getDifferenceProfile(){

        $RRBean = BeanFactory::getBean('CC_Recruitment_Request',$this->idRR);
        $RRBean->load_relationship("cc_recruitment_request_cc_profile");
        $RRBean->load_relationship("cc_recruitment_request_cc_job_description"); 
        $JobDescriptionRR = current($RRBean->cc_recruitment_request_cc_job_description->getBeans());
    
        $idsProfilesJobDesRR = $this->GetProfileOfJobDescription($JobDescriptionRR->id);
        $idsProfilesRR = $RRBean->cc_recruitment_request_cc_profile->get();
        $newProfileInsert = array_diff($idsProfilesRR,$idsProfilesJobDesRR);
        
        if($newProfileInsert){ 
           return 1;
        }else{
           return 0;
        }


    }


    public function GetProfileOfJobDescription($idJobDescription){

        $JobDescriptionBeanOld = BeanFactory::getBean('CC_Job_Description',$idJobDescription);
        $JobDescriptionBeanOld->load_relationship("cc_job_description_cc_profile");
        $idsProfilesJobDesOld = $JobDescriptionBeanOld->cc_job_description_cc_profile->get();
        return $idsProfilesJobDesOld;
    }





}