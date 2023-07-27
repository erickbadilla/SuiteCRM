<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
class CC_business_valuesViewEdit extends ViewEdit
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
        parent::display();
    }
}