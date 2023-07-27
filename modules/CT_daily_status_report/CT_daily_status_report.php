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


class CT_daily_status_report extends Basic
{
    public $new_schema = true;
    public $module_dir = 'CT_daily_status_report';
    public $object_name = 'CT_daily_status_report';
    public $table_name = 'ct_daily_status_report';
    public $importable = true;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $project_id_c;
    public $project;
    public $cc_employee_information_id_c;
    public $employee;
    public $date_reported;
    public $schedule;
    public $mood;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Returns a limited list of the current object type.
     *
     * @param string $order_by the order by SQL parameter. defaults to ""
     * @param string $where where clause. defaults to ""
     * @param bool $check_dates . defaults to false
     * @param int $show_deleted show deleted records. defaults to 0
     * @return null|SugarBean[]
     */
    public function get_limited_list($order_by = "", $where = "", $check_dates = false, $show_deleted = 0, $start=0, $length=100 )
    {
        $GLOBALS['log']->debug("get_full_list:  order_by = '$order_by' and where = '$where'");
        if (isset($_SESSION['show_deleted'])) {
            $show_deleted = 1;
        }

        $query_array = $this->create_new_list_query($order_by, $where, array(), array(), $show_deleted,'', true);
        $query = $query_array['select'] . $query_array['from'] . $query_array['where'] . $query_array['order_by']. sprintf(" LIMIT %d, %d",$start,$length );
        return $this->process_full_list_query($query, $check_dates);
    }

}