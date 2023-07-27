<?php

use Symfony\Component\Validator\Constraints\Length;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CT_daily_status_reportViewEdit extends ViewEdit
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

        $this->displayEditDailyStatusReport();

    }

    public function displayEditDailyStatusReport() {

    }


}