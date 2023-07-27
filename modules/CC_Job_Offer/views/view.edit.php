<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CC_Job_OfferViewEdit extends ViewEdit
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
        $this->displayCreateEditJobOffer();
    }

    public function displayCreateEditJobOffer() {
        
        $smarty = new Sugar_Smarty();
        $smarty->assign('BEANID', $this->bean->id ?? 'null' );
        $smarty->assign('MODULE', $this->module);

        $jobOfferAccountsData = $this->bean->getJobOfferRelatedAccountsInfo();

        if($this->bean->id == null){
            $smarty->assign('ACCOUNTS', $jobOfferAccountsData);
            $smarty->display('modules/CC_Job_Offer/tpls/create.tpl');
        }else{
            $smarty->assign('NAME', $this->bean->name ?? 'null' );
            $smarty->assign('CONTRACT_TYPE', $this->bean->contract_type ?? 'null' );
            $smarty->assign('EXPIRE_ON', $this->bean->expire_on ?? 'null' );
            $smarty->assign('ASSIGNED_LOCATION', $this->bean->assigned_location ?? 'null' );
            $smarty->assign('DESCRIPTION', $this->bean->description ?? 'null' );
            $smarty->assign('CONTACT_TYPE', $this->bean->contract_type ?? 'null' );
            $smarty->assign('IS_PUBLISHED', $this->bean->is_published ?? 'null' );
            $smarty->assign('FILE_URL', $this->bean->file_url ?? 'null' );
            $smarty->display('modules/CC_Job_Offer/tpls/edit.tpl');
        }
    }
}