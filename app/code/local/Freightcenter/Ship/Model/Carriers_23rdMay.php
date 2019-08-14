<?php
class Freightcenter_Ship_Model_Carriers
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $current_freight_user = Mage::getStoreConfig('carriers/freightcenter/freightuser');
        
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $carrier_query = $connection->query("select * from brst_freight_carriers where username = '$current_freight_user'");
        $getcarrier = $carrier_query->fetchAll();
        //echo "<pre>";print_r($getcarrier);exit;
        $all_carriers = array();
        if($getcarrier == NULL) {
            $all_carriers = '';
        } else {
            foreach($getcarrier as $carrier) {
                $carrierid = $carrier['id'];
                $carriername = $carrier['carrier_name'];
                $all_carriers[] = array('value' => $carrierid, 'label'=>Mage::helper('adminhtml')->__($carriername));
            }
        }
        
        return $all_carriers;
    }
}