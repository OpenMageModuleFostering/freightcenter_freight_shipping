<?php
class Freightcenter_Ship_Model_Mode
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('View as individual warehouse shipments')),
            array('value' => '0', 'label'=>Mage::helper('adminhtml')->__('View as Single Freight Charge')),
        );
    }

}