<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'custom/application/Ext/Api/V8/Config/common.php';
require_once 'modules/CC_Skill/CC_SkillCC_ProfileRelationship.php';


$errorLevelStored = error_reporting();
error_reporting(0);
error_reporting($errorLevelStored);


use Api\V8\Config\Common as Common;
use Api\V8\Utilities;

class CC_QuestionsController extends SugarController{

    private $queryQuestionSkill =
    "SELECT *,q.id as id_q FROM cc_questions q LEFT JOIN cc_skill_cc_questions_c sq ON q.id=sq.cc_skill_cc_questionscc_questions_idb
    WHERE q.deleted = 0 and sq.deleted = 0";

public function __construct(){
    parent::__construct();
}




public function reorderRecords($id){

    $whereEmp = " AND sq.cc_skill_cc_questionscc_skill_ida ='".$id."'";
    $sqlEmp =  $this->queryQuestionSkill . $whereEmp ;
    $dbEmp = DBManagerFactory::getInstance();
    $rowsEmp = $dbEmp->query($sqlEmp);
    $order = 5;
    
    while ($rowEmp = $dbEmp->fetchByAssoc($rowsEmp)) { 
        $parentBean = BeanFactory::getBean('CC_Questions', $rowEmp['id_q']);
        $parentBean->question_order = $order;
        $parentBean->save();
        $order = $order+5;
    }

}


public function give_order_questions($id, $order){

    $parentBean = BeanFactory::getBean('CC_Questions', $id);
    $parentBean->question_order = $order;
    $results=$parentBean->save();

    return $results;

}




}