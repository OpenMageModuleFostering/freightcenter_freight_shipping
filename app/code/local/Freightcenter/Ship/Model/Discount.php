<?php
class Freightcenter_Ship_Model_Discount
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('Enable')),
            array('value' => '0', 'label'=>Mage::helper('adminhtml')->__('Disable')),
        );
    }

}