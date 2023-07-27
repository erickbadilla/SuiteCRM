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

require_once 'modules/CT_daily_status_report/ct_daily_status_report_entrypoint_handler.class.php';

/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CT_daily_status_reportViewList extends ViewList
{
    /**
     * @see SugarView::$type
     */
    public  $type = 'list';
    private $related = null;
    private $from = null;
    private $to = null;
    private $handler = null;
    private $projects = [];
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

        $this->handler = new ct_daily_status_report_entrypoint_handler();

        $this->related = filter_input(INPUT_COOKIE, 'daily_status_related', FILTER_DEFAULT, $default);
        if($this->related === 'undefined' || $this->related === 'all'){
            $this->related = null;
        }
        $this->from = filter_input(INPUT_COOKIE, 'daily_status_date_from', FILTER_DEFAULT, $default);
        if($this->from === 'undefined'){
            $this->from = $this->handler->getFromDate();
        }
        $this->to = filter_input(INPUT_COOKIE, 'daily_status_date_to', FILTER_DEFAULT, $default);
        if($this->to === 'undefined'){
            $this->to = $this->handler->getToDate();
        }

        $this->projects = $this->handler->getProjectList();
        $this->projects = array('all' => (object)["name"=>"All Projects"]) + $this->projects;
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
        $this->displayListViewHandler();
    }

    public function displayListViewHandler() {

        $dateStart = (is_null($this->from) || $this->from == "" )?$this->handler->getFromDate():$this->from;
        $dateEnd = (is_null($this->to) || $this->to == "" )?$this->handler->getToDate():$this->to;

        $schedule_values = $this->handler->getScheduleSummary($dateStart,$dateEnd,$this->related);

        $smarty = new Sugar_Smarty();
        $smarty->assign('MOOD_EMPTY', 0);
        $smarty->assign('MOOD_FANTASTIC', 0);
        $smarty->assign('MOOD_GREAT', 0);
        $smarty->assign('MOOD_OKEY', 0);
        $smarty->assign('MOOD_DOWN', 0);
        $smarty->assign('MOOD_DEPRESSED', 0);
        $smarty->assign('MOOD_ANGRY', 0);
        $smarty->assign('MOOD_ANGRY', 0);

        $mood_values = $this->handler->getMoodSumary($dateStart,$dateEnd,$this->related);
        foreach ($mood_values as $key => $value){
            $smarty->assign('MOOD_'.strtoupper($key), $value);
        }

        $schedule_values = $this->handler->getScheduleSummary($dateStart,$dateEnd,$this->related);
        foreach ($schedule_values as $key => $value){
            $smarty->assign('MOOD_'.strtoupper($key), $value);
        }

        $smarty->assign('project_list', array_map(fn($value): string => $value->name, $this->projects));
        $smarty->assign('actual_project', $this->related);

        $smarty->assign('activity_from', $dateStart);
        $smarty->assign('activity_to', $dateEnd);

        $smarty->assign('BEANID', $this->bean->id);

        $employee_blocked = $this->handler->getEmployeesBlocked($dateStart,$dateEnd,$this->related);
        $employee_delayed = $this->handler->getEmployeesDelayed($dateStart,$dateEnd,$this->related);

        $missing_reports = $this->handler->getMissingReportsForADate($dateStart,$this->related);

        $smarty->assign('EMPLOYEE_DELEAYED', $employee_delayed);
        $smarty->assign('EMPLOYEE_BLOCK', $employee_blocked);
        $smarty->assign('EMPLOYEE_MISSING', $missing_reports);

        $smarty->display('modules/CT_daily_status_report/tpls/list.tpl');
    }
}
