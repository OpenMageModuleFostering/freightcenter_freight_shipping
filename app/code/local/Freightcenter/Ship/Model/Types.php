<?php
class Freightcenter_Ship_Model_Types
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'flat', 'label'=>Mage::helper('adminhtml')->__('Flat')),
            array('value' => 'percent', 'label'=>Mage::helper('adminhtml')->__('Percentage')),
        );
    }

}