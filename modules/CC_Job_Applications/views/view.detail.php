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
require_once "modules/CC_application_stage/StageHandler.php";
require_once 'modules/CC_Profile/controller.php';
require_once 'modules/CC_Job_Applications/controller.php';
require_once 'custom/include/careersRequests.php';
/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CC_Job_ApplicationsViewDetail extends ViewDetail
{
    /**
     * @see SugarView::$type
     */
    public $type = 'detail';

    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        if (!$this->bean->ACLAccess('view')) {
            $noaccessView = new ViewNoaccess();
            $noaccessView->display();
            sugar_die('');
        }
        if (empty($this->bean->id)) {
            sugar_die($GLOBALS['app_strings']['ERROR_NO_RECORD']);
        }

        $this->displayCreateDetailApplication();
        $this->displayRelatedNote();
        $this->displayInterviewResults();
    }


    public function displayCreateDetailApplication() {

        $templateDir = 'modules/CC_Job_Applications/tpls/';
        $smarty = new Sugar_Smarty();
        $stageHandler = new StageHandler();
        $type = $this->bean->application_type;
        $listStages = $stageHandler->getList($type);

        $candidateAvailabilityData = $this->bean->getCandidateRelatedAvailabilityInfo();
        $jobOfferInterviewersData = $this->bean->getJobOfferRelatedInterviewersInfo();

        $JobDescription = BeanFactory::getBean('CC_Job_Description');
        $JobDescriptionFields = $JobDescription->get_full_list();
        $result_job_description = array();
        foreach($JobDescriptionFields as $key => $item){
              $array = array();
              $array['id_job_description']   = $JobDescriptionFields[$key]->id;
              $array['name_job_description'] = $JobDescriptionFields[$key]->name;
              $result_job_description[] = $array;     
        }
        if(is_null($_REQUEST["candidateId"] )){
            $jobApp = new CC_Job_ApplicationsController();
            $jobInfo = $jobApp->getJobInformation($this->bean->id);        

        $smarty->assign('CANDIDATEID', $jobInfo['candidate']);
        $smarty->assign('JOBOFFERID', $jobInfo['jobOffer']);

        }else{
            $smarty->assign('CANDIDATEID', $_REQUEST["candidateId"]);
            $smarty->assign('JOBOFFERID', $_REQUEST["jobOfferId"]);
        }

        $smarty->assign('TYPE', $type);
        $smarty->assign('STAGES', $listStages ?? [] );
        $smarty->assign('TEMPLATEDIR', $GLOBALS['BASE_DIR'].'/'.$templateDir );
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('MODULE', $this->module);
        $smarty->assign('CANDIDATEAVAILABILITY', $candidateAvailabilityData);
        $smarty->assign('INTERVIEWERS', $jobOfferInterviewersData);
        $smarty->assign('JOBDESCRIPTION', $result_job_description);
        $smarty->display($templateDir.'detail.tpl');
    }

    public function displayRelatedNote() {

        $smarty = new Sugar_Smarty();
        $stageHandler = new StageHandler();
        $type = $this->bean->application_type;
        $listStages = $stageHandler->getList($type);
        
        $smarty->assign('STAGES', $listStages ?? [] );
        $smarty->display('modules/CC_Job_Applications/tpls/RelatedNotes.tpl');
    }

    public function displayInterviewResults() {

        $smarty = new Sugar_Smarty();
        $smarty->display('modules/CC_Job_Applications/tpls/InterviewResults.tpl');
    }

}
