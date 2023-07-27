<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CC_Job_ApplicationsViewEdit extends ViewEdit
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
            $noaccessView = new ViewNoaccess();
            $noaccessView->display();
            sugar_die('');
        }
        $this->displayCreateEditApplication();
    }

    public function displayCreateEditApplication() {
        $smarty = new Sugar_Smarty();
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('TYPE', $this->bean->application_type ?? 'EXTERNAL' );
        $smarty->assign('MODULE', $this->module);
        $smarty->display('modules/CC_Job_Applications/tpls/edit.tpl');
    }
}