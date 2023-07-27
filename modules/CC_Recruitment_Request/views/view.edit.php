<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/CC_Recruitment_Request/controller.php';

class CC_Recruitment_RequestViewEdit extends ViewEdit
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
        $view = $this->displayCreate();
        echo $view;
    }

    public function displayCreate() {
        $smarty = new Sugar_Smarty();
        $RecruitmentRequest = (new CC_Recruitment_RequestController)->getAccount();
        
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('MODULE', $this->module ?? '');
        $smarty->assign('NAME', $this->bean->name ?? '' );
        $smarty->assign('OPEN_POSITION',  $this->bean->open_positions ?? '');
        $smarty->assign('DESCRIPTION',  $this->bean->description ?? '');
        $smarty->assign('ACCOUNTS', $RecruitmentRequest);
        (strpos($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"], "ajax_load") === false) ? $smarty->assign('URL_AJAX',"0") : $smarty->assign('URL_AJAX',"1");
        $smarty->display('modules/CC_Recruitment_Request/tpls/create.tpl');
    }


   

   
}


