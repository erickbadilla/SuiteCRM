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
class AR_Activity_ReportViewList extends ViewList
{
    /**
     * @see SugarView::$type
     */
    public $type = 'list';
    private $related = null;
    private $from = null;
    private $to = null;
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

        $this->related = filter_input(INPUT_GET, 'activity_related', FILTER_DEFAULT, $default);
        $this->from = filter_input(INPUT_COOKIE, 'activity_date_from', FILTER_DEFAULT, $default);
        $this->to = filter_input(INPUT_COOKIE, 'activity_date_to', FILTER_DEFAULT, $default);
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
        global $app_list_strings;
        $month_before = strtotime("-3 months");
        $relates = array_keys($app_list_strings['parent_type_display']);
        $dateStart = (is_null($this->from) || $this->from == "" )?date('Y-m-d', $month_before):$this->from;
        $dateEnd = (is_null($this->to) || $this->to == "" )?date('Y-m-d'):$this->to;
        $related_to = (is_null($this->related))?"":$this->related;
        $smarty = new Sugar_Smarty();
        $smarty->assign('activity_from', $dateStart);
        $smarty->assign('activity_to', $dateEnd);
        $smarty->assign('related_to', $related_to);
        $smarty->assign('BEANID', $this->bean->id);
        $smarty->assign('RELATES', $relates);
        $smarty->display('modules/AR_Activity_Report/tpls/list.tpl');
    }
}
