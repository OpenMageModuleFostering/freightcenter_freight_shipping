<?php
class Freightcenter_Ship_Model_Mark
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'none', 'label'=>Mage::helper('adminhtml')->__('None')),
            array('value' => 'markup', 'label'=>Mage::helper('adminhtml')->__('Markup')),
            array('value' => 'discount', 'label'=>Mage::helper('adminhtml')->__('Discount')),
        );
    }

}