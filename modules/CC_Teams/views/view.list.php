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
require_once('modules/CC_Teams/controller.php');
/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CC_TeamsViewList extends ViewList
{
    /**
     * @see SugarView::$type
     */
    public $type = 'list';
    public $module = 'CC_Teams';

    private $controller;

    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function __construct()
    {
        parent::__construct();
        /*$teamsBean = BeanFactory::getBean('CC_Teams');
        parent::init($teamsBean);*/
        $this->controller = new CC_TeamsController();
    }


    public function preDisplay()
    {
        parent::preDisplay();
        $teams = $this->controller->getTeams();
        $smarty = new Sugar_Smarty();
        $smarty->assign('teams', $teams);
        $view = $smarty->fetch('modules/CC_Teams/tpls/Header.tpl');
        echo $view;
    }

    public function display()
    {
        if (!$this->bean->ACLAccess('view')) {
            $noaccessView = new ViewNoaccess();
            $noaccessView->display();
            sugar_die('');
        }
        $mapNames = function($elem){
            return $elem->name;
        };
        if(isset($_COOKIE) && key_exists("careers-teams-view-selected",$_COOKIE)) {
            $isKanban = ($_COOKIE["careers-teams-view-selected"]=="kanban-type");
            $isPM = ($_COOKIE["careers-teams-view-selected"]=="project-manager-type");
            $isTL = ($_COOKIE["careers-teams-view-selected"]=="technical-lead-type");
        }

        $kanban_types = $this->controller->getKanbanTypes();
        $employee_list = $this->controller->getEmployeeList();

        $smarty = new Sugar_Smarty();
        $smarty->assign('KANBAN_TYPES', $kanban_types);
        $smarty->assign('KANBAN_TYPES_SELECT', $_COOKIE["careers-teams-view-selected"]);
        $smarty->assign('EMPLOYEE_LIST', $employee_list);
        $smarty->assign('MODULE', $this->module);

        if($isKanban)
        {
            $projects = $this->controller->getProjects();
            $project_allocation = $this->controller->getProjectAllocation();
            $selectProjects = array_map($mapNames,$projects );
            $smarty->assign('PROJECTS', $projects );
            $smarty->assign('PROJECTS_LIST', $selectProjects);
            $smarty->assign('PROJECTS_ALLOCATION', $project_allocation);
            $smarty->display('modules/CC_Teams/tpls/KanbanView.tpl');
        } elseif ($isPM || $isTL){
            $type_action = $isPM?'getPM':'getTL';
            $type_title = $isPM?'Project Managers':'Technical Leads';
            $managers = $isPM?$this->controller->getProjectManagersList():$this->controller->getTechLeadList();
            $managers_allocation = $this->controller->getManagerAllocation($managers);
            $select_managers = array_map($mapNames,$managers );
            $smarty->assign('TYPE_ACTION', $type_action );
            $smarty->assign('TYPE_TITLE', $type_title );
            $smarty->assign('MANAGERS', $managers );
            $smarty->assign('MANAGERS_LIST', $select_managers);
            $smarty->assign('MANAGERS_ALLOCATION', $managers_allocation);
            $smarty->display('modules/CC_Teams/tpls/ManagerView.tpl');
        } else {
            parent::display();
        }
    }
}
