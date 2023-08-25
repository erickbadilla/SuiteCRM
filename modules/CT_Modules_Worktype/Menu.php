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

 if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('CT_Modules_Worktype', 'edit', true)){
    $module_menu[]=array('index.php?module=CT_Modules_Worktype&action=EditView&return_module=CT_Modules_Worktype&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'CT_Modules_Worktype');
}
if(ACLController::checkAccess('CT_Modules_Worktype', 'list', true)){
    $module_menu[]=array('index.php?module=CT_Modules_Worktype&action=index&return_module=CT_Modules_Worktype&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'CT_Modules_Worktype');
}
if(ACLController::checkAccess('CT_Modules_Worktype', 'import', true)){
    $module_menu[]=array('index.php?module=Import&action=Step1&import_module=CT_Modules_Worktype&return_module=CT_Modules_Worktype&return_action=index', $app_strings['LBL_IMPORT'], 'Import', 'CT_Modules_Worktype');
}