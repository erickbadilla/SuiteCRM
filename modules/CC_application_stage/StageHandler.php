<?php

class StageHandler {
    private static $baseModule = 'CC_application_stage';
    private $bean;
    private $list;
    private $listIds;
    private $actualStage;
    private $actualIndex;
    private $relatedStages;
    private $relatedStagesIds;

    /**
     * @param null $beanId
     */
    public function __construct($beanId = null, $type = null)
    {
        $this->bean = (is_null($beanId))?
            BeanFactory::newBean(self::$baseModule):
            BeanFactory::getBean(self::$baseModule, $beanId);
        $applicationType = (!is_null($type)) ? $type : $this->bean->type;
        $this->list = (!is_null($this->bean->id))? self::getList($applicationType):self::getList();
        $this->listIds = array_map(function($value){ return $value->id; },$this->list);

        if(!is_null($this->bean->id)){
            $this->actualStage = $this->bean;
        } else {
            $firstStage = array_slice($this->list, 0, 1);
            $this->actualStage = array_shift($firstStage);
        }
        $this->actualIndex = $this->findStageIndex();
        $this->relatedStages = [];
        $this->relatedStagesIds = [];
    }

    /**
     * @return false|int|string
     */
    private function findStageIndex(){
        foreach ( $this->list as $key => $element ) {
            if ( $this->actualStage->id == $element->id ) {
                return $key;
            }
        }
        return false;
    }

    /**
     * @param SugarBean $application
     */
    public function setApplication(SugarBean $application){
        $this->relatedStages = $application->get_linked_beans('cc_job_applications_cc_application_stage');
        $this->relatedStagesIds = array_map(function ($value){ return $value->id; },$this->relatedStages);
    }

    /**
     * @param $stageId
     * @return bool
     */
    public function isStageCompleted($stageId): bool
    {
        return in_array($stageId,$this->relatedStagesIds);
    }

    /**
     * @param $value
     * @return string
     */
    private function qouteIt($value){
        $format = "'%s'";
        return sprintf($format,$value);
    }

    /**
     * @param string $type
     * @return array|mixed
     */
    public function getList($type = "EXTERNAL"){
        $type = self::qouteIt($type);
        $actualStages = $this->bean->get_full_list( "stageorder asc", " cc_application_stage.type = $type", false,false);

        return $actualStages;
    }

    /**
     * @return bool|mixed|SugarBean|null
     */
    public function getActualStage(){
        return $this->actualStage;
    }

    /**
     * @return false|mixed
     */
    public function getNextStage(string $targetStageId = ""){
        if(!empty($targetStageId)){
            $returnStage = array_filter($this->list,function($obj) use ($targetStageId) {
                return ($obj->id == $targetStageId);
            });
            return (is_array($returnStage) && count($returnStage)>0)? current($returnStage) : false;
        } else {
            $next = $this->actualIndex+1;
            if(key_exists($next,$this->list)){
                return $this->list[$next];
            }
        }
        return false;
        /*$diffStages = array_diff($this->listIds,$this->relatedStagesIds);
        if(count($diffStages)){
            $nextStageId = current($diffStages);
            $returnStage = array_filter($this->list,function($obj) use ($nextStageId) {
                return ($obj->id == $nextStageId);
            });
            return $returnStage;
        }
        return false;*/
    }

    /**
     * @return false|mixed|SugarBean
     */
    public function getPreviousStage(){
        $previous = $this->actualIndex-1;
        if(key_exists($previous,$this->list)){
            return $this->list[$previous];
        }
        return false;
    }


}