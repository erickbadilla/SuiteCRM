<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

use Api\V8\Config\Common as Common;
use \BeanFactory;


class CC_Contact_AddressController extends SugarController {
    
    private static $moduleName = "CC_Contact_Address";

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param object $address
     * @return bool|SugarBean
     */
    public function saveAddressRecord(object $address)
    {
        $addressBean = BeanFactory::getBean(self::$moduleName);
        $addressBean->name = $address->AddressName;
        $addressBean->address = $address->Address;
        $addressBean->state = $address->AddressState;
        $addressBean->city = $address->AddressCity;
        $addressBean->district = $address->AddressDistrict;
        $addressBean->save();

        return $addressBean;
    }

}
