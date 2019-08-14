<?php
class Freightcenter_Ship_Model_Sand
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>Mage::helper('adminhtml')->__('Sandbox')),
            array('value' => '0', 'label'=>Mage::helper('adminhtml')->__('Live')),
        );
    }

}