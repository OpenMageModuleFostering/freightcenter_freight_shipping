<?php
class Freightcenter_Ship_Model_Loctypes
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'Residential', 'label'=>Mage::helper('adminhtml')->__('Residential')),
            array('value' => 'Business With Dock or Forklift', 'label'=>Mage::helper('adminhtml')->__('Business With Dock or Forklift')),
            array('value' => 'Business Without Dock or Forklift', 'label'=>Mage::helper('adminhtml')->__('Business Without Dock or Forklift')),
            array('value' => 'Construction Site', 'label'=>Mage::helper('adminhtml')->__('Construction Site')),
            array('value' => 'Convention Center or Tradeshow', 'label'=>Mage::helper('adminhtml')->__('Convention Center or Tradeshow')),
            array('value' => 'Terminal', 'label'=>Mage::helper('adminhtml')->__('Terminal')),
        );
    }

}