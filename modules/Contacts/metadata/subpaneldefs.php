<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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


$layout_defs['Contacts'] = array(
    // list of what Subpanels to show in the DetailView
    'subpanel_setup' => array(

        'activities' => array(
            'order' => 10,
            'sort_order' => 'desc',
            'sort_by' => 'date_due',
            'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'activities',   //this values is not associated with a physical file.
            'module' => 'Activities',

            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateTaskButton'),
                array('widget_class' => 'SubPanelTopScheduleMeetingButton'),
                array('widget_class' => 'SubPanelTopScheduleCallButton'),
                array('widget_class' => 'SubPanelTopComposeEmailButton'),
            ),
            'collection_list' => array(
                'tasks' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'tasks',
                ),
                'tasks_parent' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'tasks_parent',
                ),
                'meetings' => array(
                    'module' => 'Meetings',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'meetings',
                ),
                'calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForActivities',
                    'get_subpanel_data' => 'calls',
                ),
            )
        ),

        'history' => array(
            'order' => 20,
            'sort_order' => 'desc',
            'sort_by' => 'date_entered',
            'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'history',   //this values is not associated with a physical file.
            'module' => 'History',

            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
                array('widget_class' => 'SubPanelTopArchiveEmailButton'),
                array('widget_class' => 'SubPanelTopSummaryButton'),
                array('widget_class' => 'SubPanelTopFilterButton'),
            ),

            'collection_list' => array(
                'tasks' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'tasks',
                ),
                'tasks_parent' => array(
                    'module' => 'Tasks',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'tasks_parent',
                ),
                'meetings' => array(
                    'module' => 'Meetings',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'meetings',
                ),
                'calls' => array(
                    'module' => 'Calls',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'calls',
                ),
                'notes' => array(
                    'module' => 'Notes',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'notes',
                ),
                'emails' => array(
                    'module' => 'Emails',
                    'subpanel_name' => 'ForHistory',
                    'get_subpanel_data' => 'emails',
                ),
                'linkedemails' => array(
                    'module' => 'Emails',
                    'subpanel_name' => 'ForUnlinkedEmailHistory',
                    'get_subpanel_data' => 'function:get_unlinked_email_query',
                    'generate_select' => true,
                    'function_parameters' => array('return_as_array' => 'true'),
                ),
            ),
            'searchdefs' => array(
                'collection' =>
                    array(
                        'name' => 'collection',
                        'label' => 'LBL_COLLECTION_TYPE',
                        'type' => 'enum',
                        'options' => $GLOBALS['app_list_strings']['collection_temp_list'],
                        'default' => true,
                        'width' => '10%',
                    ),
                'name' =>
                    array(
                        'name' => 'name',
                        'default' => true,
                        'width' => '10%',
                    ),
                'current_user_only' =>
                    array(
                        'name' => 'current_user_only',
                        'label' => 'LBL_CURRENT_USER_FILTER',
                        'type' => 'bool',
                        'default' => true,
                        'width' => '10%',
                    ),
                'date_modified' =>
                    array(
                        'name' => 'date_modified',
                        'default' => true,
                        'width' => '10%',
                    ),
            ),
        ),
        'documents' => array(
            'order' => 25,
            'module' => 'Documents',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'documents',
            'top_buttons' =>
                array(
                    0 =>
                        array(
                            'widget_class' => 'SubPanelTopButtonQuickCreate',
                        ),
                    1 =>
                        array(
                            'widget_class' => 'SubPanelTopSelectButton',
                            'mode' => 'MultiSelect',
                        ),
                ),
        ),
/*
        'leads' => array(
            'order' => 60,
            'module' => 'Leads',
            'sort_order' => 'asc',
            'sort_by' => 'last_name, first_name',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'leads',
            'add_subpanel_data' => 'lead_id',
            'title_key' => 'LBL_LEADS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateLeadNameButton'),
                array('widget_class' => 'SubPanelTopSelectButton',
                    'popup_module' => 'Opportunities',
                    'mode' => 'MultiSelect',
                ),
            ),
        ),
*/
        'opportunities' => array(
            'order' => 30,
            'module' => 'Opportunities',
            'sort_order' => 'desc',
            'sort_by' => 'date_closed',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'opportunities',
            'add_subpanel_data' => 'opportunity_id',
            'title_key' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect')
            ),
        ),
        'cases' => array(
            'order' => 80,
            'sort_order' => 'desc',
            'sort_by' => 'case_number',
            'module' => 'Cases',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'cases',
            'add_subpanel_data' => 'case_id',
            'title_key' => 'LBL_CASES_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect')
            ),
        ),
        'bugs' => array(
            'order' => 90,
            'module' => 'Bugs',
            'sort_order' => 'desc',
            'sort_by' => 'bug_number',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'bugs',
            'add_subpanel_data' => 'bug_id',
            'title_key' => 'LBL_BUGS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect')
            ),
        ),
        'contacts' => array(
            'order' => 100,
            'module' => 'Contacts',
            'sort_order' => 'asc',
            'sort_by' => 'last_name, first_name',
            'subpanel_name' => 'ForContacts',
            'get_subpanel_data' => 'direct_reports',
            'add_subpanel_data' => 'contact_id',
            'title_key' => 'LBL_DIRECT_REPORTS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect')
            ),
        ),
        'project' => array(
            'order' => 110,
            'module' => 'Project',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'get_subpanel_data' => 'project',
            'subpanel_name' => 'default',
            'title_key' => 'LBL_PROJECTS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'campaigns' => array(
            'order' => 70,
            'module' => 'CampaignLog',
            'sort_order' => 'desc',
            'sort_by' => 'activity_date',
            'get_subpanel_data' => 'campaigns',
            'subpanel_name' => 'ForTargets',
            'title_key' => 'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE',
        ),
        'contact_aos_quotes' => array(
            'order' => 101,
            'module' => 'AOS_Quotes',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'AOS_Quotes',
            'get_subpanel_data' => 'aos_quotes',
        ),

        'contact_aos_invoices' => array(
            'order' => 102,
            'module' => 'AOS_Invoices',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'AOS_Invoices',
            'get_subpanel_data' => 'aos_invoices',
        ),

        'contact_aos_contracts' => array(
            'order' => 103,
            'module' => 'AOS_Contracts',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'AOS_Contracts',
            'get_subpanel_data' => 'aos_contracts',
        ),

        'fp_events_contacts' => array(
            'order' => 104,
            'module' => 'FP_events',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_FP_EVENTS_CONTACTS_FROM_FP_EVENTS_TITLE',
            'get_subpanel_data' => 'fp_events_contacts',
            'top_buttons' =>
                array(
                    0 =>
                        array(
                            'widget_class' => 'SubPanelTopButtonQuickCreate',
                        ),
                    1 =>
                        array(
                            'widget_class' => 'SubPanelTopSelectButton',
                            'mode' => 'MultiSelect',
                        ),
                ),
        ),
        'securitygroups' => array(
            'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect'),),
            'order' => 900,
            'sort_by' => 'name',
            'sort_order' => 'asc',
            'module' => 'SecurityGroups',
            'refresh_page' => 1,
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'SecurityGroups',
            'add_subpanel_data' => 'securitygroup_id',
            'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
        ),
    ),
);
