<?php

use Symfony\Component\Validator\Constraints\Length;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AR_Activity_ReportViewEdit extends ViewEdit
{
    /**
     * @see SugarView::$type
     */
    public $type = 'edit';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        if (!$this->bean->ACLAccess('view') || !$this->bean->ACLAccess('save')) {
            SugarApplication::appendErrorMessage(translate('LBL_NO_ACCESS', 'ACL'));
            SugarApplication::redirect("index.php");
            sugar_die('');
        }
        $this->displayCreateEditJobOffer();
    }

    public function displayCreateEditJobOffer() {
        global $app_list_strings;
        $hours =array("06");
        for ($x = 7; $x <= 19; $x++) {
            $hr = strlen($x) == 1 ? '0'.$x : $x;
            array_push($hours,$hr);
        }
        $relates = array_keys($app_list_strings['parent_type_display']);
        $smarty = new Sugar_Smarty();
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('MODULE', $this->module);
        $smarty->assign('RELATES', $relates);
        $smarty->assign('HOURS', $hours);
        $smarty->assign('START_DATE', date('m/d/Y'));
        $smarty->display('modules/AR_Activity_Report/tpls/edit.tpl');
    }


}