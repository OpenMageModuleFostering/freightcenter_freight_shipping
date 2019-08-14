<?php
class Freightcenter_CustomWare_Model_Mysql4_Collect extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {  
        $this->_init('freightcenter_customware/collect', 'id');
    }  
}