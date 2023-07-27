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
require_once "modules/CC_application_stage/StageHandler.php";

class CC_Job_ApplicationsViewList extends ViewList
{
    /**
     * @see SugarView::$type
     */
    public $type = 'list';

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
        $smarty = new Sugar_Smarty();
        $smarty->display('modules/CC_Job_Applications/tpls/ListViewSelector.tpl');
        $default_view = "ListViewHandler.tpl";
        $default_type = "EXTERNAL";
        if(isset($_COOKIE) && key_exists("CC_job-applications-view-selected",$_COOKIE)) {
            $default_view = ($_COOKIE["CC_job-applications-view-selected"]=="kanban-type")?"KanbanViewHandler.tpl":"ListViewHandler.tpl";
        }
        if(isset($_COOKIE) && key_exists("CC_job-applications-type-selected",$_COOKIE)) {
            $default_type = ($_COOKIE["CC_job-applications-type-selected"]=="INTERNAL")?"INTERNAL":"EXTERNAL";
        }
        $this->displayListViewHandler($default_view,$default_type);
    }

    public function displayListViewHandler($default_view,$default_type) {
        $smarty = new Sugar_Smarty();
        $stageHandler = new StageHandler();
        $listStages = $stageHandler->getList($default_type);
        $smarty->assign('STAGES', $listStages ?? [] );
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('MODULE', $this->module);
        $smarty->assign('BEANID', $this->bean->id);
        $smarty->display('modules/CC_Job_Applications/tpls/'.$default_view);
    }

}
