<?php
/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class CC_Recruitment_RequestViewList extends ViewList
{
    /**
     * @see SugarView::$type
     */
    public $type = 'list';

    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
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
        $view = $this->displayListViewHandler();
        echo $view;  
    }

    public function displayListViewHandler() {
        $smarty = new Sugar_Smarty();
        $smarty->assign('BEANID', $this->bean->id);
        (strpos($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"], "ajax_load") === false) ? $smarty->assign('URL_AJAX',"0") : $smarty->assign('URL_AJAX',"1");
        $smarty->display('modules/CC_Recruitment_Request/tpls/ListView.tpl');
    }
}
