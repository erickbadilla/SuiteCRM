<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
class CC_business_valuesViewDetail extends ViewDetail
{
    /**
     * @see SugarView::$type
     */
    public $type = 'detail';
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
        parent::display();
    }
}