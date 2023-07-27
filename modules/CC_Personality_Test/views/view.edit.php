<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CC_Personality_TestViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        if (!$this->bean->ACLAccess('view') || !$this->bean->ACLAccess('save')) {
            $noaccessView = new ViewNoaccess();
            $noaccessView->display();
            sugar_die('');
        }
        parent::display();
        $this->hideExcessRelation();
    }
    
    
    public function hideExcessRelation() 
    {
        if($_REQUEST["return_module"] === "CC_Candidate"){
            print '<script>$("div[data-label=LBL_CC_EMPLOYEE_INFORMATION_CC_PERSONALITY_TEST_FROM_CC_EMPLOYEE_INFORMATION_TITLE]").css("display", "none");</script>';
            print '<script>$("[field=cc_employee_information_cc_personality_test_name]").css("display", "none");</script>';
        }
        if($_REQUEST["return_module"] === "CC_Employee_Information"){
            print '<script>$("div[data-label=LBL_CC_CANDIDATE_CC_PERSONALITY_TEST_FROM_CC_CANDIDATE_TITLE]").css("display", "none");</script>';
            print '<script>$("[field=cc_candidate_cc_personality_test_name]").css("display", "none");</script>';
        }
    }


}