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

/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CT_ActivityViewList extends SugarView
{
    /**
     * @see SugarView::$type
     */
    public $type = 'list';
    private $actualSelectedProject = null;
    private $from = null;
    private $to = null;
    public static $DEFAULT_VIEW = "ProjectActivitySummary.tpl";
    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function __construct()
    {
        parent::__construct();
        $default = array("options" => array(
            "default" => null
        ));

        $this->actualSelectedProject = filter_input(INPUT_GET, 'project_id', FILTER_DEFAULT, $default);
        $this->from = filter_input(INPUT_COOKIE, 'careers_activity_from', FILTER_DEFAULT, $default);
        $this->to = filter_input(INPUT_COOKIE, 'careers_activity_to', FILTER_DEFAULT, $default);

    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        $available_views = [
            'list-type' => ['title'=>'Registered Activities','template'=>'ListViewHandler.tpl'],
            'project-type' => ['title'=>'Graph View','template'=>'ProjectViewHandler.tpl'],
            'project-dashboard' => ['title'=>'Projects Activity Summary','template'=>'ProjectActivitySummary.tpl'],
            'project-details' => ['title'=>'Project Details','template'=>'ProjectDetailsSummary.tpl']
        ];

        $user_selected_view = self::$DEFAULT_VIEW;
        $user_selected_title = 'Registered Activities';
        if(isset($_COOKIE) && key_exists("careers-activity-view-selected",$_COOKIE)) {
            $current_selected_view = $_COOKIE["careers-activity-view-selected"];
            if(key_exists($current_selected_view,$available_views)){
                $user_selected_view = $available_views[$current_selected_view]['template'];
                $user_selected_title = $available_views[$current_selected_view]['title'];
            }
        }

        $html = sprintf('<div class="moduleTitle"><h2 class="module-title-text">&nbsp;%s</h2><div class="clear"></div></div>',$user_selected_title);
        echo $html;

        $smarty = new Sugar_Smarty();
        $smarty->display('modules/CT_Activity/tpls/ListViewSelector.tpl');

        $this->displayListViewHandler($user_selected_view);
    }

    public function displayListViewHandler($default_view) {
        global $current_user;
        $month_before = strtotime("-2 months");

        $dateStart = (is_null($this->from))?date('Y-m-d', $month_before):$this->from;
        $dateEnd = (is_null($this->to))?date('Y-m-d'):$this->to;

        $smarty = new Sugar_Smarty();
        $smarty->assign('activity_from', $dateStart);
        $smarty->assign('activity_to', $dateEnd);

        if($default_view == self::$DEFAULT_VIEW){
            $smarty->assign('BEANID', $this->bean->id);
            $grid = (new CT_ActivityController())->GetActivities();
            $smarty->assign('GRID', $grid);
        }

        $this->actualSelectedProject = (key_exists('record', $_REQUEST))?$_REQUEST['record']:null;

        if($default_view == 'ProjectDetailsSummary.tpl'){
            $smarty->assign('BEANID', $this->bean->id);
            $activity_controller = new CT_ActivityController();
            $employees_data = $activity_controller->reportedEmployees($this->actualSelectedProject, $dateStart, $dateEnd);
            $project_list = $activity_controller->getOptionsProjects();
            $userHasRateAccess = ACLController::checkAccess('CI_WorkType_Rates', 'view', $current_user->id);
            $smarty->assign('base_data',json_encode($employees_data));
            $smarty->assign('actual_project',$this->actualSelectedProject);
            $smarty->assign('project_list',$project_list);
            $smarty->assign('userHasRateAccess',$userHasRateAccess?'true':'false');
            $smarty->assign('AssignedResources', count($employees_data['employee_data']));
            $smarty->assign('TotalActivities', $employees_data['total_activities']);
            $smarty->assign('TotalTime', $employees_data['total_time']);
        }

        $smarty->display('modules/CT_Activity/tpls/'.$default_view);
    }

}
