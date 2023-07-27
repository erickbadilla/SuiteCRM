<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use CC_ProfileController;

require_once 'modules/CC_Profile/controller.php';

class CC_CandidateViewDetail extends ViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function CC_CandidateViewDetail()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    public function display()
    {
        if (!$this->bean->ACLAccess('view')) {
            $noaccessView = new ViewNoaccess();
            $noaccessView->display();
            sugar_die('');
        }
        print '<style type="text/css">[value="Full Form"]{ display:none; }</style>';
        parent::display();
        $this->displayProfileMatcher();
    }

    public function displayProfileMatcher() {
        $arrProfiles = (new CC_ProfileController)->getAllProfiles();

        $keys = [
            "Id",
            "Name"
        ];

        $smarty = new Sugar_Smarty();
        $smarty->assign('BEANID', $this->bean->id);
        $smarty->assign('MODULENAME', $this->bean->object_name);
        $smarty->assign('TITLE', explode("_",$this->bean->object_name)[1]);
        $smarty->assign("TYPE_OPTIONS", get_select_options_with_id($arrProfiles, $keys));
        $smarty->display('modules/CC_Profile/ProfileMatcher.tpl');
    }

}

