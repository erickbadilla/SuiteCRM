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

require_once 'modules/CC_Profile/controller.php';

/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CC_Recruitment_RequestViewDetail extends ViewDetail
{
    
    public $type = 'detail';

   
    public function __construct()
    {
        parent::__construct();
    }
    
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

        $this->displayDetail();
    }

    public function displayDetail() {
        $smarty = new Sugar_Smarty();      
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('MODULE', $this->module);
        $smarty->assign('NAME', $this->bean->name);
        $smarty->assign('OPEN_POSITION',  $this->bean->open_positions);
        $smarty->assign('DESCRIPTION',  html_entity_decode($this->bean->description) ?? '');
        (strpos($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"], "ajax_load") === false) ? $smarty->assign('URL_AJAX',"0") : $smarty->assign('URL_AJAX',"1");
        $smarty->display('modules/CC_Recruitment_Request/tpls/detail.tpl');
    }

}